<?php
require_once 'config.php';

// Hapus semua session
session_destroy();

// Hapus cookie remember me
setcookie('remember_email', '', time() - 3600, '/');

// Lempar ke halaman login
header('Location: index.php');
exit();
?>