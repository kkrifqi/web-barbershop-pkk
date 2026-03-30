<?php
// ============================================================
//  api/bookings_list.php
//  GET → ambil semua booking dari DB (hanya admin)
//  Response JSON: { success, data: [...] }
// ============================================================

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/cek_session.php';

header('Content-Type: application/json');

// Hanya admin
if (!$sudahLogin || $roleUser !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

// JOIN ke tabel users, barbers, services
$stmt = $pdo->query("
    SELECT
        b.id,
        b.tanggal,
        b.jam,
        b.status,
        u.nama   AS nama_user,
        br.nama  AS nama_barber,
        s.nama   AS nama_service,
        s.harga  AS harga_service
    FROM bookings b
    JOIN users    u  ON b.user_id    = u.id
    JOIN barbers  br ON b.barber_id  = br.id
    JOIN services s  ON b.service_id = s.id
    ORDER BY b.tanggal DESC, b.jam DESC
");

echo json_encode([
    'success' => true,
    'data'    => $stmt->fetchAll()
]);
