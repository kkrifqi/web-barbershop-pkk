<?php
// ============================================================
//  api/service.php — Handler CRUD Service
//
//  POST { action: 'tambah', nama, kategori, harga, durasi }
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
    'tambah' => tambahService($pdo, $body),
    'hapus'  => hapusService($pdo, $body),
    default  => kirimJSON(false, 'Action tidak dikenali.')
};


// ============================================================
//  TAMBAH SERVICE
// ============================================================
function tambahService(PDO $pdo, array $data): void
{
    $nama     = trim($data['nama']     ?? '');
    $kategori = $data['kategori']      ?? '';
    $harga    = (int) ($data['harga']  ?? 0);
    $durasi   = (int) ($data['durasi'] ?? 0);

    if (!$nama) {
        kirimJSON(false, 'Nama layanan tidak boleh kosong.');
    }
    if (!in_array($kategori, ['Dewasa', 'Anak-anak'])) {
        kirimJSON(false, 'Kategori tidak valid.');
    }
    if ($harga <= 0) {
        kirimJSON(false, 'Harga harus lebih dari 0.');
    }

    $stmt = $pdo->prepare(
        "INSERT INTO services (nama, kategori, harga, durasi)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$nama, $kategori, $harga, $durasi]);

    $idBaru = $pdo->lastInsertId();

    kirimJSON(true, 'Layanan berhasil ditambahkan.', [
        'id'       => (int) $idBaru,
        'nama'     => $nama,
        'kategori' => $kategori,
        'harga'    => $harga,
        'durasi'   => $durasi,
    ]);
}


// ============================================================
//  HAPUS SERVICE
// ============================================================
function hapusService(PDO $pdo, array $data): void
{
    $id = (int) ($data['id'] ?? 0);

    if ($id <= 0) {
        kirimJSON(false, 'ID layanan tidak valid.');
    }

    // Cek service ada
    $cek = $pdo->prepare("SELECT id FROM services WHERE id = ?");
    $cek->execute([$id]);
    if (!$cek->fetch()) {
        kirimJSON(false, 'Layanan tidak ditemukan.');
    }

    // Cek apakah service masih dipakai di booking aktif
    $cekBooking = $pdo->prepare(
        "SELECT COUNT(*) FROM bookings
         WHERE service_id = ? AND status IN ('pending','accepted')"
    );
    $cekBooking->execute([$id]);
    if ((int) $cekBooking->fetchColumn() > 0) {
        kirimJSON(false, 'Layanan masih dipakai di booking aktif, tidak bisa dihapus.');
    }

    $stmt = $pdo->prepare("DELETE FROM services WHERE id = ?");
    $stmt->execute([$id]);

    kirimJSON(true, 'Layanan berhasil dihapus.');
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
