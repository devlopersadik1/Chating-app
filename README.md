# Messenger-style Android Chat App — Starter

Stack: Android Java/XML, PHP 8+, MySQL, Firebase Cloud Messaging (FCM).

**Important:** this is a starter scaffold, not a finished Messenger clone. Authentication is intentionally disabled until a secure token/session verifier is configured. Chat endpoints, full real-time messaging, and WebRTC audio/video/group calls still need implementation and end-to-end testing.

## Folders
- `android-app/` — Android Java/XML starter project
- `backend-php/` — PHP API foundation
- `database/schema.sql` — MySQL schema
- `docs/` — WebRTC and security setup notes

## Setup
1. Import `database/schema.sql` into MySQL.
2. Copy `backend-php/.env.example` to `.env` and set DB values.
3. Run `composer install` inside `backend-php/`.
4. Configure Firebase service-account JSON outside the public web root; never commit it.
5. Add your Firebase `google-services.json` to `android-app/app/` locally (do not commit secrets).
6. Set the HTTPS API URL in `ApiConfig.java`.

Do not deploy before implementing authentication and conversation membership checks. Calls require a real signaling service plus STUN/TURN; they are not implemented yet.