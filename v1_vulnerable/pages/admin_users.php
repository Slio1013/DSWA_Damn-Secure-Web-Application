<?php
require_once __DIR__ . '/../includes/db.php';
// VULNERABLE: "access control" is just reading a client-supplied cookie
$role = $_COOKIE['role'] ?? 'user';
if ($role !== 'admin') {
    echo "Access denied.";
    exit;
}
$users = $pdo->query("SELECT id, username, password, role FROM users")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html><head><title>Admin - Users</title></head>
<body>
<h2>All Users (plaintext passwords visible - vulnerable!)</h2>
<table border="1" cellpadding="6">
<tr><th>ID</th><th>Username</th><th>Password</th><th>Role</th></tr>
<?php foreach ($users as $u): ?>
<tr><td><?= $u['id'] ?></td><td><?= $u['username'] ?></td><td><?= $u['password'] ?></td><td><?= $u['role'] ?></td></tr>
<?php endforeach; ?>
</table>
<p><a href="../dashboard.php">Back</a></p>
</body></html>
