<?php
// ============================================================
//  api/gallery.php — Handler Upload & Hapus Foto Gallery
//
//  POST multipart/form-data:
//    action=upload, foto[]=<file>, foto[]=<file>, ...
//
//  POST application/json:
//    { action: 'hapus', id: 1 }
//
//  Selalu merespons JSON:
//    { success: true/false, message: '...', data: [...] }
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

// Tentukan action — bisa dari form-data atau JSON body
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isJson      = str_contains($contentType, 'application/json');

if ($isJson) {
    $body   = json_decode(file_get_contents('php://input'), true);
    $action = $body['action'] ?? '';
} else {
    $body   = $_POST;
    $action = $_POST['action'] ?? '';
}

match ($action) {
    'upload' => uploadFoto($pdo),
    'hapus'  => hapusFoto($pdo, $body),
    default  => kirimJSON(false, 'Action tidak dikenali.')
};


// ============================================================
//  UPLOAD FOTO
// ============================================================
function uploadFoto(PDO $pdo): void
{
    // Pastikan folder uploads/gallery ada
    $uploadDir = __DIR__ . '/../uploads/gallery/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Validasi ada file yang dikirim
    if (empty($_FILES['foto']['name'][0])) {
        kirimJSON(false, 'Tidak ada file yang dikirim.');
    }

    $allowed     = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    $maxSize     = 5 * 1024 * 1024; // 5 MB
    $berhasil    = [];
    $gagal       = [];

    $jumlahFile = count($_FILES['foto']['name']);

    for ($i = 0; $i < $jumlahFile; $i++) {
        $namaAsli = $_FILES['foto']['name'][$i];
        $tmpPath  = $_FILES['foto']['tmp_name'][$i];
        $mimeType = $_FILES['foto']['type'][$i];
        $ukuran   = $_FILES['foto']['size'][$i];
        $error    = $_FILES['foto']['error'][$i];

        // Skip kalau ada error upload
        if ($error !== UPLOAD_ERR_OK) {
            $gagal[] = $namaAsli . ' (error upload)';
            continue;
        }

        // Validasi tipe file (gunakan finfo, bukan $_FILES['type'] yang bisa dipalsukan)
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeReal = finfo_file($finfo, $tmpPath);
        finfo_close($finfo);

        if (!in_array($mimeReal, $allowed)) {
            $gagal[] = $namaAsli . ' (tipe file tidak diizinkan)';
            continue;
        }

        // Validasi ukuran
        if ($ukuran > $maxSize) {
            $gagal[] = $namaAsli . ' (ukuran melebihi 5MB)';
            continue;
        }

        // Generate nama file acak: timestamp + random + ekstensi asli
        $ekstensi    = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));
        $namaFile    = bin2hex(random_bytes(8)) . '_' . time() . '.' . $ekstensi;
        $pathServer  = $uploadDir . $namaFile;
        $pathDB      = 'uploads/gallery/' . $namaFile;

        if (!move_uploaded_file($tmpPath, $pathServer)) {
            $gagal[] = $namaAsli . ' (gagal disimpan)';
            continue;
        }

        // Simpan ke DB
        $stmt = $pdo->prepare("INSERT INTO gallery (foto) VALUES (?)");
        $stmt->execute([$pathDB]);

        $berhasil[] = [
            'id'   => (int) $pdo->lastInsertId(),
            'foto' => $pathDB,
            'nama' => $namaAsli,
        ];
    }

    if (empty($berhasil)) {
        kirimJSON(false, 'Semua file gagal diupload: ' . implode(', ', $gagal));
    }

    $pesan = count($berhasil) . ' foto berhasil diupload.';
    if (!empty($gagal)) {
        $pesan .= ' Gagal: ' . implode(', ', $gagal);
    }

    kirimJSON(true, $pesan, $berhasil);
}


// ============================================================
//  HAPUS FOTO
// ============================================================
function hapusFoto(PDO $pdo, array $data): void
{
    $id = (int) ($data['id'] ?? 0);

    if ($id <= 0) {
        kirimJSON(false, 'ID foto tidak valid.');
    }

    // Ambil path file dari DB
    $stmt = $pdo->prepare("SELECT foto FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $row  = $stmt->fetch();

    if (!$row) {
        kirimJSON(false, 'Foto tidak ditemukan.');
    }

    // Hapus file fisik dari server
    $pathServer = __DIR__ . '/../' . $row['foto'];
    if (file_exists($pathServer)) {
        unlink($pathServer);
    }

    // Hapus dari DB
    $del = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
    $del->execute([$id]);

    kirimJSON(true, 'Foto berhasil dihapus.');
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
