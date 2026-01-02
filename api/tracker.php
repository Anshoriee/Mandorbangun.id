<?php
require_once '../includes/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$session_id = session_id();
$visit_date = date('Y-m-d');

// Hapus pengunjung yang tidak aktif lebih dari 15 detik
$timeout = date('Y-m-d H:i:s', time() - (15));
$delete_sql = "DELETE FROM visitors WHERE last_activity < '$timeout'";
mysqli_query($conn, $delete_sql);

// Perbarui atau tambahkan pengunjung saat ini (UPSERT)
$upsert_sql = "INSERT INTO visitors (session_id, last_activity) VALUES (?, NOW()) ON DUPLICATE KEY UPDATE last_activity = NOW()";
$stmt = mysqli_prepare($conn, $upsert_sql);
mysqli_stmt_bind_param($stmt, "s", $session_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

// Catat kunjungan unik harian untuk statistik bulanan (INSERT IGNORE)
$log_visit_sql = "INSERT IGNORE INTO monthly_visits (session_id, visit_date) VALUES (?, ?)";
$log_stmt = mysqli_prepare($conn, $log_visit_sql);
mysqli_stmt_bind_param($log_stmt, "ss", $session_id, $visit_date);
mysqli_stmt_execute($log_stmt);
mysqli_stmt_close($log_stmt);

echo json_encode(['status' => 'success']);
mysqli_close($conn);
?>