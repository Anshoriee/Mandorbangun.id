<?php
require_once '../includes/admin_header.php';

// Ambil semua data pengaturan
$settings_sql = "SELECT * FROM settings";
$settings_result = mysqli_query($conn, $settings_sql);
$settings = [];
while ($row = mysqli_fetch_assoc($settings_result)) {
    $settings[$row['setting_key']] = $row['setting_value'];
}
?>

<div class="admin-content">
    <h2>Pengaturan Website</h2>
    <p>Ubah informasi kontak, media sosial, dan pengaturan umum lainnya.</p>

    <?php include '../includes/admin_notifications.php'; ?>

    <form action="process.php" method="POST" class="styled-form">
        <input type="hidden" name="action" value="save_settings">

        <h3>Informasi Kontak</h3>
        <div class="form-group">
            <label for="contact_email">Email Kontak</label>
            <input type="email" id="contact_email" name="settings[contact_email]" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="contact_phone">Nomor Telepon</label>
            <input type="text" id="contact_phone" name="settings[contact_phone]" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
        </div>
        <div class="form-group">
            <label for="contact_address">Alamat</label>
            <textarea id="contact_address" name="settings[contact_address]" rows="3"><?php echo htmlspecialchars($settings['contact_address'] ?? ''); ?></textarea>
        </div>
        
        <button type="submit" class="btn-primary">Simpan Pengaturan Kontak</button>
        <button type="button" id="open-password-modal-btn" class="btn-secondary" style="margin-left: 10px;">Ubah Password Admin</button>
    </form>
</div>

<div id="password-change-modal" class="modal-overlay password-modal">
    <div class="modal-content">
        <form action="process.php" method="POST">
            <input type="hidden" name="action" value="change_password">
            
            <div class="modal-header">
                <div class="icon-wrapper">
                    <i class="fas fa-key"></i>
                </div>
                <h3>Ubah Password Admin</h3>
            </div>
            
            <div class="modal-body">
                <div class="form-group">
                    <label for="old_password">Password Lama</label>
                    <input type="password" id="old_password" name="old_password" required>
                </div>
                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password Baru</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" id="cancel-password-change-btn" class="btn-secondary">Batal</button>
                <button type="submit" class="btn-primary">Simpan Password</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('password-change-modal');
    const openModalBtn = document.getElementById('open-password-modal-btn');
    const closeModalBtn = document.getElementById('cancel-password-change-btn');

    openModalBtn.addEventListener('click', () => {
        modal.classList.add('show');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.remove('show');
    });

    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('show');
        }
    });
});
</script>

<?php require_once '../includes/admin_footer.php'; ?>