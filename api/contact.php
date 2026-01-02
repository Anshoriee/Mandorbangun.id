<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

// Ini akan menangani pengiriman form kontak
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Format email tidak valid.']);
        exit;
    }

    // Logika untuk mengirim email atau menyimpan ke database
    // Contoh:
    // mail('admin@mandorbangun.id', 'Pesan Baru dari Website', "Dari: $name\nEmail: $email\n\nPesan:\n$message");
    
    echo json_encode(['success' => true, 'message' => 'Pesan Anda telah berhasil dikirim!']);

} else {
    echo json_encode(['success' => false, 'message' => 'Metode permintaan tidak valid.']);
}
?>
