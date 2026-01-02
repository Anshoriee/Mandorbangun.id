<?php
require_once '../includes/admin_header.php';

$id = $_GET['id'] ?? null;
$item = [
    'id' => '',
    'title' => '',
    'image_path' => '',
    'service_id' => ''
];
$page_title = "Tambah Proyek Baru";

if ($id) {
    $page_title = "Edit Proyek Portofolio";
    $sql = "SELECT * FROM portfolio WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);
}

// Ambil semua layanan untuk dropdown
$services_sql = "SELECT id, title FROM services ORDER BY order_num ASC";
$services_result = mysqli_query($conn, $services_sql);
?>

<div class="admin-content">
    <h2><?php echo $page_title; ?></h2>
    <?php
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];
    ?>
    <form action="process.php" method="POST" enctype="multipart/form-data" class="styled-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <input type="hidden" name="action" value="save_portfolio">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($item['image_path']); ?>">

        <div class="form-group">
            <label for="title">Judul Proyek</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($item['title']); ?>" required>
        </div>

        <div class="form-group">
            <label for="service_id">Kategori Layanan</label>
            <select name="service_id" id="service_id" required>
                <option value="">-- Pilih Kategori --</option>
                <?php
                while ($service = mysqli_fetch_assoc($services_result)) {
                    $selected = ($service['id'] == $item['service_id']) ? 'selected' : '';
                    echo "<option value='" . $service['id'] . "' " . $selected . ">" . htmlspecialchars($service['title']) . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="image">Gambar Proyek</label>
            <input type="file" id="image" name="image" accept="image/*">
            <?php if (!empty($item['image_path'])): ?>
                <p>Gambar saat ini:</p>
                <img src="<?php echo BASE_URL . htmlspecialchars($item['image_path']); ?>" alt="Current Image"
                    style="max-width: 200px; margin-top: 10px;">
            <?php endif; ?>
            <small>Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>

        <button type="submit" class="btn-primary">Simpan & Publikasikan</button>
        <a href="portfolio.php" class="btn-secondary">Batal</a>
    </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>