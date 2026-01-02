<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo WEBSITE_NAME; ?> - Jasa Kontraktor Profesional</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <link rel="icon" href="<?php echo BASE_URL; ?>assets/images/Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <?php
    // Menampilkan stylesheet kustom jika variabelnya ada
    if (isset($custom_stylesheet)) {
        echo $custom_stylesheet;
    }
    ?>
    
    <!-- Menambahkan Session ID dan Base URL untuk JavaScript -->
    <script>
        window.PHP_SESSION_ID = "<?php echo session_id(); ?>";
        window.BASE_URL = "<?php echo BASE_URL; ?>";
    </script>
</head>
<body>
    <body>
    <div id="page-preloader" class="visible">
    <div class="spinner"></div>
    </div>
    <header class="header">
        <div class="container navbar">
            <a href="<?php echo BASE_URL; ?>" class="logo"><img src="<?php echo BASE_URL; ?>assets/images/Logo-Mandorbangun.id.png" alt="Logo"></a>
            <nav class="nav-links" id="nav-links">
                <ul>
                    <li><a href="<?php echo BASE_URL; ?>#hero">HOME</a></li>
                    <li><a href="<?php echo BASE_URL; ?>#services">LAYANAN</a></li>
                    <li><a href="<?php echo BASE_URL; ?>#portfolio">PORTOFOLIO</a></li>
                    <li><a href="<?php echo BASE_URL; ?>#footer">KONTAK</a></li>
                    <li><a href="<?php echo BASE_URL; ?>admin/">ADMIN</a></li>
                </ul>
            </nav>
            <button class="hamburger" id="hamburger">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    <main>
