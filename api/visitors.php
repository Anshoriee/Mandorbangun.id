<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Hanya admin yang bisa mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Akses ditolak']);
    exit;
}

// Hapus pengunjung yang tidak aktif lebih dari 15 detik
$timeout_timestamp = date('Y-m-d H:i:s', time() - 15);
$delete_sql = "DELETE FROM visitors WHERE last_activity < ?";
$delete_stmt = mysqli_prepare($conn, $delete_sql);
mysqli_stmt_bind_param($delete_stmt, "s", $timeout_timestamp);
mysqli_stmt_execute($delete_stmt);
mysqli_stmt_close($delete_stmt);

// 1. Hitung pengunjung yang online
$online_sql = "SELECT COUNT(id) as total FROM visitors";
$online_result = mysqli_query($conn, $online_sql);
$online_count = mysqli_fetch_assoc($online_result)['total'];

// 2. Hitung total pengunjung unik bulan ini
$current_month = date('Y-m');
$monthly_sql = "SELECT COUNT(DISTINCT session_id) as total FROM monthly_visits WHERE DATE_FORMAT(visit_date, '%Y-%m') = ?";
$stmt = mysqli_prepare($conn, $monthly_sql);
mysqli_stmt_bind_param($stmt, "s", $current_month);
mysqli_stmt_execute($stmt);
$monthly_result = mysqli_stmt_get_result($stmt);
$monthly_count = mysqli_fetch_assoc($monthly_result)['total'];
mysqli_stmt_close($stmt);

// Kembalikan data dalam format JSON
echo json_encode([
    'online_now' => $online_count,
    'monthly_visitors' => $monthly_count
]);

mysqli_close($conn);
?>