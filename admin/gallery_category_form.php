<?php
require_once '../includes/admin_header.php';

$id = $_GET['id'] ?? null;
$category = [
    'id' => '',
    'title' => ''
];
$page_title = "Tambah Kategori Galeri Baru";

if ($id) {
    $page_title = "Edit Kategori Galeri";
    $sql = "SELECT * FROM gallery_categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $category = mysqli_fetch_assoc($result);
}
?>

<div class="admin-content">
    <h2><?php echo $page_title; ?></h2>
    <?php
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    $csrf_token = $_SESSION['csrf_token'];
    ?>
    <form action="process.php" method="POST" class="styled-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <input type="hidden" name="action" value="save_gallery_category">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($category['id']); ?>">

        <div class="form-group">
            <label for="title">Nama Kategori</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($category['title']); ?>"
                required>
        </div>

        <button type="submit" class="btn-primary">Simpan Kategori</button>
        <a href="gallery_categories.php" class="btn-secondary">Batal</a>
    </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>