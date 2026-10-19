# Firebase and calling setup (Android)

This repository is a starter, not a finished chat app. The Android package ID is `com.example.messenger`. Firebase is currently used for Firebase Cloud Messaging (FCM) push notifications; messages and user data are intended to be handled by the PHP + MySQL backend. Voice/video media is not implemented yet.

## 1. Create the Firebase project

1. Open https://console.firebase.google.com/ and choose **Create a project**.
2. Enter a project name, for example `Chating-app`. Google Analytics is optional for this starter.
3. In Project settings, choose **Add app > Android**.
4. Enter Android package name exactly: `com.example.messenger`. The nickname is optional. SHA-1 is not needed for basic FCM token delivery, but may be required if you later add Google Sign-In or other services.
5. Download `google-services.json` and place it locally at `android-app/app/google-services.json`.
6. Do not commit that file unless you have deliberately reviewed your repository's secret/config policy. This repository's `.gitignore` should exclude local Firebase config; if it does not, add `android-app/app/google-services.json`.
7. In Firebase Console > Project settings > Cloud Messaging, check that Cloud Messaging is available for the project.

## 2. Firebase services to enable

- **Firebase Cloud Messaging (FCM): REQUIRED** for push notifications and incoming-call notification alerts.
- **Firebase Authentication: OPTIONAL with the current architecture.** The starter is designed around a PHP API, but authentication is not yet fully implemented. Choose one authority for identity: either implement secure PHP/JWT authentication or deliberately migrate to Firebase Authentication and verify Firebase ID tokens on the PHP server. Do not trust a user ID sent by the Android client.
- **Cloud Firestore / Realtime Database: NOT required** for the planned PHP + MySQL design. Add one only if you intentionally move chat messages/signaling state to Firebase and configure strict security rules.
- **Cloud Storage: OPTIONAL** for profile pictures, images, and audio/video attachments. If using it, enable authentication and restrictive Storage Rules; do not make user uploads public by default.

## 3. Configure the PHP backend

1. Host PHP 8+ and MySQL on an HTTPS server. Localhost on your phone is not the same as your computer's localhost; use a reachable test server or your computer's LAN IP for development.
2. Import `database/schema.sql` into MySQL.
3. Copy `backend-php/.env.example` to `backend-php/.env` on the server and set real database values and a long random `JWT_SECRET`.
4. In Firebase Console > Project settings > Service accounts, generate a service-account private key only if the backend needs it for FCM. Store the JSON **outside the public web root**, set `FIREBASE_CREDENTIALS` to its server path, and never commit or send it in chat.
5. Run `composer install` in `backend-php/`.
6. Set the HTTPS API base URL in `android-app/app/src/main/java/com/example/messenger/ApiConfig.java`.
7. Before public deployment, implement and test real authentication, authorization, rate limiting, and conversation membership checks. The existing registration/send-message endpoints are not a complete production chat backend.

## 4. Voice, video and group calls

Firebase does not carry live audio/video. WebRTC carries the media, while your server coordinates who calls whom (signaling).

Recommended practical setup:
- **1-to-1 audio/video:** Android WebRTC library + authenticated signaling endpoints or a real-time signaling channel + STUN/TURN.
- **Group calls:** use an SFU service such as LiveKit (managed cloud or self-hosted). A peer-to-peer mesh becomes inefficient as the group grows.
- **Incoming call alerts:** FCM, with server-side checks so only the intended recipient is notified.
- **Call records:** PHP/MySQL can store caller, participants, timestamps and call status. Never put service-account credentials or permanent provider secrets in the APK.

If using LiveKit Cloud, create a LiveKit project separately, obtain the server URL and API credentials, and keep the API secret only on the backend. Add a PHP endpoint that authenticates the logged-in user and issues short-lived room access tokens. The Android app should receive only its short-lived token. For a self-hosted WebRTC deployment, configure a TURN server (for example coturn) with authenticated, time-limited credentials.

## 5. Android permissions and tests

The manifest already declares Internet, camera, microphone, and notification permissions. Android 13+ still requires requesting notification permission at runtime. Camera and microphone permissions must also be requested at runtime before a call. Test on at least two real Android devices, including background/locked-screen notification delivery and mobile data/Wi-Fi networks.

## Do not share these secrets

Never send or commit:
- `google-services.json` if your project policy treats it as private configuration (it is not a server private key, but still review exposure).
- Firebase service-account JSON/private key.
- LiveKit API secret, TURN shared secret, database password, JWT secret, production `.env`.

See also [SECURITY.md](SECURITY.md) and [WEBRTC.md](WEBRTC.md).
