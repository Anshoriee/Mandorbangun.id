</main>
<footer id="footer" class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-widget">
                <h4>Hubungi Kami</h4>
                <address class="contact-info">
                    <p>
                        <a href="https://maps.google.com/?cid=13465624914553737946&g_mp=CiVnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLkdldFBsYWNl" target="_blank">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($settings['contact_address'] ?? 'Alamat belum diatur'); ?>
                        </a>
                    </p>
                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($settings['contact_phone'] ?? 'Telepon belum diatur'); ?></p>
                    <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($settings['contact_email'] ?? 'Email belum diatur'); ?></p>
                </address>
            </div>
            
            <div class="footer-widget <?php echo isset($hide_footer_nav) ? 'invisible-widget' : ''; ?>">
                <h4>Navigasi</h4>
                <ul class="footer-links">
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#services">Layanan</a></li>
                    <li><a href="#portfolio">Portfolio</a></li>
                </ul>
            </div>

            <div class="footer-widget">
                <h4>Media Sosial</h4>
                <div class="social-media">
                    <a href="https://www.instagram.com/mandorbangun.id?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.facebook.com/share/19mAYXhapV/" target="_blank" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://youtube.com/@mandorbangun?feature=shared" target="_blank" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://www.tiktok.com/@mandorbangun.id?is_from_webapp=1&sender_device=pc" target="_blank" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>© <?php echo date("Y"); ?> <?php echo WEBSITE_NAME; ?>. Seluruh Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>

<?php
// Kode PHP untuk WhatsApp FAB
$phone = $settings['contact_phone'] ?? '';
$phone = preg_replace('/[^0-9]/', '', $phone);
if (substr($phone, 0, 1) === '0') {
    $phone = '62' . substr($phone, 1);
} elseif (empty($phone)) {
    $phone = '628152318805';
}
?>
<a href="https://wa.me/<?php echo $phone; ?>?text=Halo%20MinDor,%20saya%20mau%20konsultasi%20dong." target="_blank" class="whatsapp-fab">
    <i class="fa-brands fa-whatsapp"></i>
</a>

<script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const preloader = document.getElementById('page-preloader');
    if (!preloader) return;

    // --- 1. LOGIKA BEBAN AWAL (TANPA JEDA) ---
    // Sembunyikan preloader SEGERA setelah halaman selesai dimuat.
    window.addEventListener('load', function() {
        preloader.classList.remove('visible');
    });
    
    // --- 2. LOGIKA NAVIGASI (TANPA JEDA) ---
    // Tampilkan preloader sesaat sebelum halaman ditinggalkan.
    window.addEventListener('beforeunload', function(e) {
        // Cek apakah event dipicu oleh klik pada tautan internal
        // Ini untuk mencegah preloader muncul saat refresh halaman (F5)
        if (document.activeElement && document.activeElement.tagName === 'A') {
            const href = document.activeElement.getAttribute('href');
            // Hanya aktifkan untuk tautan navigasi, bukan tautan jangkar (#) atau eksternal
            if (href && !href.startsWith('#')) {
                preloader.classList.add('visible');
            }
        }
    });

    // --- 3. LOGIKA UNTUK TOMBOL MAJU/MUNDUR BROWSER ---
    window.addEventListener('pageshow', function(event) {
        // Sembunyikan preloader jika pengguna kembali dari cache browser
        if (event.persisted) {
            preloader.classList.remove('visible');
        }
    });
});
</script>

</body>
</html>