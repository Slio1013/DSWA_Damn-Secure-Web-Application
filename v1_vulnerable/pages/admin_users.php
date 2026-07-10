<?php
require_once __DIR__ . '/../includes/db.php';
session_start();

$role = $_SESSION['role'] ?? 'user';

if ($role !== 'admin') {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied - VulnApp</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="login-container">
            <div class="card" style="border-color: var(--accent-red);">
                <h2 class="card-title" style="color: var(--accent-red); background: none; -webkit-text-fill-color: var(--accent-red);">Access Denied</h2>
                <div class="alert alert-danger" style="margin-bottom: 24px; text-align: center; display: block;">
                    Error: Elevated privileges required to access database records.
                </div>
                <a href="../dashboard.php" class="btn btn-secondary">Return to Dashboard</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$users = $pdo->query("SELECT id, username, password, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Database Users Management</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="app-container">
        <div class="card">
            <h2 class="card-title" style="text-align: left; margin-bottom: 8px;">Database Administration</h2>
            <p>SECURED AREA: Passwords are encrypted using Base64 in the database.</p>
            
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Password (Base64)</th>
                            <th>Privilege Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td><?= htmlspecialchars($u['id']) ?></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($u['username']) ?></td>
                                <td><code style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 12px; color: var(--text-muted); font-family: monospace; word-break: break-all;"><?= htmlspecialchars($u['password']) ?></code></td>
                                <td>
                                    <span class="badge <?= $u['role'] === 'admin' ? 'badge-admin' : 'badge-user' ?>">
                                        <?= htmlspecialchars($u['role']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div>
                <a href="../dashboard.php" class="back-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
</html>
