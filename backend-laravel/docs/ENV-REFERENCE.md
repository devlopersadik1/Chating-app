# Environment configuration reference

The real `.env` file belongs at the Laravel project root on the server and must not be committed. `.env.example` contains placeholders only. Replace example domains and CHANGE_ME values in the server's private `.env`.

## Required for basic API
- `APP_KEY`: generate using `php artisan key:generate`.
- `APP_URL`: HTTPS base URL for the API.
- `DB_*`: database name, username, and password created in cPanel.
- `APP_DEBUG=false` in production.

## Optional integrations
- SMTP: needed for email verification/password reset.
- SMS provider: required before phone OTP can work. A provider account, API endpoint and credentials must be supplied by the owner.
- Firebase: configure server credentials for FCM. Do not commit service-account JSON; set `GOOGLE_APPLICATION_CREDENTIALS` to a private absolute path outside public web root.
- TURN and LiveKit: real credentials/service are needed for reliable WebRTC and group calls. cPanel hosting alone does not provide an SFU.
- Admin audit key: generate a long random secret, keep it private and rotate it carefully.

## Important distinction
The Android app needs a public API base URL (for example `https://api.your-domain.tld/api/v1/`). It must never contain DB passwords, SMS private keys, FCM service-account credentials, TURN private credentials, or LiveKit API secrets. Android values can be extracted from an APK, so only public configuration belongs there.

Do not paste production passwords or private API keys into chat or GitHub. Use your hosting control panel's private environment/configuration facilities, or a server-side `.env` file outside the public document root.
