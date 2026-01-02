<?php
require_once '../includes/config.php';

// VERIFIKASI CSRF TOKEN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        // Token tidak valid, hentikan eksekusi
        $_SESSION['error'] = 'Sesi tidak valid. Silakan coba lagi.';
        // Redirect ke halaman sebelumnya atau halaman utama admin
        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin/');
        exit;
    }
}

// Hapus token setelah digunakan agar tidak bisa dipakai lagi
unset($_SESSION['csrf_token']);

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Menggunakan exit() lebih aman di sini
    http_response_code(403);
    exit('Akses ditolak.');
}

// Helper function untuk upload gambar agar tidak duplikasi kode
function upload_image($file_input, $current_image, $upload_subdir) {
    if (isset($_FILES[$file_input]) && $_FILES[$file_input]['error'] == 0) {
        $upload_dir = '../assets/images/' . $upload_subdir . '/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Gunakan izin yang lebih aman (0755)
        }

        // 1. Validasi Tipe File di Sisi Server dengan Lebih Andal
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($_FILES[$file_input]['tmp_name']);
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!in_array($mime_type, $allowed_types)) {
            $_SESSION['error'] = "Format gambar tidak valid. Hanya JPG, PNG, GIF, WebP yang diizinkan.";
            return null;
        }

        // 2. Buat Nama File Baru yang Aman (Mencegah Path Traversal)
        $file_ext = pathinfo($_FILES[$file_input]['name'], PATHINFO_EXTENSION);
        $safe_filename = bin2hex(random_bytes(16)) . '.' . $file_ext;
        $target_file = $upload_dir . $safe_filename;

        // 3. Pindahkan File
        if (move_uploaded_file($_FILES[$file_input]['tmp_name'], $target_file)) {
            // Hapus gambar lama
            if (!empty($current_image) && file_exists('../' . $current_image)) {
                // Pastikan path tidak keluar dari direktori yang diizinkan
                if (strpos(realpath('../' . $current_image), realpath($upload_dir)) === 0) {
                    unlink('../' . $current_image);
                }
            }
            return 'assets/images/' . $upload_subdir . '/' . $safe_filename;
        } else {
            $_SESSION['error'] = "Gagal memindahkan file yang diupload.";
            return null;
        }
    }
    return $current_image;
}


$action = $_POST['action'] ?? '';

// =============================================
// PROSES UNTUK LAYANAN (SERVICES)
// =============================================
if ($action === 'save_service') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $icon_class = $_POST['icon_class'];

    if (empty($id)) {
        $count_sql = "SELECT COUNT(*) as total FROM services";
        $count_result = mysqli_query($conn, $count_sql);
        $total = mysqli_fetch_assoc($count_result)['total'];
        $order_num = $total + 1;

        $sql = "INSERT INTO services (title, description, icon_class, order_num) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $icon_class, $order_num);
    } else {
        $sql = "UPDATE services SET title = ?, description = ?, icon_class = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $title, $description, $icon_class, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Data layanan berhasil disimpan.";
    } else {
        $_SESSION['error'] = "Gagal menyimpan data layanan: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/services.php");
    exit;
}

if ($action === 'delete_service') {
    $id = $_POST['id'];
    // **FIXED**: Menggunakan prepared statement untuk keamanan
    $sql = "DELETE FROM services WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Data layanan berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus data layanan.";
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/services.php");
    exit;
}

// =============================================
// PROSES UNTUK PORTOFOLIO
// =============================================
if ($action === 'save_portfolio') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $service_id = $_POST['service_id'];
    $current_image = $_POST['current_image'];
    
    $image_path = upload_image('image', $current_image, 'portfolio');
    if ($image_path === null) { // Cek jika upload gagal
        header("Location: " . BASE_URL . "admin/portfolio.php");
        exit;
    }

    if (empty($id)) {
        $count_sql = "SELECT COUNT(*) as total FROM portfolio";
        $count_result = mysqli_query($conn, $count_sql);
        $total = mysqli_fetch_assoc($count_result)['total'];
        $order_num = $total + 1;

        $sql = "INSERT INTO portfolio (title, image_path, service_id, order_num) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $title, $image_path, $service_id, $order_num);
    } else {
        $sql = "UPDATE portfolio SET title = ?, image_path = ?, service_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssii", $title, $image_path, $service_id, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Data portofolio berhasil disimpan.";
    } else {
        $_SESSION['error'] = "Gagal menyimpan data portofolio: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/portfolio.php");
    exit;
}

