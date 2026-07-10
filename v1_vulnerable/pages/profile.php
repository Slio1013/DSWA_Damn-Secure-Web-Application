<?php
session_start();
$username = $_SESSION['username'] ?? 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - VulnApp</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="card" style="text-align: center;">
            <div class="avatar" style="width: 72px; height: 72px; font-size: 28px; font-weight: 700; margin: 0 auto 20px auto;">
                <?= strtoupper(substr($username, 0, 1)) ?>
            </div>
            
            <h2 class="card-title" style="margin-bottom: 8px;">Profile: <?= htmlspecialchars($username) ?></h2>
            <p style="margin-bottom: 24px;">Standard user profile details. This page is populated based on cookies.</p>
            
            <div style="background: rgba(255, 255, 255, 0.02); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; margin-bottom: 24px; text-align: left;">
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">USERNAME</div>
                <div style="font-weight: 500; margin-bottom: 12px;"><?= htmlspecialchars($username) ?></div>
                
                <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 4px;">USER ROLE</div>
                <div><span class="badge badge-user">user</span></div>
            </div>

            <a href="../dashboard.php" class="back-link" style="justify-content: center; margin-top: 0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</body>
</html>
