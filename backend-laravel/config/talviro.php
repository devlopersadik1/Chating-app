<?php

return [
    'app_name' => env('APP_NAME', 'Talviro Chat'),
    'api_prefix' => 'api/v1',
    'otp' => [
        'driver' => env('OTP_DRIVER', 'disabled'),
        'ttl_seconds' => (int) env('OTP_TTL_SECONDS', 300),
        'max_attempts' => (int) env('OTP_MAX_ATTEMPTS', 5),
    ],
    'sms' => [
        'provider' => env('SMS_PROVIDER'),
        'api_url' => env('SMS_API_URL'),
        'api_key' => env('SMS_API_KEY'),
        'sender_id' => env('SMS_SENDER_ID'),
    ],
    'fcm' => [
        'project_id' => env('FCM_PROJECT_ID'),
        'credentials_path' => env('GOOGLE_APPLICATION_CREDENTIALS'),
    ],
    'media' => [
        'disk' => env('MEDIA_DISK', 'local'),
        'max_image_mb' => (int) env('MEDIA_MAX_IMAGE_MB', 12),
        'max_video_mb' => (int) env('MEDIA_MAX_VIDEO_MB', 100),
        'max_file_mb' => (int) env('MEDIA_MAX_FILE_MB', 25),
    ],
    'webrtc' => [
        'turn_url' => env('TURN_URL'),
        'turn_username' => env('TURN_USERNAME'),
        'turn_credential' => env('TURN_CREDENTIAL'),
        'livekit_url' => env('LIVEKIT_URL'),
        'livekit_api_key' => env('LIVEKIT_API_KEY'),
        'livekit_api_secret' => env('LIVEKIT_API_SECRET'),
    ],
    'rate_limit_per_minute' => (int) env('API_RATE_LIMIT_PER_MINUTE', 60),
];
