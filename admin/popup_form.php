<?php
require_once '../includes/admin_header.php';

$id = $_GET['id'] ?? null;
$popup = [
    'id' => '',
    'title' => '',
    'image_path' => '',
    'link_url' => '',
    'is_active' => 1,
    'show_delay' => 3000,
    'show_frequency' => 'once_per_session'
];
$page_title = "Tambah Popup Ads Baru";

if ($id) {
    $page_title = "Edit Popup";
    $sql = "SELECT * FROM popup WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $popup = mysqli_fetch_assoc($result);
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
        <input type="hidden" name="action" value="save_popup">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($popup['id']); ?>">
        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($popup['image_path']); ?>">

        <div class="form-group">
            <label for="title">Judul Popup</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($popup['title']); ?>"
                required>
            <small>Judul untuk identifikasi di admin panel</small>
        </div>

        <div class="form-group">
            <label for="image">Gambar Popup</label>
            <input type="file" id="image" name="image" accept="image/*" <?php echo empty($id) ? 'required' : ''; ?>>
            <?php if (!empty($popup['image_path'])): ?>
                <p>Gambar saat ini:</p>
                <img src="<?php echo BASE_URL . htmlspecialchars($popup['image_path']); ?>" alt="Current Image"
                    style="max-width: 300px; margin-top: 10px; border-radius: 8px;">
            <?php endif; ?>
            <small><?php echo empty($id) ? 'Gambar wajib diisi.' : 'Kosongkan jika tidak ingin mengubah gambar.'; ?></small>
        </div>

        <div class="form-group">
            <label for="link_url">Link Tujuan (Opsional)</label>
            <input type="url" id="link_url" name="link_url" value="<?php echo htmlspecialchars($popup['link_url']); ?>"
                placeholder="https://example.com">
            <small>URL yang akan dibuka ketika popup diklik. Kosongkan jika tidak ada link.</small>
        </div>

        <div class="form-group">
            <label for="show_delay">Delay Tampil (milidetik)</label>
            <input type="number" id="show_delay" name="show_delay"
                value="<?php echo htmlspecialchars($popup['show_delay']); ?>" min="0" step="500" required>
            <small>Berapa lama menunggu sebelum popup muncul (dalam milidetik). 1000ms = 1 detik</small>
        </div>

        <div class="form-group">
            <label for="show_frequency">Frekuensi Tampil</label>
            <select name="show_frequency" id="show_frequency" required>
                <option value="always" <?php echo ($popup['show_frequency'] == 'always') ? 'selected' : ''; ?>>Selalu
                    tampil</option>
                <option value="once_per_session" <?php echo ($popup['show_frequency'] == 'once_per_session') ? 'selected' : ''; ?>>Sekali per sesi</option>
                <option value="once_per_day" <?php echo ($popup['show_frequency'] == 'once_per_day') ? 'selected' : ''; ?>>Sekali per hari</option>
            </select>
            <small>
                <strong>Selalu:</strong> Popup muncul setiap kali halaman dimuat<br>
                <strong>Sekali per sesi:</strong> Popup hanya muncul sekali selama browser terbuka<br>
                <strong>Sekali per hari:</strong> Popup hanya muncul sekali per hari
            </small>
        </div>

        <div class="form-group">
            <label for="is_active">Status</label>
            <select name="is_active" id="is_active" required>
                <option value="1" <?php echo ($popup['is_active'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                <option value="0" <?php echo ($popup['is_active'] == 0) ? 'selected' : ''; ?>>Nonaktif</option>
            </select>
            <small>Hanya popup dengan status "Aktif" yang akan ditampilkan di website</small>
        </div>

        <button type="submit" class="btn-primary">Simpan Popup</button>
        <a href="popup.php" class="btn-secondary">Batal</a>
    </form>
</div>

<?php require_once '../includes/admin_footer.php'; ?>