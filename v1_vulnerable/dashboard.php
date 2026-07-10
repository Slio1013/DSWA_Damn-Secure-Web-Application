<?php
session_start();

// Redirect to login page if user is not authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$role     = $_SESSION['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VulnApp - Control Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="app-container">
        <div class="card">
            <!-- Header section with username, role badge, and logout -->
            <div class="dashboard-header">
                <div class="user-profile-badge">
                    <div class="avatar"><?= strtoupper(substr($username, 0, 1)) ?></div>
                    <div>
                        <div style="font-weight: 600; font-size: 16px;"><?= htmlspecialchars($username) ?></div>
                        <span class="badge <?= $role === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                            <?= htmlspecialchars($role) ?>
                        </span>
                    </div>
                </div>
                <div>
                    <a href="logout.php" class="logout-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Content Area based on User Role -->
            <?php if ($role === 'admin'): ?>
                <div class="panel panel-admin">
                    <div class="panel-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent-red);"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                        Admin Control Panel
                    </div>
                    <p>Elevated privileges detected. Cookies are trusted implicitly by the server.</p>
                    
                    <div class="flag-box">
                        FLAG{cookie_trust_is_broken}
                    </div>
                    
                    <div style="margin-top: 20px;">
                        <a href="pages/admin_users.php" class="btn">Manage Database Users</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="panel panel-user">
                    <div class="panel-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--accent-blue);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        User Workspace
                    </div>
                    <p>Welcome to your user dashboard. Your role is verified using local storage cookies.</p>
                    
                    <div style="margin-top: 20px;">
                        <a href="pages/profile.php" class="btn btn-secondary">View My Profile</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
