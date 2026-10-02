<?php
require_once 'config.php';

$error = '';
$success = '';

// Kalau form sudah di-submit (metode POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Ambil input & bersihkan dengan htmlspecialchars()
    $nama    = htmlspecialchars(trim($_POST['nama']));
    $email   = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];

    // 2. Validasi: tidak boleh kosong
    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field harus diisi!';
    }
    // 3. Validasi email dengan filter_var()
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    }
    // 4. Validasi password minimal 6 karakter
    elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    }
    // 5. Cek duplikasi email
    elseif (emailSudahAda($FILE_USER, $email)) {
        $error = 'Email sudah terdaftar!';
    }
    // 6. Kalau semua oke, simpan user baru
    else {
        // Hash password dengan password_hash()
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Baca data user yang sudah ada
        $users = bacaUser($FILE_USER);

        // Tambah user baru
        $users[] = [
            'id'       => uniqid(),
            'nama'     => $nama,
            'email'    => $email,
            'password' => $passwordHash,
            'created'  => date('Y-m-d H:i:s')
        ];

        // Simpan ke file JSON
        simpanUser($FILE_USER, $users);

        $success = 'Registrasi berhasil! Silakan login.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register - Tugas 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📝 Daftar Akun Baru</h2>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Nama:</label>
            <input type="text" name="nama" value="<?= $_POST['nama'] ?? '' ?>" required>

            <label>Email:</label>
            <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit">Daftar</button>
        </form>

        <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </div>
</body>
</html>