<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MessageController extends Controller
{
    private function member(Conversation $conversation, int $userId): bool
    {
        return $conversation->members()->where('users.id', $userId)->exists();
    }

    public function index(Request $request, Conversation $conversation)
    {
        abort_unless($this->member($conversation, $request->user()->id), 403);
        $data = $request->validate(['before_id' => ['sometimes','integer','min:1']]);
        $q = $conversation->messages()->with('sender:id,name,username,avatar_url')->orderByDesc('id');
        if (isset($data['before_id'])) $q->where('id', '<', $data['before_id']);
        $messages = $q->limit(50)->get()->reverse()->values();
        return response()->json(['data' => $messages]);
    }

    public function store(Request $request, Conversation $conversation)
    {
        abort_unless($this->member($conversation, $request->user()->id), 403);
        $data = $request->validate(['body' => ['required','string','max:5000']]);
        $message = $conversation->messages()->create([
            'sender_id' => $request->user()->id,
            'body' => trim($data['body']),
            'type' => 'text',
            'sent_at' => now(),
        ]);
        return response()->json(['data' => $message->load('sender:id,name,username,avatar_url')], 201);
    }

    public function markRead(Request $request, Conversation $conversation)
    {
        abort_unless($this->member($conversation, $request->user()->id), 403);
        DB::table('conversation_members')
            ->where('conversation_id', $conversation->id)
            ->where('user_id', $request->user()->id)
            ->update(['last_read_at' => now(), 'updated_at' => now()]);
        return response()->json(['message' => 'Read marker updated']);
    }
}
