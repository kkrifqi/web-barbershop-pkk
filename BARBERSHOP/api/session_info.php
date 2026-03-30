<?php
// ============================================================
//  session_info.php — Kembalikan status session sebagai JSON
//
//  Dipanggil oleh barber.js via fetch('/api/session_info.php')
//  untuk mengetahui apakah user sedang login atau tidak.
//
//  Respons JSON:
//    { sudahLogin: false }
//    { sudahLogin: true, nama: '...', role: '...', id: 1 }
// ============================================================

require_once __DIR__ . '/../config/cek_session.php';

header('Content-Type: application/json');

if ($sudahLogin) {
    echo json_encode([
        'sudahLogin' => true,
        'nama'       => $namaUser,
        'role'       => $roleUser,
        'id'         => $userID,
    ]);
} else {
    echo json_encode([
        'sudahLogin' => false,
    ]);
}
