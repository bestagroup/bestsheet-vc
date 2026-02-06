<?php

namespace App\Http\Controllers\Panel;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CorrespondenceController extends Controller
{
    public function index()
    {

        $thispage       = [
            'title'   => 'مدیریت  پیام ها ',
            'list'    => 'لیست  پیام ها ',
            'add'     => 'افزودن  پیام ها ',
            'create'  => 'ایجاد  پیام ها ',
            'enter'   => 'ورود  پیام ها ',
            'edit'    => 'ویرایش  پیام ها ',
            'delete'  => 'حذف  پیام ها ',
        ];

        $userId = auth()->id();

        $users = User::select('id', 'name')->get();

        $conversations = Message::with([
            'sender:id,name',
            'recipients:id,name',
            'replies.sender:id,name',
            'attachments'
        ])
            ->where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                    ->orWhereHas('recipients', fn ($r) => $r->where('user_id', $userId));
            })
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return view('panel.correspondence', compact('users', 'conversations', 'thispage'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject'       => 'nullable|string|max:255',
            'body'          => 'required|string',
            'recipients'    => 'required|array',
            'recipients.*'  => 'exists:users,id',
            'parent_id'     => 'nullable|exists:messages,id',
            'attachments.*' => 'file|max:20480',
        ]);

        return DB::transaction(function () use ($request, $data) {

            // 1️⃣ ایجاد پیام
            $message = Message::create([
                'sender_id' => auth()->id(),
                'subject'   => $data['subject'] ?? null,
                'body'      => $data['body'],
                'parent_id' => $data['parent_id'] ?? null,
            ]);

            // 2️⃣ گیرندگان
            if (!empty($data['parent_id'])) {
                $parent = Message::with('recipients')->findOrFail($data['parent_id']);
                $message->recipients()->sync($parent->recipients->pluck('id'));
            } else {
                $message->recipients()->attach($data['recipients']);
            }

            // 3️⃣ پیوست‌ها
            $attachments = [];
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $fileName  = uniqid() . '.' . $extension;

                    $attachment = MessageAttachment::create([
                        'message_id'    => $message->id,
                        'original_name' => $file->getClientOriginalName(),
                        'path'          => $file->storeAs("messages/".$message->id, $fileName, 'public'),
                        'size'          => $file->getSize(),
                        'mime_type'     => $file->getMimeType(),
                    ]);

                    $attachments[] = [
                        'id'   => $attachment->id,
                        'name' => $attachment->original_name,
                        'url'  => asset('storage/'.$attachment->path),
                        'size' => $attachment->size,
                        'mime' => $attachment->mime_type,
                    ];
                }
            }

            // 4️⃣ 🔥 broadcast دقیقاً اینجاست (بعد از همه‌چیز)
            broadcast(new MessageSent($message))->toOthers();


            // 5️⃣ پاسخ
            return response()->json([
                'id' => $message->id,
                'created_at' => $message->created_at,
            ], 201);
        });
    }
}
