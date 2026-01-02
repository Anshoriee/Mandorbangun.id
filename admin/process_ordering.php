<?php
require_once '../includes/config.php';
header('Content-Type: application/json');

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Akses ditolak']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['type']) || !isset($data['order'])) {
    echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
    exit;
}

$table_name = '';
if ($data['type'] === 'portfolio') {
    $table_name = 'portfolio';
} elseif ($data['type'] === 'services') {
    $table_name = 'services';
} elseif ($data['type'] === 'alur_kerja') {
    $table_name = 'alur_kerja';
} 
// TAMBAHKAN KONDISI BARU DI SINI
elseif ($data['type'] === 'gallery_items') {
    $table_name = 'gallery_items';
} elseif ($data['type'] === 'gallery_categories') {
    $table_name = 'gallery_categories';
} 
// AKHIR DARI KONDISI BARU
else {
    echo json_encode(['success' => false, 'message' => 'Tipe tidak dikenal']);
    exit;
}

mysqli_begin_transaction($conn);

try {
    $sql = "UPDATE `$table_name` SET `order_num` = ? WHERE `id` = ?";
    $stmt = mysqli_prepare($conn, $sql);

    foreach ($data['order'] as $item) {
        $position = $item['position'];
        $id = $item['id'];
        mysqli_stmt_bind_param($stmt, "ii", $position, $id);
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception(mysqli_stmt_error($stmt));
        }
    }
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()]);
}

mysqli_close($conn);
?>
