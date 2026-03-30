<?php
// ============================================================
//  api/barber.php — Handler CRUD Barber
//
//  POST { action: 'tambah', nama, email, instagram, keahlian, deskripsi, status }
//  POST { action: 'hapus',  id }
//
//  Selalu merespons JSON:
//    { success: true/false, message: '...', data: {...} }
// ============================================================

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/cek_session.php';

header('Content-Type: application/json');

// Guard: hanya admin
if (!$sudahLogin || $roleUser !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan.']);
    exit;
}

$body   = json_decode(file_get_contents('php://input'), true);
$action = $body['action'] ?? '';

match ($action) {
    'tambah' => tambahBarber($pdo, $body),
    'hapus'  => hapusBarber($pdo, $body),
    default  => kirimJSON(false, 'Action tidak dikenali.')
};


// ============================================================
//  TAMBAH BARBER
// ============================================================
function tambahBarber(PDO $pdo, array $data): void
{
    $nama      = trim($data['nama']      ?? '');
    $email     = trim($data['email']     ?? '');
    $instagram = trim($data['instagram'] ?? '');
    $keahlian  = trim($data['keahlian']  ?? '');
    $deskripsi = trim($data['deskripsi'] ?? '');
    $status    = in_array($data['status'] ?? '', ['aktif', 'nonaktif'])
                 ? $data['status']
                 : 'aktif';

    if (!$nama) {
        kirimJSON(false, 'Nama barber tidak boleh kosong.');
    }

    $stmt = $pdo->prepare(
        "INSERT INTO barbers (nama, email, instagram, keahlian, deskripsi, status)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$nama, $email, $instagram, $keahlian, $deskripsi, $status]);

    $idBaru = $pdo->lastInsertId();

    kirimJSON(true, 'Barber berhasil ditambahkan.', [
        'id'        => (int) $idBaru,
        'nama'      => $nama,
        'email'     => $email,
        'instagram' => $instagram,
        'deskripsi' => $deskripsi ?: $keahlian,
        'status'    => $status,
    ]);
}


// ============================================================
//  HAPUS BARBER
// ============================================================
function hapusBarber(PDO $pdo, array $data): void
{
    $id = (int) ($data['id'] ?? 0);

    if ($id <= 0) {
        kirimJSON(false, 'ID barber tidak valid.');
    }

    // Cek barber ada
    $cek = $pdo->prepare("SELECT id FROM barbers WHERE id = ?");
    $cek->execute([$id]);
    if (!$cek->fetch()) {
        kirimJSON(false, 'Barber tidak ditemukan.');
    }

    // Cek apakah barber masih punya booking aktif
    $cekBooking = $pdo->prepare(
        "SELECT COUNT(*) FROM bookings
         WHERE barber_id = ? AND status IN ('pending','accepted')"
    );
    $cekBooking->execute([$id]);
    if ((int) $cekBooking->fetchColumn() > 0) {
        kirimJSON(false, 'Barber masih memiliki booking aktif, tidak bisa dihapus.');
    }

    $stmt = $pdo->prepare("DELETE FROM barbers WHERE id = ?");
    $stmt->execute([$id]);

    kirimJSON(true, 'Barber berhasil dihapus.');
}


// ============================================================
//  Helper
// ============================================================
function kirimJSON(bool $success, string $message, mixed $data = null): never
{
    $resp = ['success' => $success, 'message' => $message];
    if ($data !== null) $resp['data'] = $data;
    echo json_encode($resp);
    exit;
}
