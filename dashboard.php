<?php
require_once 'config.php';

// Cek apakah user sudah login
cekLogin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tugas 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🎉 Selamat Datang, <?= htmlspecialchars($_SESSION['user_nama']) ?>!</h2>
        <p>Email kamu: <?= htmlspecialchars($_SESSION['user_email']) ?></p>
        <p>Kamu berhasil login ke dashboard.</p>

        <div class="menu">
            <a href="edit_profile.php" class="btn">✏️ Edit Profil</a>
            <a href="logout.php" class="btn danger"> Logout</a>
        </div>
    </div>
</body>
</html>