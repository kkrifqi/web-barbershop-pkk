<?php
// ============================================================
//  api/settings.php — Handler untuk fitur Settings
//
//  GET  → ambil semua settings dari DB (untuk halaman user)
//  POST → simpan semua settings ke DB (dari admin panel)
//         action: 'simpan'
// ============================================================

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/cek_session.php';

header('Content-Type: application/json');

// ============================================================
//  GET — Ambil semua settings (publik, tidak perlu login)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Pragma: no-cache');
    
    $stmt = $pdo->query("SELECT kunci, nilai FROM settings");
    $rows = $stmt->fetchAll();

    // Ubah array of rows → object { kunci: nilai }
    $settings = [];
    foreach ($rows as $row) {
        $settings[$row['kunci']] = $row['nilai'];
    }

    echo json_encode(['success' => true, 'data' => $settings]);
    exit;
}


// ============================================================
//  POST — Simpan settings (hanya admin)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Guard: hanya admin yang boleh simpan
    if (!$sudahLogin || $roleUser !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    }

    $body   = json_decode(file_get_contents('php://input'), true);
    $action = $body['action'] ?? '';

    if ($action !== 'simpan') {
        echo json_encode(['success' => false, 'message' => 'Action tidak dikenali.']);
        exit;
    }

    // Daftar kunci yang boleh diupdate (whitelist)
    $allowed = [
        'nama_barbershop', 'tagline', 'alamat', 'telepon', 'email',
        'about_p1', 'about_p2', 'about_p3',
        'jam_senin', 'jam_selasa', 'jam_rabu', 'jam_kamis',
        'jam_jumat', 'jam_sabtu', 'jam_minggu',
        'instagram', 'facebook', 'tiktok',
    ];

    // Pakai INSERT ... ON DUPLICATE KEY UPDATE supaya satu query per kunci
    $stmt = $pdo->prepare(
        "INSERT INTO settings (kunci, nilai)
         VALUES (?, ?)
         ON DUPLICATE KEY UPDATE nilai = VALUES(nilai)"
    );

    foreach ($allowed as $kunci) {
        if (isset($body[$kunci])) {
            $stmt->execute([$kunci, trim($body[$kunci])]);
        }
    }

    echo json_encode(['success' => true, 'message' => 'Pengaturan berhasil disimpan.']);
    exit;
}

// Method lain tidak diizinkan
http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan.']);
