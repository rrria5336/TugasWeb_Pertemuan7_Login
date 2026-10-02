<?php
// config.php
// Mulai session di setiap halaman
session_start();

// Nama file JSON untuk simpan data user
$FILE_USER = 'users.json';

// ============================================
// FUNGSI 1: Baca semua user dari file JSON
// ============================================
function bacaUser($file) {
    // Kalau file belum ada, bikin array kosong
    if (!file_exists($file)) {
        return [];
    }
    // Baca isi file, ubah dari JSON ke array PHP
    $data = file_get_contents($file);
    return json_decode($data, true) ?? [];
}

// ============================================
// FUNGSI 2: Simpan array user ke file JSON
// ============================================
function simpanUser($file, $users) {
    // Ubah array PHP jadi JSON, lalu tulis ke file
    file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
}

// ============================================
// FUNGSI 3: Cek apakah email sudah terdaftar
// ============================================
function emailSudahAda($file, $email) {
    $users = bacaUser($file);
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return true; // Ketemu! Email sudah ada
        }
    }
    return false; // Belum ada
}

// ============================================
// FUNGSI 4: Cek apakah user sudah login
// ============================================
function cekLogin() {
    // Kalau session 'user_id' tidak ada, berarti belum login
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php'); // Lempar ke halaman login
        exit();
    }
}
?>