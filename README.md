# Messenger-style Android Chat App — Starter

Stack: Android Java/XML, PHP 8+, MySQL, Firebase Cloud Messaging (FCM).

**Important:** this is a starter scaffold, not a finished Messenger clone. Authentication is intentionally disabled until a secure token/session verifier is configured. Chat endpoints, full real-time messaging, and WebRTC audio/video/group calls still need implementation and end-to-end testing.

## Folders
- `android-app/` — Android Java/XML starter project
- `backend-php/` — PHP API foundation
- `database/schema.sql` — MySQL schema
- `docs/FIREBASE_SETUP.md` — Firebase project, FCM, backend, and calling setup
- `docs/WEBRTC.md` — WebRTC and group calling architecture
- `docs/SECURITY.md` — security checklist

## Setup
1. Follow [Firebase setup](docs/FIREBASE_SETUP.md) to create a Firebase project and add `google-services.json` locally.
2. Import `database/schema.sql` into MySQL.
3. Copy `backend-php/.env.example` to `.env` on your server and set DB values.
4. Run `composer install` inside `backend-php/`.
5. Configure Firebase service-account JSON outside the public web root; never commit it.
6. Set the HTTPS API URL in `android-app/app/src/main/java/com/example/messenger/ApiConfig.java`.

Do not deploy before implementing authentication and conversation membership checks. Calls require a real signaling service plus STUN/TURN (and an SFU such as LiveKit for scalable group calls); they are not implemented yet.
