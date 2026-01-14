# Technical Test - Junior Fullstack Developer (Sistem Inventaris)

Repositori ini adalah hasil pengerjaan **Technical Test** untuk posisi **Junior Fullstack Developer**. Aplikasi ini dibangun menggunakan framework **Laravel 11**, **Vue.js (Inertia.js)**, dan **Tailwind CSS**.

Sistem ini mencakup dua modul utama sesuai spesifikasi soal: **Manajemen Inventaris (Produk)** dan **Manajemen Pengguna (User & Role)**, dengan tambahan fitur UI/UX untuk meningkatkan pengalaman pengguna.

---

## 🚀 Fitur & Fungsionalitas

### 1. Sistem Inventaris (Inventory)
* **CRUD Produk:** Menambah dan melihat daftar stok barang.
* **Transaksi Penjualan:** Mengurangi stok barang dengan tombol "Jual".
* **Validasi Stok Ketat:**
  * Stok tidak boleh bernilai negatif.
  * Sistem otomatis menolak transaksi jika stok habis (0).
  * Menggunakan `DB Transaction` dan `lockForUpdate` untuk mencegah *race condition* (data bentrok saat akses bersamaan).
* **✨ Fitur Tambahan (Nilai Plus):**
  * **Pencarian Real-time:** Mencari nama barang tanpa reload halaman.
  * **Pagination:** Menampilkan data per 10 baris agar aplikasi tetap ringan.
  * **Status Visual:** Indikator warna pada stok (Hijau = Aman, Merah = Habis).

### 2. Manajemen Pengguna (User Management)
* **List User:** Menampilkan daftar pengguna beserta Role mereka.
* **Ubah Role:** Admin dapat mengubah hak akses pengguna (Admin, Seller, Pelanggan) melalui dropdown.
* **Validasi:** Hanya role yang terdaftar di database yang dapat disimpan.

### 3. UI/UX Modern
* **SweetAlert2:** Notifikasi *popup* interaktif (bukan alert browser standar) untuk respon sukses/gagal.
* **Responsive Design:** Tampilan tabel dan form menyesuaikan layar Desktop dan Mobile.
* **Loading States:** Indikator loading saat memproses data.

---

## 🛠️ Tech Stack

* **Backend:** PHP 8.x, Laravel 11
* **Frontend:** Vue.js 3 (Composition API), Inertia.js
* **Styling:** Tailwind CSS
* **Database:** MySQL / MariaDB
* **Tools:** Vite, SweetAlert2

---

## ⚙️ Cara Instalasi (Local Setup)

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal Anda:

### 1. Clone Repositori
```bash
git clone https://github.com/ANazmuddin/junior_fullStack_developer.git
cd junior_fullStack_developer

```

### 2. Install Dependencies

Install paket backend (Composer) dan frontend (NPM):

```bash
composer install
npm install

```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env

```

Buka file `.env` dan sesuaikan konfigurasi database Anda (Sesuaikan `DB_USERNAME` dan `DB_PASSWORD` dengan settingan lokal Anda):

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=technical_test
DB_USERNAME=root
DB_PASSWORD=

```

### 4. Generate App Key

```bash
php artisan key:generate

```

### 5. Setup Database & Seeding (Wajib)

Jalankan migrasi untuk membuat tabel dan mengisi data awal (Role Admin, Seller, Pelanggan & Akun Admin Demo):

```bash
php artisan migrate:fresh --seed

```

### 6. Jalankan Aplikasi

Anda perlu menjalankan dua terminal terpisah:

**Terminal 1 (Backend Server):**

```bash
php artisan serve

```

**Terminal 2 (Frontend Compiler):**

```bash
npm run dev

```

Akses aplikasi melalui browser di: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo (Login)

Saat Anda menjalankan perintah `php artisan migrate:fresh --seed`, sistem secara otomatis membuat akun Admin untuk keperluan pengujian:

* **Email:** `admin@example.com`
* **Password:** `password`

---

## 🎥 Video Demo

Berikut adalah link video demo simulasi penggunaan aplikasi:
**[TEMPELKAN LINK VIDEO GOOGLE DRIVE / YOUTUBE ANDA DISINI]**

---

## 📝 Catatan Pengembang

* Aplikasi dikembangkan di lingkungan **Linux (Manjaro)** namun kompatibel sepenuhnya dengan Windows dan macOS.
* Pastikan ekstensi PHP `pdo_mysql` dan `gd` sudah aktif di komputer Anda.