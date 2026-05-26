<?php
include 'config.php';

// Validasi parameter
if (!isset($_GET['message'], $_GET['nama_pengguna'], $_GET['pemesanan_ids'])) {
    die("Data konfirmasi tidak lengkap.");
}

$message = urldecode($_GET['message']);
$nama_pengguna = urldecode($_GET['nama_pengguna']);
$pemesanan_ids = explode(',', urldecode($_GET['pemesanan_ids']));

if (empty($pemesanan_ids)) {
    die("Tidak ada data pemesanan yang ditemukan.");
}

// Query untuk mendapatkan data pemesanan
$sql = "SELECT m.id_menu, m.nama_menu, SUM(p.jumlah) AS total_jumlah, SUM(p.total_harga) AS total_harga_menu
        FROM pemesanan p
        JOIN menu m ON p.id_menu = m.id_menu
        WHERE p.id_pemesanan IN (" . implode(',', array_fill(0, count($pemesanan_ids), '?')) . ")
        GROUP BY m.id_menu, m.nama_menu";

$stmt = $conn->prepare($sql);
$stmt->bind_param(str_repeat('i', count($pemesanan_ids)), ...$pemesanan_ids);
$stmt->execute();
$result = $stmt->get_result();

$pemesanan_data = [];
$total_harga = 0;

// Proses hasil query
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pemesanan_data[] = $row;
        $total_harga += $row['total_harga_menu'];
    }
}

// Kurangi stok dan perbarui stok keluar untuk setiap pesanan
foreach ($pemesanan_data as $pesanan) {
    kurangiStok($pesanan['id_menu'], $pesanan['total_jumlah']);
}

$stmt->close();

function kurangiStok($id_menu, $jumlah) {
    global $conn;
    
    // Ambil data stok menu dari database
    $query = "SELECT stok, stok_keluar, stok_masuk FROM menu WHERE id_menu = '$id_menu'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stok_sebelumnya = $row['stok'];
        $stok_masuk = $row['stok_masuk'];
        $stok_keluar_sebelumnya = $row['stok_keluar'];

        // Hitung stok baru
        $stok_baru = $stok_sebelumnya - $jumlah;
        $stok_keluar = $stok_keluar_sebelumnya + $jumlah;

        // Jika stok baru kurang dari 0, coba ambil dari stok masuk
        if ($stok_baru < 0) {
            $stok_baru = 0;
            $stok_masuk -= abs($stok_baru);  // Mengurangi stok masuk sesuai kekurangan
            if ($stok_masuk < 0) {
                $stok_masuk = 0;  // Pastikan stok masuk tidak negatif
            }
        }

        // Perbarui stok, stok keluar, dan stok masuk di database
        $sql = "UPDATE menu 
                SET stok = '$stok_baru', 
                    stok_keluar = '$stok_keluar', 
                    stok_masuk = '$stok_masuk'
                WHERE id_menu = '$id_menu'";

        // Eksekusi query untuk memperbarui stok di database
        mysqli_query($conn, $sql);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Daams Cafe</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .confirmation-container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
            background: #f9f9f9;
        }
        .menu-list {
            margin-top: 20px;
        }
        .menu-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }
        .menu-item:last-child {
            border-bottom: none;
        }
        .back-home {
            display: block;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-home:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <h2>Konfirmasi Pesanan</h2>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Nama Pengguna: <?php echo htmlspecialchars($nama_pengguna, ENT_QUOTES, 'UTF-8'); ?></p>
        <p>Tanggal Pesan: <?php echo date("Y-m-d"); ?></p>
        
        <h3>Menu yang Dipesan:</h3>
        <div class="menu-list">
            <?php if (!empty($pemesanan_data)): ?>
                <?php foreach ($pemesanan_data as $pesanan): ?>
                    <div class="menu-item">
                        <p>Nama Menu: <?php echo htmlspecialchars($pesanan['nama_menu'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Jumlah Dipesan: <?php echo htmlspecialchars($pesanan['total_jumlah'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p>Total Harga: Rp <?php echo number_format($pesanan['total_harga_menu'], 0, ',', '.'); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada menu yang dipesan.</p>
            <?php endif; ?>
        </div>
        <a href="menu.php" class="back-home">Kembali ke Halaman Menu</a>
    </div>
</body>
</html>
