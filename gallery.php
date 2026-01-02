<?php
include 'includes/config.php';
// Ambil data pengaturan untuk kontak
$settings_sql = "SELECT * FROM settings";
$settings_result = mysqli_query($conn, $settings_sql);
$settings = [];
while ($row = mysqli_fetch_assoc($settings_result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}

// Menambahkan variabel untuk stylesheet kustom di header
$custom_stylesheet = '<link rel="stylesheet" href="' . BASE_URL . 'assets/css/gallery.css">';

include 'includes/header.php';

// 1. Mengambil data kategori untuk tombol filter
$categories_sql = "SELECT id, title as name, order_num FROM gallery_categories ORDER BY order_num ASC";
$categories_result = $conn->query($categories_sql);

// 2. Mengambil semua item galeri beserta semua gambar DAN CAPTION-nya
$projects_sql = "
    SELECT 
        gi.id, 
        gi.title, 
        gc.title as category_name,
        (SELECT GROUP_CONCAT(
            CONCAT_WS('###', g_img.image_path, IFNULL(g_img.caption, '')) 
            SEPARATOR '||'
         ) 
         FROM gallery_images g_img 
         WHERE g_img.gallery_item_id = gi.id 
         ORDER BY (g_img.image_type = 'main') DESC, g_img.order_num ASC) as all_images_data
    FROM gallery_items gi
    JOIN gallery_categories gc ON gi.category_id = gc.id
    ORDER BY gi.order_num ASC, gi.id DESC
";
$projects_result = $conn->query($projects_sql);

// 3. Menyiapkan data untuk JavaScript
$projects_for_js = [];
if ($projects_result && $projects_result->num_rows > 0) {
    while($row = $projects_result->fetch_assoc()) {
        $category_slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $row['category_name']));
        
        $images_data = [];
        if (!empty($row['all_images_data'])) {
            $images_pairs = explode('||', $row['all_images_data']);
            foreach ($images_pairs as $pair) {
                list($path, $caption) = array_pad(explode('###', $pair, 2), 2, '');
                $images_data[] = [
                    'path' => $path,
                    'caption' => htmlspecialchars($caption)
                ];
            }
        }
        
        $projects_for_js[] = [
            'id' => 'p' . $row['id'],
            'title' => htmlspecialchars($row['title']),
            'category' => $category_slug,
            'description' => htmlspecialchars($row['title']),
            'images' => $images_data
        ];
    }
}
?>

<div class="gallery-page-container">
    <div class="container">
        <div class="section-title">
            <h2>Portofolio Karya Kami</h2>
            <p>Setiap proyek adalah cerita. Temukan inspirasi dari karya desain dan konstruksi yang telah kami wujudkan.</p>
        </div>

        <div id="filter-container" class="gallery-filters">
            </div>

        <div id="gallery" class="portfolio-gallery-grid">
            </div>
    </div>
</div>

<div id="offcanvas-container" class="pointer-events-none">
    <div id="offcanvas-overlay"></div>
    <div id="offcanvas-panel">
        <div class="panel-header">
            <h2 id="panel-title"></h2>
            <button id="close-panel-btn">&times;</button>
        </div>
        <div class="panel-body">
            <div class="main-image-wrapper">
                <img id="panel-main-image" src="" alt="Gambar utama proyek">
            </div>
            
            <div id="panel-thumbnails" class="thumbnails-container">
                </div>

            <div class="description-wrapper">
                <p id="panel-description"></p>
            </div>
        </div>
    </div>
</div>

<script>
    // Memastikan variabel ada sebelum digunakan
    const projectsData = <?php echo json_encode($projects_for_js, JSON_UNESCAPED_SLASHES); ?>;
    const categoriesData = <?php
        $categories_for_js = [];
        if ($categories_result && $categories_result->num_rows > 0) {
            mysqli_data_seek($categories_result, 0);
            while($category = $categories_result->fetch_assoc()) {
                $categories_for_js[] = $category;
            }
        }
        echo json_encode($categories_for_js);
    ?>;
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/Flip.min.js"></script>

<script src="<?php echo BASE_URL; ?>assets/js/gallery.js"></script>

<?php 
// Menambahkan variabel agar footer tidak menampilkan menu navigasi tambahan
$hide_footer_nav = true;
include 'includes/footer.php'; 
?>