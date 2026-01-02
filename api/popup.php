<?php
// api/popup.php - API untuk mengambil data popup ads
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/config.php';

try {
    // Query untuk mengambil popup yang aktif
    $sql = "SELECT * FROM popup WHERE is_active = 1 ORDER BY created_at DESC LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $popup = mysqli_fetch_assoc($result);

        // Tambahkan base URL ke image path
        $popup['image_path'] = BASE_URL . $popup['image_path'];

        echo json_encode([
            'success' => true,
            'data' => $popup
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Tidak ada popup aktif'
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}

mysqli_close($conn);
?>