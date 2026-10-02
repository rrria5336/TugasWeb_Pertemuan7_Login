<?php
require_once 'config.php';

cekLogin();

$error = '';
$success = '';
$userId = $_SESSION['user_id'];

// Ambil data user saat ini
$users = bacaUser($FILE_USER);
$currentUser = null;
foreach ($users as $user) {
    if ($user['id'] === $userId) {
        $currentUser = $user;
        break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaBaru  = htmlspecialchars(trim($_POST['nama']));
    $emailBaru = htmlspecialchars(trim($_POST['email']));
    $passBaru  = $_POST['password_baru'];

    if (empty($namaBaru) || empty($emailBaru)) {
        $error = 'Nama dan email harus diisi!';
    }
    elseif (!filter_var($emailBaru, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    }
    elseif (emailSudahAda($FILE_USER, $emailBaru) && $emailBaru !== $currentUser['email']) {
        $error = 'Email sudah digunakan user lain!';
    }
    else {
        foreach ($users as &$user) {
            if ($user['id'] === $userId) {
                $user['nama']  = $namaBaru;
                $user['email'] = $emailBaru;

                if (!empty($passBaru)) {
                    if (strlen($passBaru) < 6) {
                        $error = 'Password minimal 6 karakter!';
                        break;
                    }
                    $user['password'] = password_hash($passBaru, PASSWORD_DEFAULT);
                }
                break;
            }
        }

        if (empty($error)) {
            simpanUser($FILE_USER, $users);

            $_SESSION['user_nama']  = $namaBaru;
            $_SESSION['user_email'] = $emailBaru;

            $success = 'Profil berhasil diperbarui!';
            $currentUser['nama']  = $namaBaru;
            $currentUser['email'] = $emailBaru;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil - Tugas 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>✏️ Edit Profil</h2>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?= $currentUser['nama'] ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= $currentUser['email'] ?>" required>

            <label>Password Baru (kosongkan jika tidak ingin ganti):</label>
            <input type="password" name="password_baru">

            <button type="submit">Simpan Perubahan</button>
        </form>

        <p><a href="dashboard.php">← Kembali ke Dashboard</a></p>
    </div>
</body>
</html>