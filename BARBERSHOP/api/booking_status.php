<?php
// ============================================================
//  api/booking_status.php
//  POST → update status booking (hanya admin)
//  Body: { booking_id: int, status: string }
// ============================================================

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/cek_session.php';

header('Content-Type: application/json');

// Hanya admin
if (!$sudahLogin || $roleUser !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan.']);
    exit;
}

$body      = json_decode(file_get_contents('php://input'), true);
$bookingId = (int) ($body['booking_id'] ?? 0);
$status    = trim($body['status'] ?? '');

// Whitelist status yang valid
$allowed = ['pending', 'accepted', 'completed', 'canceled'];
if (!$bookingId || !in_array($status, $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid.']);
    exit;
}

$stmt = $pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->execute([$status, $bookingId]);

echo json_encode(['success' => true, 'message' => 'Status berhasil diupdate.']);
