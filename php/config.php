<?php
$servername = "localhost";  // Nama server database
$username = "root";         // Username database
$password = "";             // Password database
$dbname = "reservasi_cafe"; // Nama database

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
