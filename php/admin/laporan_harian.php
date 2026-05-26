<?php
include '../config.php'; // Koneksi ke database

// Ambil data pemesanan untuk laporan harian
$sql_pemesanan = "SELECT p.id_pemesanan, p.id_pengguna, p.jumlah, p.tanggal, p.id_metode, p.total_harga, 
                  m.nama_menu, m.stok_masuk, m.stok_keluar
                  FROM pemesanan p
                  JOIN menu m ON p.id_menu = m.id_menu
                  WHERE p.tanggal >= DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
$result_pemesanan = mysqli_query($conn, $sql_pemesanan);

$total_harga_harian = 0; // Variabel untuk menyimpan total harga harian

echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
echo "<title>Laporan Harian Pemesanan - Daams Cafe</title>";
echo "<link rel='stylesheet' href='laporan_harian.css'>"; // Menggunakan CSS yang dibuat khusus
echo "</head>";
echo "<body>";
echo "<header>";
echo "<nav>";
echo "<ul>";
echo "<li><a href='admin_dashboard.php'>Home</a></li>";
echo "<li><a href='kelola_menu.php'>Kelola Menu</a></li>";
echo "<li><a href='kelola_products.php'>Kelola Products</a></li>";
echo "<li><a href='kelola_pengguna.php'>Kelola Pengguna</a></li>";
echo "<li><a href='kelola_pemesanan.php'>Kelola Pemesanan</a></li>";
echo "<li><a href='kelola_meja.php'>Kelola Meja</a></li>";
echo "<li><a href='kelola_reservasi.php'>Kelola Reservasi</a></li>";
echo "</ul>";
echo "</nav>";
echo "</header>";
echo "<div class='report-container'>";

// Tampilkan laporan pemesanan harian
if ($result_pemesanan !== false && mysqli_num_rows($result_pemesanan) > 0) {
    echo "<h2>Laporan Harian Pemesanan</h2>";
    echo "<table>
            <tr>
                <th>ID Pemesanan</th>
                <th>Nama Menu</th>
                <th>ID Pengguna</th>
                <th>Jumlah</th>
                <th>Tanggal</th>
                <th>ID Metode</th>
                <th>Total Harga</th>
                <th>Stok Masuk</th>
                <th>Stok Keluar</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result_pemesanan)) {
        $total_harga_harian += $row['total_harga']; // Tambahkan harga ke total harga harian
        echo "<tr>
                <td>{$row['id_pemesanan']}</td>
                <td>{$row['nama_menu']}</td>
                <td>{$row['id_pengguna']}</td>
                <td>{$row['jumlah']}</td>
                <td>{$row['tanggal']}</td>
                <td>{$row['id_metode']}</td>
                <td>" . number_format($row['total_harga'], 2, ',', '.') . "</td>
                <td>{$row['stok_masuk']}</td>
                <td>{$row['stok_keluar']}</td>
            </tr>";
    }
    echo "</table>";
    echo "<h3>Total Harga Harian: Rp " . number_format($total_harga_harian, 2, ',', '.') . "</h3>";
} else {
    echo "<p>Tidak ada data pemesanan untuk hari ini.</p>";
}

// Ambil dan tampilkan data stok masuk
$sql_stok_masuk = "SELECT nama_menu, stok_masuk FROM menu WHERE stok_masuk > 0";
$result_stok_masuk = mysqli_query($conn, $sql_stok_masuk);

if ($result_stok_masuk !== false && mysqli_num_rows($result_stok_masuk) > 0) {
    echo "<h3>Stok Masuk Hari Ini</h3>";
    echo "<table>
            <tr>
                <th>Nama Menu</th>
                <th>Stok Masuk</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result_stok_masuk)) {
        echo "<tr>
                <td>{$row['nama_menu']}</td>
                <td>{$row['stok_masuk']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Tidak ada stok masuk untuk hari ini.</p>";
}

// Ambil dan tampilkan data stok keluar
$sql_stok_keluar = "SELECT nama_menu, stok_keluar FROM menu WHERE stok_keluar > 0";
$result_stok_keluar = mysqli_query($conn, $sql_stok_keluar);

if ($result_stok_keluar !== false && mysqli_num_rows($result_stok_keluar) > 0) {
    echo "<h3>Stok Keluar Hari Ini</h3>";
    echo "<table>
            <tr>
                <th>Nama Menu</th>
                <th>Stok Keluar</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result_stok_keluar)) {
        echo "<tr>
                <td>{$row['nama_menu']}</td>
                <td>{$row['stok_keluar']}</td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Tidak ada stok keluar untuk hari ini.</p>";
}

echo "</div>";
echo "</body>";
echo "</html>";

mysqli_close($conn);
?>
