<?php
/**
 * ====================================================================
 *  VULNERABLE LOGIN PAGE (v1) -- FOR EDUCATIONAL / LAB USE ONLY
 * ====================================================================
 * INTENTIONAL VULNERABILITIES (as requested for this exercise):
 *   1. SQL Injection - username/password concatenated directly into SQL
 *   2. No input validation/sanitization of any kind
 *   3. No CAPTCHA / no rate limiting / no brute-force protection
 *   4. "Session handling" is just a plaintext cookie the client fully
 *      controls (role and username are trusted from client input)
 *   5. Verbose error messages reveal SQL errors to the user
 *   6. Passwords stored & compared in plaintext
 * DO NOT deploy this file publicly. It exists solely so we can attack
 * it in a controlled lab and then fix it in v2_secure/.
 * ====================================================================
 */
require_once __DIR__ . '/includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // --- VULNERABLE: raw string concatenation into SQL query ---
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

    // Show the raw query on screen -- intentionally bad, for the lab
    $debugSql = $sql;

    $result = $pdo->query($sql);
    $user = $result ? $result->fetch(PDO::FETCH_ASSOC) : false;

    if ($user) {
        // --- VULNERABLE: no real session, just a client-trusted cookie ---
        setcookie('username', $user['username'], time() + 3600, '/');
        setcookie('role', $user['role'], time() + 3600, '/');
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Login failed.";
        if ($pdo->errorInfo()[2]) {
            $error .= " DB error: " . $pdo->errorInfo()[2]; // leaks SQL errors
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>VulnApp Login</title>
<style>body{font-family:Arial;max-width:420px;margin:60px auto;}
input{width:100%;padding:8px;margin:6px 0;} .err{color:red;} .dbg{background:#eee;padding:8px;font-size:12px;word-break:break-all;}</style>
</head>
<body>
<h2>VulnApp - Login</h2>
<form method="post">
    <label>Username</label>
    <input type="text" name="username">
    <label>Password</label>
    <input type="text" name="password">
    <button type="submit">Login</button>
</form>
<?php if ($error): ?><p class="err"><?= $error ?></p><?php endif; ?>
<?php if (!empty($debugSql)): ?>
    <p><strong>Debug (query executed):</strong></p>
    <div class="dbg"><?= $debugSql ?></div>
<?php endif; ?>
<p><small>Sample creds: admin/SuperSecret123, john/password1</small></p>
</body>
</html>