if ($action === 'delete_portfolio') {
    $id = $_POST['id'];
    
    // Ambil path gambar untuk dihapus dari server
    $sql_get_image = "SELECT image_path FROM portfolio WHERE id = ?";
    $stmt_get_image = mysqli_prepare($conn, $sql_get_image);
    mysqli_stmt_bind_param($stmt_get_image, "i", $id);
    mysqli_stmt_execute($stmt_get_image);
    $result = mysqli_stmt_get_result($stmt_get_image);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_get_image);
    
    // Hapus dari database
    $sql = "DELETE FROM portfolio WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        if ($row && !empty($row['image_path']) && file_exists('../' . $row['image_path'])) {
            unlink('../' . $row['image_path']);
        }
        $_SESSION['message'] = "Data portofolio berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus data portofolio.";
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/portfolio.php");
    exit;
}

// =============================================
// PROSES UNTUK ALUR KERJA
// =============================================
if ($action === 'save_alur_kerja_item') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $current_image = $_POST['current_image'];

    $image_path = upload_image('image', $current_image, 'alur_kerja');
    if ($image_path === null) {
        header("Location: " . BASE_URL . "admin/alur_kerja.php");
        exit;
    }

    if (empty($id)) {
        $count_sql = "SELECT COUNT(*) as total FROM alur_kerja";
        $count_result = mysqli_query($conn, $count_sql);
        $total = mysqli_fetch_assoc($count_result)['total'];
        $order_num = $total + 1;

        $sql = "INSERT INTO alur_kerja (title, image_path, order_num) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $title, $image_path, $order_num);
    } else {
        $sql = "UPDATE alur_kerja SET title = ?, image_path = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $title, $image_path, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Gambar alur kerja berhasil disimpan.";
    } else {
        $_SESSION['error'] = "Gagal menyimpan gambar alur kerja.";
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/alur_kerja.php");
    exit;
}

if ($action === 'delete_alur_kerja_item') {
    $id = $_POST['id'];
    
    // Ambil path gambar untuk dihapus
    $sql_get_image = "SELECT image_path FROM alur_kerja WHERE id = ?";
    $stmt_get_image = mysqli_prepare($conn, $sql_get_image);
    mysqli_stmt_bind_param($stmt_get_image, "i", $id);
    mysqli_stmt_execute($stmt_get_image);
    $result = mysqli_stmt_get_result($stmt_get_image);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_get_image);

    $sql = "DELETE FROM alur_kerja WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        if ($row && file_exists('../' . $row['image_path'])) {
            unlink('../' . $row['image_path']);
        }
        $_SESSION['message'] = "Gambar alur kerja berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus gambar alur kerja.";
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/alur_kerja.php");
    exit;
}

// =============================================
// PROSES UNTUK POP UP (PROMO)
// =============================================
if ($action === 'save_popup') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $link_url = $_POST['link_url'];
    $show_delay = (int) $_POST['show_delay'];
    $show_frequency = $_POST['show_frequency'];
    $is_active = (int) $_POST['is_active'];
    $current_image = $_POST['current_image'];

    $image_path = upload_image('image', $current_image, 'popup');
    if ($image_path === null) {
        header("Location: " . BASE_URL . "admin/popup.php");
        exit;
    }

    if (empty($id)) {
        $sql = "INSERT INTO popup (title, image_path, link_url, show_delay, show_frequency, is_active) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssisi", $title, $image_path, $link_url, $show_delay, $show_frequency, $is_active);
    } else {
        $sql = "UPDATE popup SET title = ?, image_path = ?, link_url = ?, show_delay = ?, show_frequency = ?, is_active = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssisii", $title, $image_path, $link_url, $show_delay, $show_frequency, $is_active, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Popup ads berhasil disimpan.";
    } else {
        $_SESSION['error'] = "Gagal menyimpan popup ads: " . mysqli_error($conn);
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/popup.php");
    exit;
}

if ($action === 'delete_popup') {
    $id = $_POST['id'];
    
    // Ambil path gambar
    $sql_get_image = "SELECT image_path FROM popup WHERE id = ?";
    $stmt_get_image = mysqli_prepare($conn, $sql_get_image);
    mysqli_stmt_bind_param($stmt_get_image, "i", $id);
    mysqli_stmt_execute($stmt_get_image);
    $result = mysqli_stmt_get_result($stmt_get_image);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt_get_image);

    $sql = "DELETE FROM popup WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        if ($row && file_exists('../' . $row['image_path'])) {
            unlink('../' . $row['image_path']);
        }
        $_SESSION['message'] = "Popup ads berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus popup ads.";
    }
    mysqli_stmt_close($stmt);
    header("Location: " . BASE_URL . "admin/popup.php");
    exit;
}

