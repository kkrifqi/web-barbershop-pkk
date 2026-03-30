<?php
// ============================================================
//  auth.php — Handler autentikasi (register / login / logout)
//
//  Dipanggil via fetch() dari JS dengan POST:
//    { action: 'register' | 'login' | 'logout', ...data }
//
//  Selalu merespons JSON:
//    { success: true/false, message: '...', redirect: '...' }
// ============================================================

require_once __DIR__ . '/../config/koneksi.php';

// Mulai session (aman: tidak akan duplikat kalau sudah aktif)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    kirimJSON(false, 'Method tidak diizinkan.');
}

// Ambil body JSON dari fetch()
$body   = json_decode(file_get_contents('php://input'), true);
$action = $body['action'] ?? '';

match ($action) {
    'register' => handleRegister($pdo, $body),
    'login'    => handleLogin($pdo, $body),
    'logout'   => handleLogout(),
    default    => kirimJSON(false, 'Action tidak dikenali.')
};


// ============================================================
//  REGISTER
// ============================================================
function handleRegister(PDO $pdo, array $data): void
{
    $nama     = trim($data['nama']     ?? '');
    $email    = trim($data['email']    ?? '');
    $no_hp    = trim($data['no_hp']    ?? '');
    $password = trim($data['password'] ?? '');

    // --- Validasi dasar ---
    if (!$nama || !$email || !$password) {
        kirimJSON(false, 'Nama, email, dan kata sandi wajib diisi.');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        kirimJSON(false, 'Format email tidak valid.');
    }
    if (strlen($password) < 6) {
        kirimJSON(false, 'Kata sandi minimal 6 karakter.');
    }

    // --- Cek email sudah terdaftar ---
    $cek = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $cek->execute([$email]);
    if ($cek->fetch()) {
        kirimJSON(false, 'Email sudah terdaftar. Silakan login.');
    }

    // --- Simpan user baru ---
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare(
        "INSERT INTO users (nama, email, no_hp, password, role)
         VALUES (?, ?, ?, ?, 'user')"
    );
    $stmt->execute([$nama, $email, $no_hp, $hash]);

    kirimJSON(true, 'Pendaftaran berhasil! Silakan login.', '../login-register/login.html');
}


// ============================================================
//  LOGIN
// ============================================================
function handleLogin(PDO $pdo, array $data): void
{
    $email    = trim($data['email']    ?? '');
    $password = trim($data['password'] ?? '');

    if (!$email || !$password) {
        kirimJSON(false, 'Email dan kata sandi wajib diisi.');
    }

    // Ambil user berdasarkan email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Verifikasi password
    if (!$user || !password_verify($password, $user['password'])) {
        kirimJSON(false, 'Email atau kata sandi salah.');
    }

    // --- Simpan data ke session ---
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_nama'] = $user['nama'];
    $_SESSION['user_role'] = $user['role'];

    // Arahkan ke halaman yang sesuai berdasarkan role
    $redirect = $user['role'] === 'admin'
        ? '../admin/admin.php'
        : '../pages/barber.php';       // user biasa balik ke halaman utama

    kirimJSON(true, 'Login berhasil! Selamat datang, ' . $user['nama'] . '.', $redirect);
}


// ============================================================
//  LOGOUT
// ============================================================
function handleLogout(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $p['path'], $p['domain'], $p['secure'], $p['httponly']
        );
    }

    session_destroy();
    kirimJSON(true, 'Logout berhasil.', '../pages/barber.php');
}


// ============================================================
//  Helper: kirim respons JSON lalu hentikan eksekusi
// ============================================================
function kirimJSON(bool $success, string $message, string $redirect = ''): never
{
    header('Content-Type: application/json');
    echo json_encode([
        'success'  => $success,
        'message'  => $message,
        'redirect' => $redirect,
    ]);
    exit;
}
