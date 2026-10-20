<?php

namespace App\Services;

use Google\Auth\ApplicationDefaultCredentials;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class FcmService
{
    public function sendChatMessage(string $token, string $title, string $body, int $conversationId, int $messageId): bool
    {
        $projectId = config('talviro.fcm.project_id');
        if (!$projectId) {
            return false;
        }

        try {
            $credentials = ApplicationDefaultCredentials::getCredentials(
                'https://www.googleapis.com/auth/firebase.messaging'
            );
            $auth = $credentials->fetchAuthToken();
            $accessToken = $auth['access_token'] ?? null;
            if (!$accessToken) {
                Log::warning('FCM access token unavailable');
                return false;
            }

            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->timeout(5)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => mb_substr($title, 0, 100),
                            'body' => mb_substr($body, 0, 300),
                        ],
                        'data' => [
                            'type' => 'chat_message',
                            'conversation_id' => (string) $conversationId,
                            'message_id' => (string) $messageId,
                        ],
                        'android' => ['priority' => 'HIGH'],
                    ],
                ]);

            if ($response->successful()) {
                return true;
            }

            Log::warning('FCM message rejected', ['status' => $response->status()]);
            return false;
        } catch (Throwable $e) {
            // Do not log access tokens, service-account contents, or message text.
            Log::warning('FCM delivery failed', ['exception' => class_basename($e)]);
            return false;
        }
    }
}
