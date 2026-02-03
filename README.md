# App-POS (Sistem Kasir)

Aplikasi Point of Sale (POS) modern yang dibangun menggunakan Laravel 12. Aplikasi ini dirancang untuk memudahkan manajemen produk, kategori, transaksi, dan pemantauan laporan penjualan bagi pemilik bisnis.

Aplikasi ini dibuat untuk memenuhi tugas UAS mata kuliah Framework Programming Pandu Aldi Pratama dengan NIM 25094003 - RPL - D4 Teknik Informatika Universitas Harkat Negeri Tegal

## Fitur Utama

- **Dashboard**: Ringkasan performa penjualan dan statistik utama.
- **Manajemen Produk**: Kelola data produk, harga, dan stok dengan mudah.
- **Kategori Produk**: Pengelompokan produk untuk pencarian yang lebih cepat.
- **Transaksi Penjualan**: Antarmuka kasir yang responsif untuk memproses transaksi pelanggan.
- **Laporan Transaksi**: Analisis penjualan dengan grafik tren dan riwayat transaksi yang mendalam.
- **Role Pengguna**: Sistem otentikasi dengan peran Admin dan Kasir.

## Prasyarat

Pastikan sistem Anda memenuhi persyaratan berikut:

- PHP >= 8.2
- Database (MySQL/MariaDB)
- Composer

## Panduan Instalasi

Ikuti langkah-langkah di bawah ini untuk memulai pengembangan:

1.  **Clone Repository**

    ```bash
    git clone <repository-url>
    cd app-pos
    ```

2.  **Jalankan Setup Otomatis**
    Aplikasi ini menyediakan perintah kustom untuk menginstal dependensi (Composer & NPM), menyalin file `.env`, dan generate kunci aplikasi:

    ```bash
    composer install
    ```

3.  **Konfigurasi Database**
    Buka file `.env` dan sesuaikan kredensial database Anda:

    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=app_pos
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Migrasi & Seed Data**
    Buat struktur tabel dan isi dengan data dummy (pengguna, kategori, produk) untuk keperluan testing:

    ```bash
    php artisan migrate:fresh --seed
    ```

5.  **Jalankan Server**
    Jalankan server pengembangan Laravel:
    ```bash
    php artisan serve
    ```
    Aplikasi sekarang dapat diakses melalui [http://127.0.0.1:8000](http://127.0.0.1:8000).

## Akun Demo

| Role  | Username | Password |
| :---- | :------- | :------- |
| Admin | admin    | password |
| Kasir | kasir1   | password |

## Teknologi

- **Backend**: Laravel 12 (Framework PHP)
- **Styling**: Vanilla CSS (Modern UI Design)
- **Interaction**: JavaScript (Vanilla / Alpine.js)
- **Data Visualization**: Chart.js

---

Dikembangkan dengan ❤️ menggunakan Laravel.
