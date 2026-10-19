# Talviro Chat Laravel API foundation

This directory contains the app-specific source to place into a clean Laravel 12 project (PHP 8.2+). It is not the Laravel framework itself; install the framework with Composer, then copy these files into the project. Do not deploy until tests and the security checklist are complete.

## Install
1. In cPanel Terminal/SSH, create a Laravel 12 project in a directory outside public_html: `composer create-project laravel/laravel:^12.0 talviro-api`
2. In that project run: `composer require laravel/sanctum`
3. Publish Sanctum config/migrations if the package version asks for it, then enable Sanctum's API token support according to Laravel 12 documentation.
4. Copy this folder's `app/`, `routes/api.php`, and `database/migrations/` into the Laravel project, merging rather than overwriting framework files.
5. Configure DB values in .env, run `php artisan migrate`, and set APP_ENV=production, APP_DEBUG=false, APP_KEY, and HTTPS URL.
6. Point the domain document root at Laravel's `public/` directory. Never expose project root, .env, storage or vendor to the web.
7. Test APIs with a staging database before using real users.

## Included API foundation
- Email/password registration and login with Sanctum personal access tokens.
- Authenticated profile endpoint and logout.
- User search by username/display name.
- Direct/group conversation creation and listing.
- Conversation membership authorization on message list/send/read operations.
- Text message status timestamps.

## Not included yet
Phone OTP provider integration, FCM send service, media scanning/storage, WebRTC signaling, TURN/SFU group calls, story/status, block/report workflow, admin moderation screens, E2EE, push notifications, and complete Android UI integration. Do not represent these as working until implemented and tested. Never store passwords as plaintext or trust client-supplied sender IDs.
