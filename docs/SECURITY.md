# Security checklist

- [ ] Implement real JWT signature validation or secure server sessions.
- [ ] Require HTTPS.
- [ ] Authorize every conversation read/write against conversation_members.
- [ ] Never trust client-supplied sender_id.
- [ ] Rate-limit login, registration, message send and call initiation.
- [ ] Keep Firebase service account JSON and .env outside the public web root and out of Git.
- [ ] Add password reset, verification, block/report and abuse handling.
- [ ] Validate upload size, MIME type and content; store outside executable web root.
- [ ] Do not claim end-to-end encryption unless implemented and independently reviewed.
- [ ] Test Android permissions, background restrictions, notifications and TURN-only networks.