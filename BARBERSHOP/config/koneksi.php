<?php
// ============================================================
//  koneksi.php — Koneksi ke database MySQL
//  Sertakan file ini di setiap halaman PHP yang butuh database:
//    require_once __DIR__ . '/koneksi.php';
// ============================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // ganti sesuai user MySQL kamu
define('DB_PASS', '');              // ganti sesuai password MySQL kamu
define('DB_NAME', 'db_barbershop');
define('DB_CHARSET', 'utf8mb4');

// DSN (Data Source Name) untuk PDO
$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // lempar exception kalau ada error
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // hasil query dalam bentuk array asosiatif
    PDO::ATTR_EMULATE_PREPARES   => false,                    // pakai prepared statement beneran
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // Jangan tampilkan pesan error detail di production!
    // Ganti dengan halaman error yang proper kalau sudah live.
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal: ' . $e->getMessage()
    ]));
}
