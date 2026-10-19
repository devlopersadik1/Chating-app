<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->validate(['q' => ['required','string','min:2','max:50']]);
        $term = addcslashes($data['q'], '%_\\\\');
        $users = User::query()
            ->where('is_banned', false)
            ->where('id', '<>', $request->user()->id)
            ->where(function ($q) use ($term) {
                $q->where('username', 'like', $term.'%')
                  ->orWhere('name', 'like', '%'.$term.'%');
            })
            ->select(['id','name','username','avatar_url','is_online'])
            ->limit(20)->get();
        return response()->json(['data' => $users]);
    }
}
