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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VulnApp - Secure Login Portal</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="card">
            <h2 class="card-title">DAMN SECURE WEB APP</h2>
            <p style="text-align: center; font-size: 14px; margin-bottom: 24px;">Please authenticate to access the secure dashboard</p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" id="password" name="password" placeholder="Enter your password" required autocomplete="off">
                </div>
                <button type="submit" class="btn">Authenticate</button>
            </form>

            <?php if (!empty($debugSql)): ?>
                <div class="terminal">
                    <div class="terminal-header">
                        <div class="terminal-dots">
                            <span class="terminal-dot dot-red"></span>
                            <span class="terminal-dot dot-yellow"></span>
                            <span class="terminal-dot dot-green"></span>
                        </div>
                        <span class="terminal-title">Query Debug Console</span>
                    </div>
                    <div><strong>Executed SQL:</strong> <?= htmlspecialchars($debugSql) ?></div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</body>
</html>
