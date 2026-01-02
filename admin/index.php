<?php
require_once '../includes/admin_header.php';

// Menghitung total layanan
$services_count_sql = "SELECT COUNT(id) as total FROM services";
$services_count_result = mysqli_query($conn, $services_count_sql);
$services_total = mysqli_fetch_assoc($services_count_result)['total'];

// Menghitung total portofolio
$portfolio_count_sql = "SELECT COUNT(id) as total FROM portfolio";
$portfolio_count_result = mysqli_query($conn, $portfolio_count_sql);
$portfolio_total = mysqli_fetch_assoc($portfolio_count_result)['total'];
?>

<div class="admin-content">
    <h2>Dashboard Admin</h2>
    <span class="subtitle">Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon"><i class="fas fa-concierge-bell"></i></div>
            <div class="card-value"><?php echo $services_total; ?></div>
            <div class="card-label">Total Layanan</div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon"><i class="fas fa-briefcase"></i></div>
            <div class="card-value"><?php echo $portfolio_total; ?></div>
            <div class="card-label">Portfolio Items</div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon"><i class="fas fa-users"></i></div>
            <div id="monthly-visitors-count" class="card-value">...</div>
            <div class="card-label">Pengunjung Bulan Ini</div>
        </div>
        <div class="dashboard-card">
            <div class="card-icon"><i class="fas fa-globe"></i></div>
            <div id="online-now-count" class="card-value">...</div>
            <div class="card-label">Pengunjung Saat Ini</div>
        </div>
    </div>

    <div style="margin-top: 30px;">
        <button id="reset-monthly-btn" class="btn-secondary" style="background-color: #dc3545; color: white;">
            <i class="fas fa-undo"></i> Reset Pengunjung Bulan Ini
        </button>
    </div>
</div>

<div id="reset-confirmation-modal" class="modal-overlay">
    <div class="modal-content">
        <h3>Konfirmasi Reset</h3>
        <p>Apakah Anda yakin ingin mereset hitungan pengunjung bulan ini menjadi 0? Tindakan ini tidak dapat diurungkan.
        </p>
        <div class="modal-buttons">
            <button id="confirm-reset-btn" class="btn-delete">Ya, Reset</button>
            <button id="cancel-reset-btn" class="btn-secondary">Batal</button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const monthlyVisitorsEl = document.getElementById('monthly-visitors-count');
        const onlineNowEl = document.getElementById('online-now-count');

        function fetchVisitorData() {
            const apiUrl = '<?php echo BASE_URL; ?>api/visitors.php';

            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    monthlyVisitorsEl.textContent = data.monthly_visitors || 0;
                    onlineNowEl.textContent = data.online_now || 0;
                })
                .catch(error => {
                    console.error('Error:', error.message);
                    monthlyVisitorsEl.textContent = 'N/A';
                    onlineNowEl.textContent = 'N/A';
                });
        }

        // Ambil data saat halaman pertama kali dimuat
        fetchVisitorData();

        // Atur interval untuk memperbarui data setiap 5 detik
        setInterval(fetchVisitorData, 5000);


        // Logika untuk Modal Reset
        const modal = document.getElementById('reset-confirmation-modal');
        const openModalBtn = document.getElementById('reset-monthly-btn');
        const closeModalBtn = document.getElementById('cancel-reset-btn');
        const confirmResetBtn = document.getElementById('confirm-reset-btn');

        // Pastikan tombol ada sebelum menambahkan event listener
        if (openModalBtn) {
            openModalBtn.addEventListener('click', () => modal.classList.add('show'));
        }
        
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => modal.classList.remove('show'));
        }
        
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('show');
                }
            });
        }

        if (confirmResetBtn) {
            confirmResetBtn.addEventListener('click', () => {
                const resetApiUrl = '<?php echo BASE_URL; ?>api/reset_visitors.php';

                fetch(resetApiUrl, { method: 'POST' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Perbarui tampilan secara manual setelah reset berhasil
                            monthlyVisitorsEl.textContent = '0';
                            // Pengunjung online akan di-update pada interval berikutnya
                        } else {
                            alert('Gagal mereset: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Error saat mereset:', error))
                    .finally(() => {
                        modal.classList.remove('show'); // Tutup modal setelah selesai
                    });
            });
        }
    });
</script>

<?php
require_once '../includes/admin_footer.php';
?>