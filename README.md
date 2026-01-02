# Website Mandorbangun.id

Ini adalah repositori untuk kode sumber website resmi Mandorbangun.id, sebuah perusahaan jasa kontraktor profesional di Semarang.

## Deskripsi

Website ini berfungsi sebagai profil perusahaan, menampilkan layanan yang ditawarkan, portofolio proyek yang telah dikerjakan, dan menyediakan informasi kontak bagi calon klien.

## Struktur Proyek


mandorbangun.id/
├── admin/
│   ├── index.php       (Dashboard)
│   ├── login.php
│   ├── logout.php
│   ├── services.php    (Kelola Layanan)
│   ├── portfolio.php   (Kelola Portofolio)
│   └── settings.php    (Pengaturan Website)
├── api/
│   ├── services.php    (Endpoint untuk data layanan)
│   ├── portfolio.php   (Endpoint untuk data portofolio)
│   └── contact.php     (Endpoint untuk form kontak)
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── database/
│   └── mandorbangun.sql (Struktur database)
├── includes/
│   ├── config.php
│   ├── header.php
│   ├── footer.php
│   ├── admin_header.php
│   └── admin_footer.php
├── index.php           (Halaman utama)
└── README.md


## Instalasi

1.  **Clone repositori ini:**
    ```bash
    git clone [URL_REPO_ANDA]
    ```
2.  **Setup Database:**
    - Buat database baru di MySQL (misal: `mandorbangun_db`).
    - Impor file `database/mandorbangun.sql` ke dalam database yang baru Anda buat.
3.  **Konfigurasi Koneksi:**
    - Buka file `includes/config.php`.
    - Sesuaikan `DB_SERVER`, `DB_USERNAME`, `DB_PASSWORD`, dan `DB_NAME` dengan konfigurasi database Anda.
    - Sesuaikan `BASE_URL` dengan URL lokal Anda (misal: `http://localhost/mandorbangun.id/`).
4.  **Jalankan Server:**
    - Gunakan server lokal seperti XAMPP atau WAMP untuk menjalankan proyek.

## Kredensial Admin

-   **Username:** `admin`
-   **Password:** `password123`

> **Penting:** Segera ganti password default setelah login pertama kali melalui panel admin.

---
Dibuat dengan ❤️ oleh Tim Mandorbangun.id
