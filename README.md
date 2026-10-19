# Talviro Chat — Android + PHP/MySQL starter

Stack: Android Java/XML, PHP 8+, MySQL, Firebase Cloud Messaging (FCM), with WebRTC architecture planned for calls.

**Important:** this is a starter project, not a finished Messenger clone. Login verification, fully working real-time messaging, media delivery, FCM sending, WebRTC calls, group-call media infrastructure, and end-to-end encryption still require implementation and end-to-end testing. Do not deploy it as a public production service yet.

## Repository layout
- `android-app/` — Android Java/XML source
- `backend-php/` — PHP backend foundation
- `backend-php/admin-panel/` — owner/admin console foundation
- `database/schema.sql` — core MySQL schema
- `database/migrations/001_admin_control_panel.sql` — admin roles, audit log and settings schema
- `.github/workflows/build-apk.yml` — GitHub Actions debug APK build
- `docs/FIREBASE_SETUP.md` — Firebase setup
- `docs/WEBRTC.md` — call architecture
- `docs/SECURITY.md` — security checklist

## Build APK
1. Open the repository's **Actions** tab.
2. Select **Build Talviro Chat APK** and run the workflow, or push a change under `android-app/`.
3. When the run succeeds, open the run and download the `talviro-chat-debug-apk` artifact.

This workflow produces a debug APK only. A release APK needs a securely managed signing key; never commit keystores or passwords.

## cPanel setup outline
1. Back up your database and import `database/schema.sql`.
2. Import `database/migrations/001_admin_control_panel.sql` into the same database.
3. Deploy PHP files to your hosting account. Keep secrets, service-account credentials and uploads outside the public web root wherever possible.
4. Install PHP dependencies with Composer if required by the backend.
5. Configure the database, a strong audit hash key and Firebase credentials using your host's protected configuration method.
6. Follow `backend-php/admin-panel/README.md` for creating the first owner account. The owner setup script is CLI-only and must be deleted after first use.
7. Configure the HTTPS API URL in the Android source before building an APK.

## Owner/admin console
The initial admin foundation supports separate owner/admin/moderator roles, owner-only admin account creation/disable, password hashing, CSRF tokens, prepared SQL, basic login throttling, session hardening and audit events. It does **not** yet implement all moderation or app-wide controls (reports, user bans, broadcast notifications, feature toggles). Those need to be built and tested before production.

## Security warning
Do not deploy before implementing authenticated API requests and authorization checks for every conversation/message/media operation. HTTPS is not end-to-end encryption. Keep real `.env`, Firebase service-account JSON, database passwords and signing keys out of GitHub. Calls need a signaling service and STUN/TURN; scalable group calls generally need an SFU such as LiveKit.
