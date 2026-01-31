<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject'       => 'nullable|string|max:255',
            'body'          => 'required|string',
            'recipients'    => 'required|array',
            'recipients.*'  => 'exists:users,id',
            'attachments.*' => 'file|max:20480',
        ]);

        return DB::transaction(function () use ($request, $data) {
            $message = Message::create([
                'sender_id'     => auth()->id(),
                'subject'       => $data['subject'] ?? null,
                'body'          => $data['body'],
            ]);

            $message->recipients()->attach($data['recipients']);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    MessageAttachment::create([
                        'message_id' => $message->id,
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $file->store('messages'),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ]);
                }
            }

            return response()->json([
                'id' => $message->id,
                'created_at' => $message->created_at,
            ], 201);
        });

    }

    /**
     * Display the specified resource.
     */
    public function show(r $r)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(r $r)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, r $r)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(r $r)
    {
        //
    }
}
