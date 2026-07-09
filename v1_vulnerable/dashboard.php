<?php
/**
 * VULNERABLE: role/username taken straight from client-controlled cookies.
 * An attacker can simply set cookie role=admin in devtools/curl to become admin,
 * with zero server-side verification. Also no check that user is "logged in" at all.
 */
$username = $_COOKIE['username'] ?? 'Guest';
$role     = $_COOKIE['role'] ?? 'user';
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title>
<style>body{font-family:Arial;max-width:600px;margin:60px auto;}
.admin{background:#ffe0e0;padding:10px;} .user{background:#e0f0ff;padding:10px;}</style>
</head>
<body>
<h2>Welcome, <?= $username ?> (role: <?= $role ?>)</h2>

<?php if ($role === 'admin'): ?>
    <div class="admin">
        <h3>Admin Panel</h3>
        <p>Secret admin flag: <strong>FLAG{cookie_trust_is_broken}</strong></p>
        <p><a href="pages/admin_users.php">Manage Users</a></p>
    </div>
<?php else: ?>
    <div class="user">
        <h3>User Area</h3>
        <p><a href="pages/profile.php">My Profile</a></p>
    </div>
<?php endif; ?>

<p><a href="logout.php">Logout</a></p>
</body>
</html>
