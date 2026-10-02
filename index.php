<?php
require_once 'config.php';

$error = '';

// Kalau sudah login, langsung ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email    = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi!';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    }
    else {
        $users = bacaUser($FILE_USER);
        $found = false;

        foreach ($users as $user) {
            if ($user['email'] === $email && password_verify($password, $user['password'])) {
                $found = true;

                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_nama']  = $user['nama'];
                $_SESSION['user_email'] = $user['email'];

                // BONUS: Remember Me dengan cookie
                if ($remember) {
                    setcookie('remember_email', $email, time() + (86400 * 30), '/');
                } else {
                    setcookie('remember_email', '', time() - 3600, '/');
                }

                header('Location: dashboard.php');
                exit();
            }
        }

        if (!$found) {
            $error = 'Email atau password salah!';
        }
    }
}

$rememberEmail = $_COOKIE['remember_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Tugas 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🔐 Login</h2>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Email:</label>
            <input type="email" name="email" value="<?= $rememberEmail ?>" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <label class="checkbox-label">
                <input type="checkbox" name="remember" <?= $rememberEmail ? 'checked' : '' ?>>
                Ingat saya (Remember Me)
            </label>

            <button type="submit">Login</button>
        </form>

        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>