// =============================================
// PROSES UNTUK KATEGORI GALERI
// =============================================
if ($action === 'save_gallery_category') {
    $id = $_POST['id'];
    $title = $_POST['title'];

    if (empty($id)) {
        $count_sql = "SELECT COUNT(*) as total FROM gallery_categories";
        $count_result = mysqli_query($conn, $count_sql);
        $total = mysqli_fetch_assoc($count_result)['total'];
        $order_num = $total + 1;

        $sql = "INSERT INTO gallery_categories (title, order_num) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $title, $order_num);
    } else {
        $sql = "UPDATE gallery_categories SET title = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $title, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Kategori galeri berhasil disimpan.";
    } else {
        $_SESSION['error'] = "Gagal menyimpan kategori galeri.";
    }
    header("Location: " . BASE_URL . "admin/gallery_categories.php");
    exit;
}

if ($action === 'delete_gallery_category') {
    $id = $_POST['id'];
    $sql = "DELETE FROM gallery_categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Kategori galeri dan semua gambarnya berhasil dihapus.";
    } else {
        $_SESSION['error'] = "Gagal menghapus kategori galeri.";
    }
    header("Location: " . BASE_URL . "admin/gallery_categories.php");
    exit;
}

// =============================================
// PROSES UNTUK ITEM GALERI (DENGAN MULTIPLE IMAGES) - VERSI PERBAIKAN
// =============================================
if ($action === 'save_gallery_item_multiple') {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    
    mysqli_begin_transaction($conn);

    try {
        // Step 1: Simpan atau update item galeri utama
        if ($id) { // Jika edit
            $sql = "UPDATE gallery_items SET title = ?, category_id = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $title, $category_id, $id);
            mysqli_stmt_execute($stmt);
            $item_id = $id;
        } else { // Jika baru
            $count_sql = "SELECT COUNT(*) as total FROM gallery_items";
            $count_result = mysqli_query($conn, $count_sql);
            $total = mysqli_fetch_assoc($count_result)['total'];
            $order_num = $total + 1;
            
            $sql = "INSERT INTO gallery_items (title, category_id, order_num, image_path) VALUES (?, ?, ?, '')";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $title, $category_id, $order_num);
            mysqli_stmt_execute($stmt);
            $item_id = mysqli_insert_id($conn);
        }

        // Step 2: Update gambar yang sudah ada
        if (isset($_POST['existing_images'])) {
            foreach ($_POST['existing_images'] as $img_id => $img_data) {
                $update_sql = "UPDATE gallery_images SET image_type = ?, caption = ?, order_num = ? WHERE id = ?";
                $update_stmt = mysqli_prepare($conn, $update_sql);
                mysqli_stmt_bind_param($update_stmt, "ssii", $img_data['type'], $img_data['caption'], $img_data['order'], $img_id);
                mysqli_stmt_execute($update_stmt);
            }
        }

        // Step 3: Hapus gambar yang ditandai
        if (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $delete_id) {
                // Ambil path untuk hapus file fisik
                $get_path_sql = "SELECT image_path FROM gallery_images WHERE id = ?";
                $path_stmt = mysqli_prepare($conn, $get_path_sql);
                mysqli_stmt_bind_param($path_stmt, "i", $delete_id);
                mysqli_stmt_execute($path_stmt);
                $path_result = mysqli_stmt_get_result($path_stmt);
                if ($path_row = mysqli_fetch_assoc($path_result)) {
                    if (file_exists('../' . $path_row['image_path'])) {
                        unlink('../' . $path_row['image_path']);
                    }
                }
                
                // Hapus dari database
                $delete_sql = "DELETE FROM gallery_images WHERE id = ?";
                $delete_stmt = mysqli_prepare($conn, $delete_sql);
                mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);
                mysqli_stmt_execute($delete_stmt);
            }
        }

        // Step 4: Proses unggahan gambar baru
        if (isset($_FILES['new_images_file'])) {
            $upload_dir = '../assets/images/gallery/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            foreach ($_FILES['new_images_file']['name'] as $index => $filename) {
                if ($_FILES['new_images_file']['error'][$index] === UPLOAD_ERR_OK) {
                    $file_tmp = $_FILES['new_images_file']['tmp_name'][$index];
                    
                    // Buat nama file unik
                    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $new_filename = time() . '_' . uniqid() . '.' . $file_ext;
                    $upload_path = $upload_dir . $new_filename;
                    $db_path = 'assets/images/gallery/' . $new_filename;
                    
                    // Pindahkan file
                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        // Ambil data terkait dari array lain
                        $img_type = $_POST['new_images_type'][$index] ?? 'main';
                        $img_caption = $_POST['new_images_caption'][$index] ?? '';
                        $img_order = $_POST['new_images_order'][$index] ?? 99;
                        
                        // Simpan ke database
                        $img_sql = "INSERT INTO gallery_images (gallery_item_id, image_path, image_type, caption, order_num) VALUES (?, ?, ?, ?, ?)";
                        $img_stmt = mysqli_prepare($conn, $img_sql);
                        mysqli_stmt_bind_param($img_stmt, "isssi", $item_id, $db_path, $img_type, $img_caption, $img_order);
                        mysqli_stmt_execute($img_stmt);
                    }
                }
            }
        }

        // Step 5: Update gambar utama di tabel gallery_items
        $main_img_sql = "SELECT image_path FROM gallery_images WHERE gallery_item_id = ? ORDER BY (image_type = 'main') DESC, order_num ASC LIMIT 1";
        $main_stmt = mysqli_prepare($conn, $main_img_sql);
        mysqli_stmt_bind_param($main_stmt, "i", $item_id);
        mysqli_stmt_execute($main_stmt);
        $main_result = mysqli_stmt_get_result($main_stmt);
        $main_row = mysqli_fetch_assoc($main_result);
        
        $main_image_path = $main_row ? $main_row['image_path'] : '';

        $update_main_sql = "UPDATE gallery_items SET image_path = ?, main_image_path = ? WHERE id = ?";
        $update_main_stmt = mysqli_prepare($conn, $update_main_sql);
        mysqli_stmt_bind_param($update_main_stmt, "ssi", $main_image_path, $main_image_path, $item_id);
        mysqli_stmt_execute($update_main_stmt);

        // Jika semua berhasil
        mysqli_commit($conn);
        $_SESSION['message'] = "Proyek galeri berhasil disimpan.";

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
        header("Location: " . BASE_URL . "admin/gallery_form.php" . ($id ? "?id=$id" : ""));
        exit();
    }
    
    header("Location: " . BASE_URL . "admin/gallery.php");
    exit();
}

