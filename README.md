# ☕ Daams Cafe Reservation System

Sistem Reservasi Cafe berbasis PHP dan MySQL yang memungkinkan pelanggan melakukan registrasi akun, reservasi meja, pemesanan menu, serta memberikan kemudahan bagi admin dalam mengelola data pengguna, reservasi, produk, dan laporan harian.

## 📌 Deskripsi Proyek

Daams Cafe Reservation System dikembangkan untuk membantu digitalisasi proses reservasi dan pemesanan pada cafe. Sistem ini menyediakan fitur bagi pelanggan untuk melakukan reservasi secara online serta memudahkan admin dalam mengelola operasional cafe melalui dashboard khusus.

Proyek ini dibuat menggunakan:

- PHP Native
- MySQL Database
- HTML5
- CSS3
- XAMPP (Apache & MySQL)

---

## ✨ Fitur Utama

### 👤 Pelanggan
- Registrasi akun
- Login & Logout
- Melihat informasi cafe
- Melihat daftar menu
- Melakukan reservasi meja
- Melakukan pemesanan produk
- Konfirmasi pemesanan

### 🔑 Admin
- Dashboard Admin
- Kelola Data Pengguna
- Kelola Data Menu
- Kelola Data Produk
- Kelola Reservasi
- Kelola Pemesanan
- Kelola Meja
- Laporan Harian

---

## 📂 Struktur Project

```text
reservasi_cafe/
│
├── index.php
├── style.css
├── reservasi_cafe.sql
│
├── images/
│   └── Asset gambar website
│
└── php/
    ├── Login.php
    ├── Logout.php
    ├── register.php
    ├── reservasi.php
    ├── menu.php
    ├── products.php
    ├── contact.php
    ├── about.php
    ├── konfirmasi.php
    ├── config.php
    │
    └── admin/
        ├── admin_dashboard.php
        ├── kelola_pengguna.php
        ├── kelola_menu.php
        ├── kelola_products.php
        ├── kelola_meja.php
        ├── kelola_reservasi.php
        ├── kelola_pemesanan.php
        └── laporan_harian.php
```

---

## 🗄️ Database

Nama Database:

```sql
reservasi_cafe
```

Import file:

```text
reservasi_cafe.sql
```

Konfigurasi database dapat diubah pada file:

```php
php/config.php
```

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservasi_cafe";
```

---

## 🚀 Cara Menjalankan Project

### 1. Clone Repository

```bash
git clone https://github.com/username/reservasi-cafe.git
```

### 2. Pindahkan ke Folder XAMPP

```text
xampp/htdocs/reservasi_cafe
```

### 3. Jalankan Apache dan MySQL

Buka XAMPP Control Panel lalu aktifkan:

- Apache
- MySQL

### 4. Import Database

1. Buka phpMyAdmin
2. Buat database baru:

```sql
reservasi_cafe
```

3. Import file:

```text
reservasi_cafe.sql
```

### 5. Jalankan Website

Buka browser:

```text
http://localhost/reservasi_cafe
```

---

## 📸 Tampilan Sistem

### Halaman Utama
- Home
- About
- Products
- Contact
- Reservations

### Dashboard Admin
- Kelola Pengguna
- Kelola Produk
- Kelola Menu
- Kelola Reservasi
- Kelola Pemesanan
- Kelola Meja
- Laporan Harian

---

## 🎯 Tujuan Pengembangan

Proyek ini dibuat sebagai implementasi sistem informasi berbasis web untuk membantu proses:

- Reservasi tempat secara online
- Pemesanan menu cafe
- Manajemen data pelanggan
- Pengelolaan operasional cafe
- Penyusunan laporan transaksi

---

## 👨‍💻 Teknologi yang Digunakan

- PHP Native
- MySQL
- HTML
- CSS
- JavaScript
- XAMPP

---

## 📄 Lisensi

Project ini dibuat untuk kebutuhan pembelajaran, pengembangan portofolio, dan kegiatan magang.

---

### Author

**Muh Amir**

Mahasiswa Informatika  
Web Developer | Backend Developer | Database Enthusiast
