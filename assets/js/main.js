class PopupAds {
    constructor() {
        this.popupData = null;
        this.init();
    }

    async init() {
        try {
            const response = await fetch('api/popup.php');
            const data = await response.json();

            if (data.success && data.data) {
                this.popupData = data.data;
                this.checkAndShowPopup();
            }
        } catch (error) {
            console.log('Popup ads tidak tersedia:', error);
        }
    }

    checkAndShowPopup() {
        if (!this.popupData) return;

        const { show_frequency, show_delay } = this.popupData;
        const today = new Date().toDateString();

        let shouldShow = false;

        switch (show_frequency) {
            case 'always':
                shouldShow = true;
                break;

            case 'once_per_session':
                if (!sessionStorage.getItem('popup_shown')) {
                    shouldShow = true;
                    sessionStorage.setItem('popup_shown', 'true');
                }
                break;

            case 'once_per_day':
                const lastShown = localStorage.getItem('popup_last_shown');
                if (!lastShown || lastShown !== today) {
                    shouldShow = true;
                    localStorage.setItem('popup_last_shown', today);
                }
                break;
        }

        if (shouldShow) {
            setTimeout(() => {
                this.showPopup();
            }, show_delay);
        }
    }

    showPopup() {
        if (!this.popupData) return;

        const popupHTML = `
            <div id="popup-overlay" class="popup-overlay">
                <div class="popup-container">
                    <button class="popup-close" onclick="popupAds.closePopup()">&times;</button>
                    ${this.popupData.link_url ?
                `<a href="${this.popupData.link_url}" target="_blank" class="popup-link" onclick="popupAds.closePopup()">` :
                '<div class="popup-link">'
            }
                        <img src="${this.popupData.image_path}" alt="${this.popupData.title}" class="popup-image">
                    ${this.popupData.link_url ? '</a>' : '</div>'}
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', popupHTML);

        const overlay = document.getElementById('popup-overlay');
        requestAnimationFrame(() => {
            overlay.classList.add('show');
        });

        this.addEventListeners();
    }

    addEventListeners() {
        const overlay = document.getElementById('popup-overlay');

        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                this.closePopup();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closePopup();
            }
        });

        document.body.style.overflow = 'hidden';
    }

    closePopup() {
        const overlay = document.getElementById('popup-overlay');
        if (overlay) {
            overlay.classList.remove('show');

            setTimeout(() => {
                overlay.remove();
                document.body.style.overflow = '';
            }, 300);
        }
    }
}

// Inisialisasi utama saat DOM selesai dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Logika untuk Pop-up Iklan (hanya di halaman utama)
    if (document.getElementById('hero')) {
        window.popupAds = new PopupAds();
    }

    // Logika untuk menu hamburger
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });

        document.addEventListener('click', (event) => {
            if (!hamburger.contains(event.target) && !navLinks.contains(event.target)) {
                navLinks.classList.remove('active');
            }
        });
    }

    // === Logika untuk Galeri Kategori dengan Scroll ===
    const kategoriKartu = document.querySelectorAll('.kategori-kartu');

    kategoriKartu.forEach(kartu => {
        kartu.addEventListener('click', function (e) {
            e.preventDefault();

            const targetId = this.dataset.target;
            const targetGallery = document.getElementById(targetId);

            if (!targetGallery) return;

            const isAlreadyActive = targetGallery.classList.contains('active');

            document.querySelectorAll('.gallery-content.active').forEach(activeGallery => {
                activeGallery.classList.remove('active');
            });

            if (!isAlreadyActive) {
                targetGallery.classList.add('active');

                // Tambahkan sedikit delay sebelum scroll agar animasi slide-down dimulai
                setTimeout(() => {
                    targetGallery.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100); // delay 100ms
            }
        });
    });

    // === Logika untuk Alur Kerja Card (Mobile Click) ===
    const alurKerjaCards = document.querySelectorAll('.alur-kerja-container .card');
    if (alurKerjaCards.length > 0) {
        // Menambahkan event listener ke setiap kartu
        alurKerjaCards.forEach(card => {
            card.addEventListener('click', function (e) {
                // Mencegah event lain terpanggil
                e.stopPropagation();

                const isAlreadyActive = this.classList.contains('active');

                // Hapus dulu kelas 'active' dari semua kartu
                alurKerjaCards.forEach(c => c.classList.remove('active'));

                // Jika kartu yang diklik belum aktif, maka aktifkan
                if (!isAlreadyActive) {
                    this.classList.add('active');
                }
            });
        });

        // Menambahkan event listener untuk menutup kartu jika diklik di luar area
        document.addEventListener('click', function (e) {
            const alurKerjaContainer = document.querySelector('.alur-kerja-container');
            // Jika klik terjadi di luar container alur kerja
            if (alurKerjaContainer && !alurKerjaContainer.contains(e.target)) {
                alurKerjaCards.forEach(c => c.classList.remove('active'));
            }
        });
    }
});

// =================================
// PENGUNJUNG REAL-TIME TRACKER (REVISED & ROBUST)
// =================================

// Ambil BASE_URL dari variabel global yang di-set oleh PHP
const BASE_URL = window.BASE_URL || 'http://localhost/mandorbangun.id/';

function trackVisitor() {
    const trackerUrl = BASE_URL + 'api/tracker.php';

    // 'keepalive: true' penting agar request tetap berjalan bahkan saat halaman ditutup
    fetch(trackerUrl, { method: 'POST', keepalive: true })
        .catch(error => console.error('Error saat menghubungi tracker:', error));
}

// Fungsi untuk memberitahu server saat user pergi
function notifyLeave() {
    const leaveUrl = BASE_URL + 'api/leave.php';
    const sessionId = window.PHP_SESSION_ID; // Ambil session ID dari PHP

    // navigator.sendBeacon adalah cara modern dan andal untuk mengirim
    // data saat halaman akan ditutup tanpa menunda prosesnya.
    if (navigator.sendBeacon && sessionId) {
        const formData = new FormData();
        formData.append('session_id', sessionId);
        navigator.sendBeacon(leaveUrl, formData);
    }
}

// Lakukan pelacakan saat halaman pertama kali dimuat
trackVisitor();

// Atur interval untuk terus melacak setiap 30 detik (menjaga status "online")
setInterval(trackVisitor, 10000);

// Tambahkan event listener yang lebih andal untuk mendeteksi saat user menutup tab/browser
// 'pagehide' lebih dapat diandalkan daripada 'unload'
window.addEventListener('pagehide', notifyLeave);
