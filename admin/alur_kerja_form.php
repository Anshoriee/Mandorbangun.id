<?php
require_once '../includes/admin_header.php';

$id = $_GET['id'] ?? null;
$item = [
    'id' => '',
    'title' => '',
    'image_path' => ''
];
$page_title = "Tambah Gambar Alur Kerja";

if ($id) {
    $page_title = "Edit Gambar Alur Kerja";
    $sql = "SELECT * FROM alur_kerja WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);
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
    <form action="process.php" method="POST" enctype="multipart/form-data" class="styled-form">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <input type="hidden" name="action" value="save_alur_kerja_item">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($item['image_path']); ?>">

        <div class="form-group">
            <label for="title">Judul Gambar (Opsional)</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($item['title']); ?>">
        </div>

        <div class="form-group">
            <label for="image">File Gambar</label>
            <input type="file" id="image" name="image" accept="image/*" <?php echo empty($id) ? 'required' : ''; ?>>
            <?php if (!empty($item['image_path'])): ?>
                <p>Gambar saat ini:</p>
                <img src="<?php echo BASE_URL . htmlspecialchars($item['image_path']); ?>" alt="Current Image"
                    style="max-width: 200px; margin-top: 10px;">
            <?php endif; ?>
            <small><?php echo empty($id) ? 'Gambar wajib diisi.' : 'Kosongkan jika tidak ingin mengubah gambar.'; ?></small>
        </div>

        <button type="submit" class="btn-primary">Simpan Gambar</button>
        <a href="alur_kerja.php" class="btn-secondary">Batal</a>
    </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>