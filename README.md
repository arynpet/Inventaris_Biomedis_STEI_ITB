# 🔬 Inventaris Biomedis STEI ITB

Aplikasi manajemen inventaris komprehensif yang dirancang khusus untuk lingkungan Biomedis STEI ITB. Sistem ini menangani sirkulasi peminjaman aset dan ruangan, serta manajemen operasional laboratorium (termasuk pencetakan 3D) menggunakan arsitektur MVC modern dengan Laravel 12.

## 🛠️ Technologies

Project ini dibangun menggunakan stack teknologi modern berikut:

- `Laravel` (v12 - Bleeding Edge)
- `PHP` (^8.2)
- `Vite` (^7.0)
- `Tailwind CSS` (v4.0)
- `Alpine.js`
- `MySQL / MariaDB`
- `Pest` (Testing Framework)
- `DomPDF` (PDF Generation)
- `Simple QR Code`

## ✨ Features

Berikut adalah fitur utama yang tersedia dalam sistem ini:

### 📦 Manajemen Inventaris (Core Inventory)
- **Asset Tracking**: CRUD lengkap untuk aset lab dengan detail nomor seri dan spesifikasi.
- **Location Management**: Pengelompokan aset berdasarkan Ruangan (`Rooms`) dan Kategori.
- **Item Movement**: Fitur khusus untuk memindahkan lokasi penyimpanan aset antar ruangan.

### 🔄 Sirkulasi & Peminjaman
- **Item Borrowing**: Mencatat peminjaman barang dengan status *real-time* (`borrowed`, `returned`, `late`) dan validasi tanggal otomatis.
- **Room Booking**: Modul reservasi fasilitas ruangan laboratorium.
- **User Validation**: Validasi peminjam berdasarkan status pelatihan (*Training Status*) untuk otorisasi penggunaan alat khusus.
- **History Logs**: Rekam jejak sirkulasi yang lengkap untuk keperluan audit.

### 🖨️ Modul Laboratorium 3D Print
- **Printer Management**: Inventarisasi mesin printer 3D.
- **Material Tracking**: Pelacakan stok filamen/resin yang berkurang secara otomatis saat pencetakan dilakukan.
- **Job Monitoring**: Pencatatan aktivitas cetak yang terhubung dengan pengguna, printer, dan file desain.

### 📊 Otomasi & Pelaporan
- **QR Code Integration**:
  - *Generation*: Pembuatan label QR otomatis untuk setiap item.
  - *Scanning*: Fitur pindai QR untuk mempercepat proses peminjaman.
- **PDF Export**: Ekspor dokumen label QR, riwayat peminjaman, dan detail formulir peminjaman dalam format PDF.

## 🚀 Running the Project

Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

1. **Clone Repositori**
   Unduh kode sumber ke mesin lokal Anda:
   ```bash
   git clone https://github.com/arynpet/Inventaris_Biomedis_STEI_ITB
   cd Inventaris_Biomedis_STEI_ITB
   ```
   
2. **Instal Dependensi**
    Jalankan perintah berikut untuk menginstal pustaka Backend (Composer) dan Frontend (NPM):
    ```bash
    composer install
    npm install
    ```

3. **Konfigurasi Environment**
    Salin file konfigurasi contoh dan sesuaikan dengan kredensial database lokal Anda:
    ```bash
    cp .env.example .env
    ```


*Buka file `.env` dan sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`.*
4. **Setup Aplikasi**
Generate kunci aplikasi, buat symbolic link untuk storage, dan jalankan migrasi database:
    ```
    php artisan key:generate
    php artisan storage:link
    php artisan migrate
    ```


5. **Jalankan Server**
    Anda perlu menjalankan dua proses secara paralel (gunakan terminal terpisah):
    **Terminal 1 (Vite - Frontend Build):**
    ```
    npm run dev
    ```


    **Terminal 2 (Laravel - Backend Server):**
    ```bash
    php artisan serve
    ```

6. **Akses Aplikasi**
Buka [http://127.0.0.1:8000] di browser Anda untuk mulai menggunakan aplikasi.

## 🧪 Testing

Proyek ini menggunakan **Pest** untuk pengujian unit dan fitur. Untuk menjalankan test suite:

```
php artisan test

```
