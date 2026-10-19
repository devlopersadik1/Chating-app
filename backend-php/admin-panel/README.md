# Talviro Chat Admin Panel foundation

Includes separate owner/admin/moderator accounts, owner-only admin provisioning, password hashing, CSRF checks, prepared SQL, login throttling (in login.php), session hardening, security headers and audit logs.

## cPanel setup
1. Back up MySQL.
2. Import database/schema.sql, then database/migrations/001_admin_control_panel.sql in phpMyAdmin.
3. Set DB_HOST, DB_NAME, DB_USER, DB_PASS and ADMIN_AUDIT_HASH_KEY in the hosting environment. The audit key must be a random secret of 32+ characters. Never commit production secrets.
4. Keep this directory outside public_html where possible. If web-accessible, enforce HTTPS and restrict it with cPanel Directory Privacy/WAF/IP allow-list.
5. Use cPanel Terminal/SSH to run: php setup-owner.php owner@example.com "Owner Name". Enter a unique strong password. If no terminal exists, do not expose the setup script over the web; ask hosting support for a safe CLI run.
6. Delete setup-owner.php after the first owner account is created.
7. Visit login.php using HTTPS.

This is a foundation, not a finished moderation console. App API authentication, conversation membership, message moderation, reports, user bans, broadcast notifications, and setting toggles must be implemented and tested before production. Review reverse-proxy HTTPS handling with hosting support. Use backups and a staging environment.
