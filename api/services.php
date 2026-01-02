<?php
// api/services.php - Fixed Services API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/config.php';

try {
    // Query untuk mengambil data layanan dari database
    $sql = "SELECT title, description, icon_class FROM services ORDER BY id";
    $result = mysqli_query($conn, $sql);
    
    $services = array();
    
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $services[] = array(
                "icon" => $row['icon_class'],
                "title" => $row['title'],
                "description" => $row['description']
            );
        }
    } else {
        // Fallback data jika database kosong
        $services = [
            [
                "icon" => "fa-solid fa-building",
                "title" => "Pembangunan Baru",
                "description" => "Membangun rumah, ruko, dan gedung dari nol dengan material berkualitas dan manajemen proyek yang efisien."
            ],
            [
                "icon" => "fa-solid fa-house-chimney-window",
                "title" => "Renovasi & Perbaikan",
                "description" => "Memperbarui dan meningkatkan kualitas bangunan Anda, mulai dari renovasi kecil hingga perombakan total."
            ],
            [
                "icon" => "fa-solid fa-compass-drafting",
                "title" => "Desain Arsitektur",
                "description" => "Menyediakan jasa desain arsitektur dan interior yang fungsional, estetis, dan sesuai dengan gaya hidup Anda."
            ]
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $services]);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}

mysqli_close($conn);
?>