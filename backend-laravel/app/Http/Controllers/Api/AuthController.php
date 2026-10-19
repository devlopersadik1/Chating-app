<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','min:2','max:80'],
            'username' => ['required','string','min:3','max:30','alpha_dash','unique:users,username'],
            'email' => ['required','email:rfc','max:190','unique:users,email'],
            'password' => ['required','string','min:10','max:128'],
        ]);
        $user = User::create([
            'name' => $data['name'],
            'username' => strtolower($data['username']),
            'email' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
        ]);
        $token = $user->createToken('android')->plainTextToken;
        return response()->json(['user' => $user->only(['id','name','username','email','avatar_url']), 'token' => $token], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => ['required','email:rfc','max:190'], 'password' => ['required','string','max:128']]);
        $user = User::where('email', strtolower($data['email']))->first();
        if (!$user || !Hash::check($data['password'], $user->password) || $user->is_banned) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials or account unavailable.']]);
        }
        $token = $user->createToken('android')->plainTextToken;
        return response()->json(['user' => $user->only(['id','name','username','email','avatar_url']), 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['message' => 'Signed out']);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()->only(['id','name','username','email','avatar_url','is_online'])]);
    }
}
