<?php
session_start();

// Unset all session values
$_SESSION = array();

// Expire the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Expire legacy cookies (if present)
setcookie('username', '', time() - 3600, '/');
setcookie('role', '', time() - 3600, '/');

// Destroy session on server
session_destroy();

header('Location: login.php');
exit;
