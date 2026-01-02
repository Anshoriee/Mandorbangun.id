<?php
require_once '../includes/config.php';

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: " . BASE_URL . "admin/");
    exit;
}

$error_message = '';

// Cek jika ada status dari redirect (misal: karena inaktivitas)
if (isset($_GET['status']) && $_GET['status'] === 'inactive') {
    $error_message = 'Anda telah logout otomatis karena tidak ada aktivitas.';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ambil data admin dari database
    $sql = "SELECT id, username, password FROM admins WHERE username = ?";
    
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "s", $username);
        
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                if(mysqli_stmt_fetch($stmt)){
                    // Verifikasi password
                    if(password_verify($password, $hashed_password)){
                        // Regenerasi session ID untuk keamanan
                        session_regenerate_id(true);
                        
                        $_SESSION['loggedin'] = true;
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['last_activity'] = time(); // Atur waktu aktivitas awal
                        
                        header("Location: " . BASE_URL . "admin/");
                        exit;
                    } else {
                        $error_message = 'Password yang Anda masukkan salah.';
                    }
                }
            } else {
                $error_message = 'Username tidak ditemukan.';
            }
        } else {
            $error_message = 'Oops! Terjadi kesalahan. Silakan coba lagi nanti.';
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - <?php echo WEBSITE_NAME; ?></title>
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css?v=<?php echo time(); ?>">
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/Logo.png">
</head>
<body class="admin-login-page">
    <div class="login-form">
        <div class="login-header">
            <!-- Logo ditambahkan di sini -->
            <img src="<?php echo BASE_URL; ?>assets/images/Logo.png" alt="Logo" class="login-logo">
            <h2>Admin Login</h2>
            <p>Silakan masuk untuk mengelola website.</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="notification error" style="text-align: left;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="login.php" method="post" class="styled-form" style="padding: 0; border: none; box-shadow: none;">
            <div class="form-group" style="text-align: left;">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group" style="text-align: left;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-primary" style="width: 100%;">Login</button>
        </form>
        <a href="<?php echo BASE_URL; ?>" class="back-to-site">Kembali ke Website Utama</a>
    </div>
</body>
</html>
