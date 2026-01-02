<?php

// Kontrol Tampilan Error
// Set ke 'production' saat website sudah online
define('ENVIRONMENT', 'development'); // atau 'production'

if (ENVIRONMENT === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// includes/config.php - Fixed Configuration
// Konfigurasi Database
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'mandorbangun.id'); 

// Pengaturan Website
define('WEBSITE_NAME', 'Mandorbangun.id');
define('BASE_URL', 'http://localhost/mandorbangun.id/'); // Sesuaikan dengan struktur folder Anda

// Pengaturan batas waktu logout otomatis (dalam detik)
define('INACTIVE_TIMEOUT', 600); // 1 detik untuk logout sesegera mungkin

// PENGATURAN KEAMANAN SESSION TINGKAT LANJUT
ini_set('session.cookie_httponly', 1); // Mencegah akses cookie via JavaScript
ini_set('session.use_only_cookies', 1); // Hanya gunakan cookie untuk sesi
ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); // Hanya kirim cookie via HTTPS
ini_set('session.cookie_samesite', 'Strict'); // Mencegah serangan CSRF

// Memulai session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Membuat koneksi ke database dengan error handling yang lebih baik
try {
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    
    // Set charset untuk menghindari masalah encoding
    mysqli_set_charset($conn, "utf8mb4");
    
    // Cek koneksi
    if($conn === false){
        throw new Exception("Koneksi database gagal: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    die("ERROR: " . $e->getMessage());
}

// Fungsi helper untuk sanitasi input
function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, trim($data));
}

// Fungsi untuk mengecek login admin
function is_admin_logged_in() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}
?>
