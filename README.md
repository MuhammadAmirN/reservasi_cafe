# ☕ Sistem Reservasi Cafe

Sistem Reservasi Cafe adalah aplikasi web berbasis PHP dan MySQL yang dirancang untuk membantu pengelolaan reservasi meja, pemesanan produk, serta administrasi operasional cafe secara digital.

Proyek ini dikembangkan sebagai bagian dari portofolio akademik untuk mengimplementasikan konsep pengembangan aplikasi web, manajemen database, autentikasi pengguna, dan pengelolaan data menggunakan PHP Native dan MySQL.

---

## 📋 Fitur Utama

### Pengguna
- Registrasi akun
- Login dan Logout
- Melihat informasi cafe
- Melihat daftar menu dan produk
- Melakukan reservasi meja
- Melakukan pemesanan produk
- Konfirmasi pemesanan

### Admin
- Dashboard Admin
- Manajemen Pengguna (CRUD)
- Manajemen Produk (CRUD)
- Manajemen Menu
- Manajemen Reservasi
- Manajemen Pemesanan
- Manajemen Meja
- Laporan Harian

---

## 🛠️ Teknologi yang Digunakan

- PHP Native
- MySQL
- HTML5
- CSS3
- JavaScript
- XAMPP

---

## 💡 Kompetensi yang Diimplementasikan

Melalui proyek ini saya mempelajari dan menerapkan:

- Sistem autentikasi pengguna
- Session management
- Operasi CRUD (Create, Read, Update, Delete)
- Relational Database Management
- Pengelolaan data menggunakan MySQL
- Pengembangan dashboard admin
- Integrasi frontend dan backend
- Pengembangan aplikasi web berbasis PHP

---

## 📂 Struktur Proyek

```text
reservasi_cafe/
│
├── index.php
├── reservasi_cafe.sql
├── style.css
│
├── images/
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
        ├── kelola_produk.php
        ├── kelola_meja.php
        ├── kelola_reservasi.php
        ├── kelola_pemesanan.php
        └── laporan_harian.php
```

---

## ⚙️ Cara Menjalankan Proyek

### 1. Clone Repository

```bash
git clone https://github.com/username/nama-repository.git
```

### 2. Pindahkan ke Folder XAMPP

```text
xampp/htdocs/reservasi_cafe
```

### 3. Jalankan Apache dan MySQL

Aktifkan Apache dan MySQL melalui XAMPP Control Panel.

### 4. Buat Database

Buka phpMyAdmin kemudian buat database:

```sql
reservasi_cafe
```

### 5. Import Database

Import file:

```text
reservasi_cafe.sql
```

### 6. Konfigurasi Koneksi Database

Sesuaikan file:

```php
php/config.php
```

Contoh:

```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reservasi_cafe";
```

### 7. Jalankan Aplikasi

Buka browser:

```text
http://localhost/reservasi_cafe
```

---

## 🎯 Tujuan Pengembangan

Tujuan dari proyek ini adalah untuk memahami dan mengimplementasikan:

- Pengembangan aplikasi web berbasis PHP Native
- Perancangan dan implementasi database MySQL
- Pengelolaan data pelanggan dan reservasi
- Penerapan sistem autentikasi pengguna
- Pengembangan dashboard administrasi

---

## 📚 Status Proyek

✅ Selesai

Proyek ini digunakan sebagai portofolio pembelajaran dan dokumentasi kemampuan pengembangan aplikasi web menggunakan PHP dan MySQL.

---

## 👨‍💻 Pengembang

**Muh Amir**

Mahasiswa Informatika

Portfolio Project – Web Development & Database Management
