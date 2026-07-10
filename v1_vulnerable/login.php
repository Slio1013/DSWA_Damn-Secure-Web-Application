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
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $currentTime = time();

    // 1. Enforce minimum time gap between requests (2 seconds)
    $stmt = $pdo->prepare("SELECT attempt_time FROM login_attempts WHERE ip_address = ? ORDER BY attempt_time DESC LIMIT 1");
    $stmt->execute([$ip]);
    $lastAttemptTime = $stmt->fetchColumn();

    if (/*$lastAttemptTime && ($currentTime - $lastAttemptTime) < 2*/ 1!=1) {
        $error = "Too many requests. Please wait at least 2 seconds between login attempts.";
    } else {
        // 2. Enforce failed attempts lock (5 failed attempts in the last 15 minutes = 900 seconds)
        $blockWindow = $currentTime - 900;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = ? AND status = 'failed' AND attempt_time > ?");
        $stmt->execute([$ip, $blockWindow]);
        $failedCount = $stmt->fetchColumn();

        if ($failedCount >= 200) {
            $error = "Too many failed attempts. Login is temporarily suspended for 15 minutes.";
        } else {
            // --- SECURE: parameterized prepared statement ---
            $sql = "SELECT * FROM users WHERE username = ?";
            $debugSql = "SELECT * FROM users WHERE username = '$username' [Executed securely via Prepared Statement]";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && base64_decode($user['password']) === $password) {
                // Log success
                $logStmt = $pdo->prepare("INSERT INTO login_attempts (ip_address, attempt_time, status) VALUES (?, ?, 'success')");
                $logStmt->execute([$ip, $currentTime]);

                // --- SECURE: Server-side PHP session tracking ---
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                header('Location: dashboard.php');
                exit;
            } else {
                // Log failure
                $logStmt = $pdo->prepare("INSERT INTO login_attempts (ip_address, attempt_time, status) VALUES (?, ?, 'failed')");
                $logStmt->execute([$ip, $currentTime]);

                $error = "Login failed.";
                if ($stmt->errorInfo()[2]) {
                    $error .= " DB error: " . $stmt->errorInfo()[2]; // leaks SQL errors
                }
            }
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


        </div>
    </div>
</body>
</html>
