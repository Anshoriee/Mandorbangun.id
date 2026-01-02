<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
    exit;
}

// Kosongkan tabel monthly_visits untuk mereset hitungan
$sql = "TRUNCATE TABLE monthly_visits";

if (mysqli_query($conn, $sql)) {
    // Juga reset pengunjung online untuk konsistensi
    mysqli_query($conn, "TRUNCATE TABLE visitors");
    echo json_encode(['success' => true, 'message' => 'Data pengunjung bulanan berhasil direset.']);
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(['success' => false, 'message' => 'Gagal mereset data.']);
}

mysqli_close($conn);
?>