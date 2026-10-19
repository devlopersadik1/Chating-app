# Release security checklist

- [ ] Run `composer audit` and update vulnerable dependencies.
- [ ] APP_DEBUG=false; APP_ENV=production; generate APP_KEY; enforce HTTPS.
- [ ] Web document root points only to Laravel public/; protect .env, vendor, storage, backups and logs.
- [ ] Sanctum token expiration/revocation policy defined; logout revokes current token.
- [ ] Configure trusted proxies correctly; never trust arbitrary forwarded headers.
- [ ] Configure throttles and monitoring for registration, login, search, send message and uploads.
- [ ] Confirm authorization for every read/write/media download and admin action.
- [ ] Configure backups, restore drill, log rotation and retention.
- [ ] Add privacy policy, account deletion, reporting and abuse escalation.
- [ ] Implement and test OTP via a real SMS provider before advertising phone login.
- [ ] Validate uploads by MIME, size, file signature and malware scanning; store outside executable web root.
- [ ] FCM server credentials are server-only; Android clients never receive private service account keys.
- [ ] Add FCM delivery, WebRTC signaling, TURN and SFU for scalable group calls.
- [ ] Do not claim E2EE until key management and a reviewed protocol are implemented.
- [ ] Run automated tests and staging end-to-end tests; this starter has not been production certified.
