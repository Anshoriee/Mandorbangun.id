<?php
require_once '../includes/admin_header.php';

$id = $_GET['id'] ?? null;
$service = [
    'id' => '',
    'title' => '',
    'description' => '',
    'icon_class' => ''
];
$page_title = "Tambah Layanan Baru";

if ($id) {
    $page_title = "Edit Layanan";
    $sql = "SELECT * FROM services WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $service = mysqli_fetch_assoc($result);
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
        <input type="hidden" name="action" value="save_service">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($service['id']); ?>">

        <div class="form-group">
            <label for="title">Nama Layanan</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($service['title']); ?>"
                required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="4"
                required><?php echo htmlspecialchars($service['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="icon_class">Kelas Ikon Font Awesome</label>
            <input type="text" id="icon_class" name="icon_class"
                value="<?php echo htmlspecialchars($service['icon_class']); ?>"
                placeholder="Contoh: fa-solid fa-building" required>
            <small>Cari nama kelas di <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a>.</small>
        </div>

        <button type="submit" class="btn-primary">Simpan & Publikasikan</button>
        <a href="services.php" class="btn-secondary">Batal</a>
    </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>