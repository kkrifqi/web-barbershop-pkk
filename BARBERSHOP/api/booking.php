<?php
// ============================================================
//  api/booking.php
//
//  GET  ?data=services  → ambil semua layanan dari DB
//  GET  ?data=barbers   → ambil semua barber aktif dari DB
//  POST action=submit   → simpan booking baru ke DB
// ============================================================

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/cek_session.php';

header('Content-Type: application/json');


// ── GET ──────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $data = $_GET['data'] ?? '';

    // Layanan
    if ($data === 'services') {
        $stmt = $pdo->query("SELECT * FROM services ORDER BY kategori, harga ASC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;
    }

    // Barber aktif
    if ($data === 'barbers') {
        $stmt = $pdo->query("SELECT id, nama, foto, instagram FROM barbers WHERE status = 'aktif' ORDER BY id ASC");
        echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Parameter tidak dikenali.']);
    exit;
}


// ── POST ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Harus login
    if (!$sudahLogin) {
        echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu.', 'redirect' => '../login-register/login.html']);
        exit;
    }

    $body = json_decode(file_get_contents('php://input'), true);
    $action = $body['action'] ?? '';

    if ($action !== 'submit') {
        echo json_encode(['success' => false, 'message' => 'Action tidak dikenali.']);
        exit;
    }

    // Ambil & validasi input
    $service_id = (int) ($body['service_id'] ?? 0);
    $barber_id  = (int) ($body['barber_id']  ?? 0);
    $tanggal    = trim($body['tanggal'] ?? '');
    $jam        = trim($body['jam']     ?? '');

    if (!$service_id || !$barber_id || !$tanggal || !$jam) {
        echo json_encode(['success' => false, 'message' => 'Data booking tidak lengkap.']);
        exit;
    }

    // Validasi format tanggal & jam
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
        echo json_encode(['success' => false, 'message' => 'Format tanggal tidak valid.']);
        exit;
    }
    if (!preg_match('/^\d{2}:\d{2}$/', $jam)) {
        echo json_encode(['success' => false, 'message' => 'Format jam tidak valid.']);
        exit;
    }

    // Cek apakah slot sudah terisi (barber + tanggal + jam sama)
    $cek = $pdo->prepare(
        "SELECT id FROM bookings
         WHERE barber_id = ? AND tanggal = ? AND jam = ?
         AND status NOT IN ('canceled')"
    );
    $cek->execute([$barber_id, $tanggal, $jam]);
    if ($cek->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Slot waktu ini sudah terisi. Pilih jam lain.']);
        exit;
    }

    // Simpan booking
    $stmt = $pdo->prepare(
        "INSERT INTO bookings (user_id, barber_id, service_id, tanggal, jam, status)
         VALUES (?, ?, ?, ?, ?, 'pending')"
    );
    $stmt->execute([$userID, $barber_id, $service_id, $tanggal, $jam]);
    $bookingId = $pdo->lastInsertId();

    echo json_encode([
        'success'    => true,
        'message'    => 'Booking berhasil!',
        'booking_id' => $bookingId,
    ]);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan.']);
