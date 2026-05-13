Security audit TODOs

- ~~Add CSRF protection for all state-changing POST handlers and forms (settings, manage/system creation, etc.).~~
- ~~Enforce ownership/authorization checks on manage routes (e.g., system edit) to prevent IDOR.~~
- ~~Escape breadcrumb names from database values to prevent stored XSS.~~
- ~~Regenerate session ID on login and after 2FA to prevent session fixation.~~
- ~~Harden session cookie settings (secure, httponly, samesite) before session_start.~~
- ~~Harden remember-me cookies (SameSite) and store only hashed tokens in DB.~~
- ~~Fix 2FA lockout bug: clear pending_2fa_user and block after max attempts.~~
- ~~Add login rate limiting / throttling to reduce brute-force risk.~~
- ~~Lock down upload handler: require auth, remove test user increment, add size limits, validate image safely.~~
