<?php
// views/modules/auth/logout.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Hapus semua session vars
$_SESSION = [];

// Hancurkan session cookie jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Redirect ke login dengan pesan sederhana (opsional: gunakan GET param)
header('Location: index.php?page=login&msg=loggedout');
exit;
