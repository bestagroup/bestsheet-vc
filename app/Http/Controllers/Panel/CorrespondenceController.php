<?php

namespace App\Http\Controllers\Panel;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class CorrespondenceController extends Controller
{
    /* ===================== LIST ===================== */

    public function index()
    {
        //Artisan::call('cache:clear');
        $user = auth()->user();

        $conversations = Conversation::with([
            'users:id,name',
            'lastMessage.sender:id,name',
            'lastMessage.attachments',
            'messages' => fn ($q) => $q->with([
                'sender:id,name',
                'attachments',
            ])->orderBy('created_at'),
        ])
            ->whereHas('users', fn ($q) => $q->where('users.id', $user->id))
            ->latest('updated_at')
            ->get();

        return view('panel.correspondence', [
            'conversations' => $conversations,
            'thispage'       => [
                'title'   => 'مدیریت مکاتبات',
                'list'    => 'لیست مکاتبات',
                'add'     => 'افزودن مکاتبات',
                'create'  => 'ایجاد مکاتبات',
                'enter'   => 'ورود مکاتبات',
                'edit'    => 'ویرایش مکاتبات',
                'delete'  => 'حذف مکاتبات',
            ],
            'users' => User::select('id', 'name')->get(),
        ]);
    }

    public function data()
    {
        $user = auth()->user();

        $conversations = Conversation::with([
            'users:id,name',
            'lastMessage.sender:id,name',
            'lastMessage.attachments',
            'messages' => fn ($q) => $q->with([
                'sender:id,name',
                'attachments',
            ])->orderBy('created_at'),
        ])
            ->whereHas('users', fn ($q) => $q->where('users.id', $user->id))
            ->latest('updated_at')
            ->get();

        $users = User::select('id', 'name')->get();

        return response()->json([
            'authUserId' => auth()->id(),
            'users' => $users->mapWithKeys(fn ($u) => [
                $u->id => [
                    'id' => $u->id,
                    'name' => $u->name,
                ],
            ]),
            'conversations' => $conversations->map(function ($c) {
                $messages = $c->messages->sortBy('created_at')->values();
                $rootMsg = $messages->firstWhere('parent_id', null) ?? $messages->first();
                $replies = $rootMsg
                    ? $messages->where('id', '!=', $rootMsg->id)->values()
                    : collect();
                $lastMsg = $messages->last();

                $mapMessage = function ($m) {
                    return [
                        'id' => 'm'.$m->id,
                        'senderId' => $m->sender_id,
                        'body' => $m->body,
                        'time' => $m->created_at,
                        'attachments' => $m->attachments->map(fn ($a) => [
                            'id' => $a->id,
                            'name' => $a->original_name,
                            'url' => $a->url,
                        ])->toArray(),
                    ];
                };

                return [
                    'id' => $c->id,
                    'subject' => $c->subject,
                    'participants' => $c->users->pluck('id')->toArray(),
                    'unread' => $c->pivot->unread_count ?? 0,
                    'lastActivity' => optional($lastMsg)->created_at ?? optional($c->lastMessage)->created_at,
                    'messages' => [
                        'root' => $rootMsg ? $mapMessage($rootMsg) : null,
                        'replies' => $replies->map($mapMessage)->values()->toArray(),
                    ],
                ];
            })->values(),
        ]);
    }

    /* ===================== STORE ===================== */

    public function store(Request $request)
    {
        $data = $request->validate([
            'conversation_id' => 'nullable|exists:conversations,id',
            'subject'         => 'nullable|string|max:255',
            'body'            => 'required|string',
            'recipients'      => 'nullable|array',
            'recipients.*'    => 'exists:users,id',
            'parent_id'       => 'nullable|exists:messages,id',
            'attachments.*'   => 'file|max:20480',
        ]);

        return DB::transaction(function () use ($data, $request) {

            /* -------- Conversation -------- */

            if (empty($data['conversation_id'])) {
                $conversation = Conversation::create([
                    'subject' => $data['subject'] ?? null,
                    'type'    => 'internal',
                ]);

                $conversation->users()->attach(
                    array_unique(array_merge(
                        $data['recipients'],
                        [auth()->id()]
                    )),
                    ['unread_count' => 0]
                );
            } else {
                $conversation = Conversation::findOrFail($data['conversation_id']);
            }

            /* -------- Message -------- */

            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => auth()->id(),
                'parent_id'       => $data['parent_id'] ?? null,
                'body'            => $data['body'],
            ]);

            /* -------- Attachments -------- */

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store(
                        "messages/{$message->id}",
                        'public'
                    );

                    MessageAttachment::create([
                        'message_id'    => $message->id,
                        'path'          => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type'     => $file->getMimeType(),
                        'size'          => $file->getSize(),
                    ]);
                }
            }

            /* -------- Unread Count -------- */

            $conversation->users()
                ->where('users.id', '!=', auth()->id())
                ->increment('conversation_user.unread_count');

            /* -------- Broadcast -------- */

            broadcast(new MessageSent($message))->toOthers();

            return response()->json([
                'conversation_id' => $conversation->id,
                'message_id'      => $message->id,
            ], 201);
        });
    }

    public function show(Request $request , $id)
    {
        $project = Project::whereId($id)->firstOrFail();
        if ($request->ajax()) {

            $data = Conversation::query()
                ->whereHas('users', fn ($q) => $q->where('users.id', $project->user_id))
                ->with([
                    'messages.sender:id,name',
                    'messages.attachments:id,message_id,path,mime_type',
                ])
                ->get()
                ->flatMap(function ($conversation) {
                    return $conversation->messages->map(function ($message) use ($conversation) {
                        return [
                            'id'        => $message->id,
                            'subject'   => $conversation->subject,
                            'body'      => $message->body,
                            'file_path' => optional($message->attachments->first())->path,
                            'type'      => optional($message->attachments->first())->mime_type,
                            'user'      => optional($message->sender_id)->name,
                            'date'      => $message->created_at,
                        ];
                    });
                })
                ->values();

            return DataTables::of($data)
                ->editColumn('file_path', function ($row) {

                    if (empty($row['file_path'])) {
                        return '';
                    }

                    $fileUrl = asset('storage/' . $row['file_path']);

                    return match ($row['type']) {
                        'image' => '<img src="'.$fileUrl.'" width="80">',
                        'audio' => '<audio controls><source src="'.$fileUrl.'" type="audio/mpeg"></audio>',
                        'video' => '<video width="160" controls><source src="'.$fileUrl.'" type="video/mp4"></video>',
                        default => '<a href="'.$fileUrl.'" target="_blank">دانلود فایل</a>',
                    };
                })
                ->editColumn('date', fn ($row) => jdate($row['date'])->format('Y/m/d'))
                ->rawColumns(['file_path'])
                ->make(true);
        }
    }
}
