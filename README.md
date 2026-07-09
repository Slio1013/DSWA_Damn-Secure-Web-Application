# VulnApp — Intentionally Vulnerable PHP Login App (Lab Use Only)

## What this is
A minimal PHP + DB login app with two roles (admin, normal user) and a couple
of sample pages, built **on purpose** with the vulnerabilities you asked for:
- No CAPTCHA / no brute-force protection
- No real session handling (role/username trusted from a plain client cookie)
- No input validation
- SQL Injection (raw string concatenation into the query)
- Plaintext password storage

Full attack + defense walkthrough is in `VulnApp_Attack_and_Defense_Guide.docx`.

## Running it locally

This ships with SQLite as the DB engine (so it runs anywhere with zero setup).
The PHP/PDO code is the same style you'd use for MySQL.

```bash
cd v1_vulnerable
php -S 127.0.0.1:8001
```

Then open http://127.0.0.1:8001/login.php in your browser.

Sample accounts (seeded automatically on first run, in `data/vulnapp.sqlite`):
| username | password       | role  |
|----------|----------------|-------|
| admin    | SuperSecret123 | admin |
| john     | password1      | user  |
| mary     | letmein        | user  |

### To use real MySQL instead
In `includes/db.php`, replace the SQLite connection line with:
```php
$pdo = new PDO('mysql:host=localhost;dbname=vulnapp', 'root', '');
```
and create a matching `users` table (see the `CREATE TABLE` in that file for
the exact columns).

## Folder structure
```
v1_vulnerable/
  login.php              <- vulnerable login form + handler
  dashboard.php           <- role-based landing page (trusts client cookie!)
  logout.php
  includes/db.php         <- DB connection + seed data
  pages/admin_users.php   <- "admin-only" page (checked via cookie only)
  pages/profile.php       <- sample normal-user page
```

## Resetting the database
Just delete `v1_vulnerable/data/vulnapp.sqlite` and reload the login page —
it reseeds automatically.
