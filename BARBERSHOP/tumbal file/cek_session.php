<?php
// ============================================================
//  cek_session.php — Helper pengecekan status login
//
//  Sertakan di halaman PHP manapun yang perlu tahu status user:
//    require_once __DIR__ . '/../api/cek_session.php';
//
//  Setelah di-include, variabel berikut tersedia:
//    $sudahLogin  (bool)   — apakah user sedang login
//    $namaUser    (string) — nama user, kosong kalau belum login
//    $roleUser    (string) — 'admin' | 'user' | ''
//    $userID      (int)    — id user, 0 kalau belum login
//
//  Fungsi tersedia:
//    guardAdmin()  — redirect ke login kalau bukan admin
//    guardLogin()  — redirect ke login kalau belum login
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$sudahLogin = isset($_SESSION['user_id']);
$namaUser   = $sudahLogin ? $_SESSION['user_nama'] : '';
$roleUser   = $sudahLogin ? $_SESSION['user_role']  : '';
$userID     = $sudahLogin ? (int) $_SESSION['user_id'] : 0;

// ============================================================
//  Guard: Hanya untuk Admin
//  Pakai di setiap halaman .php di folder /admin/
// ============================================================
function guardAdmin(): void
{
    global $sudahLogin, $roleUser;

    if (!$sudahLogin || $roleUser !== 'admin') {
        header('Location: ../login-register/login.html');
        exit;
    }
}

// ============================================================
//  Guard: Hanya untuk User yang Sudah Login
//  Pakai di halaman booking, profil, dll.
// ============================================================
function guardLogin(): void
{
    global $sudahLogin;

    if (!$sudahLogin) {
        // Simpan URL tujuan biar setelah login bisa redirect kembali
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /login-register/login.html');
        exit;
    }
}
