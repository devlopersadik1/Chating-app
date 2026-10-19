package com.example.messenger;

/**
 * Public API endpoint configuration only.
 * Never put database passwords, SMS secrets, FCM service-account keys,
 * TURN credentials, or LiveKit API secrets in this Android app.
 */
public final class ApiConfig {
    private ApiConfig() {}

    // Replace with the HTTPS URL of your deployed Laravel API.
    // Example: https://api.your-domain.tld/
    public static final String API_BASE_URL = "https://api.example.com/";

    // Laravel routes in backend-laravel/routes/api.php use the v1 prefix.
    public static final String API_V1_URL = API_BASE_URL + "api/v1/";
}
