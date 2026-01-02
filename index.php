<?php
require_once 'includes/config.php';
require_once 'includes/header.php';

// Ambil semua layanan untuk tab dan section layanan
$services_sql = "SELECT * FROM services ORDER BY order_num ASC";
$services_result = mysqli_query($conn, $services_sql);
$services = [];
while ($row = mysqli_fetch_assoc($services_result)) {
    $services[] = $row;
}
// Ambil gambar untuk alur kerja dari tabel 'alur_kerja'
$alur_kerja_sql = "SELECT * FROM alur_kerja ORDER BY order_num ASC";
$alur_kerja_result = mysqli_query($conn, $alur_kerja_sql);
$alur_kerja_items = [];
while($row = mysqli_fetch_assoc($alur_kerja_result)){
    $alur_kerja_items[] = $row;
}

// Ambil semua item portofolio untuk section portofolio utama
$all_portfolio_sql = "SELECT * FROM portfolio ORDER BY order_num ASC";
$all_portfolio_result = mysqli_query($conn, $all_portfolio_sql);
$all_portfolio_items = [];
while($row = mysqli_fetch_assoc($all_portfolio_result)){
    $all_portfolio_items[] = $row;
}

// Ambil data pengaturan untuk kontak
$settings_sql = "SELECT * FROM settings";
$settings_result = mysqli_query($conn, $settings_sql);
$settings = [];
while ($row = mysqli_fetch_assoc($settings_result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<section id="hero" class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Desain & Bangun dengan Kualitas Premium</h1>
            <p>Kami adalah mitra terpercaya Anda dalam mewujudkan properti impian, dari konsep hingga serah terima kunci di Semarang dan sekitarnya.</p>
            <a href="https://drive.google.com/file/d/1UvXdn8dAAwtr6iu0_mNiqSDhvuctwdpv/view?usp=sharing" target="_blank" class="btn">Lihat Katalog Kami</a>
        </div>
    </div>
</section>

<section id="services">
    <div class="container">
        <div class="section-title">
            <h2>Layanan Unggulan Kami</h2>
            <p>Menyediakan solusi konstruksi yang komprehensif untuk memenuhi setiap kebutuhan properti Anda.</p>
        </div>
        <div class="services-grid">
            <?php foreach ($services as $service): ?>
                <div class="service-card">
                    <div class="icon"><i class="<?php echo htmlspecialchars($service['icon_class']); ?>"></i></div>
                    <h3><?php echo htmlspecialchars($service['title']); ?></h3>
                    <p><?php echo htmlspecialchars($service['description']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section id="alur-kerja" class="alur-kerja-section">
    <div class="section-title">
        <h2>Alur Kerja Kami</h2>
    </div>
    <div class="alur-kerja-container">
        <?php foreach ($alur_kerja_items as $item): ?>
            <div class="card">
                <img src="<?php echo BASE_URL . htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <div class="content">
                    <h2><?php echo htmlspecialchars($item['title']); ?></h2>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section id="portfolio">
    <div class="container">
        <div class="section-title">
            <h2>Hasil Karya Kami</h2>
            <p>Beberapa proyek yang telah kami selesaikan dengan bangga.</p>
        </div>
        <div class="portfolio-grid">
            <?php foreach ($all_portfolio_items as $item): ?>
                <div class="portfolio-item">
                    <img src="<?php echo BASE_URL . htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <div class="portfolio-overlay">
                        <h3><?php echo htmlspecialchars($item['title']); ?></h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="gallery.php" class="btn">Lihat Galeri Lengkap</a>
        </div>
        </div>
</section>

<?php
require_once 'includes/footer.php';
?>