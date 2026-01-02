<?php
require_once '../includes/config.php';

// File ini dipanggil oleh navigator.sendBeacon() saat user menutup tab
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Ambil session ID dari POST body yang dikirim oleh sendBeacon
$session_id = $_POST['session_id'] ?? null;

if (!empty($session_id)) {
    // Hapus session pengunjung ini dari tabel online
    $delete_sql = "DELETE FROM visitors WHERE session_id = ?";
    $stmt = mysqli_prepare($conn, $delete_sql);
    mysqli_stmt_bind_param($stmt, "s", $session_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

// Beri respons kosong karena ini adalah beacon
http_response_code(204);
?>