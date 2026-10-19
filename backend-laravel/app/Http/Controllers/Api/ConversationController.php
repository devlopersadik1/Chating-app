<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        $items = Conversation::query()
            ->whereHas('members', fn($q) => $q->where('users.id', $request->user()->id))
            ->with(['members:id,name,username,avatar_url,is_online'])
            ->withMax('messages', 'created_at')
            ->orderByDesc('messages_max_created_at')
            ->limit(100)->get();
        return response()->json(['data' => $items]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kind' => ['required','in:direct,group'],
            'title' => ['nullable','string','max:120'],
            'member_ids' => ['required','array','min:1','max:99'],
            'member_ids.*' => ['required','integer','distinct','exists:users,id'],
        ]);
        $ids = array_values(array_unique(array_map('intval', $data['member_ids'])));
        if (in_array($request->user()->id, $ids, true)) {
            throw ValidationException::withMessages(['member_ids' => ['Do not include your own ID; the current user is added automatically.']]);
        }
        if ($data['kind'] === 'direct' && count($ids) !== 1) {
            throw ValidationException::withMessages(['member_ids' => ['A direct conversation needs exactly one other user.']]);
        }
        $ids[] = $request->user()->id;
        $ids = array_values(array_unique($ids));
        if ($data['kind'] === 'group' && count($ids) < 3) {
            throw ValidationException::withMessages(['member_ids' => ['A group needs at least three members including you.']]);
        }
        $conversation = DB::transaction(function () use ($data, $ids, $request) {
            $c = Conversation::create(['kind' => $data['kind'], 'title' => $data['title'] ?? null, 'created_by' => $request->user()->id]);
            $attach = [];
            foreach ($ids as $id) $attach[$id] = ['role' => $id === $request->user()->id ? 'owner' : 'member'];
            $c->members()->attach($attach);
            return $c;
        });
        return response()->json(['data' => $conversation->load('members:id,name,username,avatar_url')], 201);
    }
}