if ($action === 'delete_gallery_item') {
    $id = $_POST['id'];
    try {
        // Ambil semua path gambar untuk dihapus
        $images_sql = "SELECT image_path FROM gallery_images WHERE gallery_item_id = ?";
        $images_stmt = mysqli_prepare($conn, $images_sql);
        mysqli_stmt_bind_param($images_stmt, "i", $id);
        mysqli_stmt_execute($images_stmt);
        $images_result = mysqli_stmt_get_result($images_stmt);
        
        while ($img_row = mysqli_fetch_assoc($images_result)) {
            if (file_exists('../' . $img_row['image_path'])) {
                unlink('../' . $img_row['image_path']);
            }
        }
        
        // Hapus item utama (akan menghapus gambar terkait karena ON DELETE CASCADE)
        $delete_sql = "DELETE FROM gallery_items WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_sql);
        mysqli_stmt_bind_param($delete_stmt, "i", $id);
        mysqli_stmt_execute($delete_stmt);
        
        $_SESSION['message'] = "Item galeri berhasil dihapus!";
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menghapus item galeri: " . $e->getMessage();
    }
    
    header("Location: " . BASE_URL . "admin/gallery.php");
    exit();
}

// =============================================
// PROSES UNTUK PENGATURAN (SETTINGS)
// =============================================
if ($action === 'save_settings') {
    $settings = $_POST['settings'];
    foreach ($settings as $key => $value) {
        $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $value, $key);
        mysqli_stmt_execute($stmt);
    }
    $_SESSION['message'] = "Pengaturan berhasil diperbarui.";
    header("Location: " . BASE_URL . "admin/settings.php");
    exit;
}

// =============================================
// PROSES UNTUK UBAH PASSWORD ADMIN
// =============================================
if ($action === 'change_password') {
    $admin_id = $_SESSION['id'];
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // 1. Validasi password baru dan konfirmasi
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Password baru dan konfirmasi tidak cocok.";
        header("Location: " . BASE_URL . "admin/settings.php");
        exit;
    }

    // 2. Ambil password saat ini dari database
    $sql = "SELECT password FROM admins WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    // 3. Verifikasi password lama
    if ($admin && password_verify($old_password, $admin['password'])) {
        // 4. Jika password lama benar, hash password baru
        $new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // 5. Update password baru ke database
        $update_sql = "UPDATE admins SET password = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, "si", $new_hashed_password, $admin_id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            $_SESSION['message'] = "Password berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gagal memperbarui password. Silakan coba lagi.";
        }
    } else {
        $_SESSION['error'] = "Password lama yang Anda masukkan salah.";
    }

    header("Location: " . BASE_URL . "admin/settings.php");
    exit;
}
?>