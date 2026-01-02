<?php
// File ini menampilkan pesan sukses atau error setelah suatu aksi di panel admin.

if (isset($_SESSION['message'])) {
    // Menggunakan htmlspecialchars untuk memastikan tidak ada HTML yang rusak di dalam pesan.
    // Ini adalah praktik keamanan yang baik untuk mencegah XSS (Cross-Site Scripting).
    echo '<div class="notification success">' . htmlspecialchars($_SESSION['message']) . '</div>';
    // Hapus pesan setelah ditampilkan agar tidak muncul lagi saat refresh.
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    // Ini sangat penting, terutama untuk pesan error yang mungkin datang dari database,
    // yang bisa mengandung karakter yang akan merusak HTML jika tidak di-escape.
    echo '<div class="notification error">' . htmlspecialchars($_SESSION['error']) . '</div>';
    // Hapus pesan setelah ditampilkan.
    unset($_SESSION['error']);
}
?>
