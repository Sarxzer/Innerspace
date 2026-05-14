Security audit TODOs

High

- [x] Fix remember-me revoke flow: hash cookie token before DB revoke, and ensure logout actually invalidates the stored hash.
- [x] Enforce system privacy: block access to non-public systems unless owner, and avoid listing private systems to anonymous users.
- [x] Escape "Now fronting" names on public system page to prevent stored XSS.

Medium

- [x] Harden canonical URL rendering: sanitize/escape host and path, or use a configured base URL.
- [ ] Make logout a POST with CSRF token to avoid cross-site logouts.
- [ ] Add baseline security headers in nginx (HSTS, CSP, X-Content-Type-Options, Referrer-Policy, frame-ancestors).

Low

- [ ] Avoid logging CSRF tokens in debug alerts and do not redirect to unsanitized HTTP_REFERER.
- [x] Reduce error/exception detail sent to Discord in production (redact secrets/paths).
- [ ] Strengthen login throttling beyond session-only (per IP/user or shared store).

Done

- [x] Add CSRF protection for all state-changing POST handlers and forms (settings, manage/system creation, etc.).
- [x] Enforce ownership/authorization checks on manage routes (e.g., system edit) to prevent IDOR.
- [x] Escape breadcrumb names from database values to prevent stored XSS.
- [x] Regenerate session ID on login and after 2FA to prevent session fixation.
- [x] Harden session cookie settings (secure, httponly, samesite) before session_start.
- [x] Harden remember-me cookies (SameSite) and store only hashed tokens in DB.
- [x] Fix 2FA lockout bug: clear pending_2fa_user and block after max attempts.
- [x] Add login rate limiting / throttling to reduce brute-force risk.
- [x] Lock down upload handler: require auth, remove test user increment, add size limits, validate image safely.
