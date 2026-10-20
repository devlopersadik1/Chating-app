<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string', 'max:4096'],
            'platform' => ['sometimes', 'in:android'],
        ]);

        $request->user()->deviceTokens()->updateOrCreate(
            ['token' => $data['token']],
            ['platform' => $data['platform'] ?? 'android', 'last_seen_at' => now()]
        );

        return response()->json(['message' => 'Device registered for notifications']);
    }

    public function destroy(Request $request)
    {
        $data = $request->validate(['token' => ['required', 'string', 'max:4096']]);
        DeviceToken::where('user_id', $request->user()->id)
            ->where('token', $data['token'])
            ->delete();

        return response()->json(['message' => 'Device unregistered']);
    }
}
