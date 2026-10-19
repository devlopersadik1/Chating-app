# Talviro Chat — Android + Laravel/PHP/MySQL starter

Android source is maintained here so GitHub Actions can build APK artifacts. The PHP backend is deployed separately to cPanel.

## Source folders
- `android-app/` — Android Java/XML starter
- `backend-laravel/` — Laravel 12 app-specific API files to merge into a clean Laravel project (PHP 8.2+)
- `backend-php/` — earlier PHP backend/admin foundation; do not deploy both backends to the same API URL
- `backend-php/admin-panel/` — earlier standalone admin-panel foundation
- `database/schema.sql` — earlier backend schema
- `database/migrations/001_admin_control_panel.sql` — earlier admin schema migration
- `.github/workflows/build-apk.yml` — GitHub Actions debug APK build

## Build Android APK
1. Open **Actions**.
2. Run **Build Talviro Chat APK** or push a change under `android-app/`.
3. Download the `talviro-chat-debug-apk` artifact after a successful run.

The workflow builds a debug APK only. Release signing requires a securely managed keystore. Never commit keystore/passwords, Firebase private keys, or production .env files.

## Laravel API setup
Read `backend-laravel/README.md` and `backend-laravel/docs/SECURITY-RELEASE-CHECKLIST.md`. Create a clean Laravel 12 project with Composer, install Sanctum, merge the app-specific files and update the default User model as documented. Configure the cPanel MySQL database, run migrations and point the domain document root to Laravel's `public/` directory.

## Status and important limitations
This repository is still under development and is **not** a complete or production-certified Messenger clone. The Laravel files currently provide a foundation for email/password authentication, profile lookup, conversation membership, and text messages. Phone OTP, FCM delivery, media uploads, message receipt states, stories, moderation workflows, WebRTC signaling, TURN/SFU group calls, E2EE and full Android integration are not yet fully implemented and tested.

Before public launch, implement and test API authorization, rate limits, account recovery/deletion, upload validation, logging/monitoring, backups and privacy workflows. HTTPS alone is not end-to-end encryption. Keep secrets out of GitHub.
