<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="admin_dashboard.css">
</head>
<body>
    <header>
    <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
    </header>
    <nav>
        <ul>
            <li><a href="kelola_menu.php">Kelola Menu</a></li>
            <li><a href="kelola_products.php">Kelola Products</a></li>
            <li><a href="kelola_meja.php">Kelola Meja</a></li>
            <li><a href="kelola_pengguna.php">Kelola Pengguna</a></li>
            <li><a href="kelola_pemesanan.php">Kelola Pemesanan</a></li>
            <li><a href="kelola_reservasi.php">Kelola Reservasi</a></li>
            <li><a href="laporan_harian.php">Laporan Harian</a></li>
        </ul>
    </nav>
    <div class="content">
    </div>
</body>
</html>
