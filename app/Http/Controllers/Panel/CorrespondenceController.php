<?php

namespace App\Http\Controllers\Panel;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MessageAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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
            'users' => \App\Models\User::select('id', 'name')->get(),
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
}
