<?php
$username = $_COOKIE['username'] ?? 'Guest';
?>
<!DOCTYPE html>
<html><head><title>My Profile</title></head>
<body>
<h2>Profile: <?= $username ?></h2>
<p>This is a sample normal-user page.</p>
<p><a href="../dashboard.php">Back</a></p>
</body></html>
