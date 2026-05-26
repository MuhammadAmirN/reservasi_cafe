<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah_stok'])) {
        $id_menu = $_POST['id_menu'];
        $stok_masuk_baru = $_POST['stok_masuk'];

        if (isset($_POST['tambah_stok'])) {
            $id_menu = $_POST['id_menu'];
            $stok_masuk_baru = $_POST['stok_masuk'];
        
            // Ambil stok sebelumnya dari database
            $query = "SELECT stok_masuk FROM menu WHERE id_menu = '$id_menu'";
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
        
                // Perbarui stok masuk secara manual
                $stok_masuk_baru += $row['stok_masuk'];
        
                // Perbarui stok masuk di database
                $sql = "UPDATE menu 
                        SET stok_masuk = '$stok_masuk_baru'
                        WHERE id_menu = '$id_menu'";
        
                if (mysqli_query($conn, $sql)) {
                    echo "Stok masuk berhasil diperbarui!";
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "ID menu tidak ditemukan.";
            }
        }
        
    }

    if (isset($_POST['edit'])) {
        $id_menu = $_POST['id_menu'];
        $nama_menu = $_POST['nama_menu'];
        $kategori = $_POST['kategori'];
        $harga = $_POST['harga'];
        $gambar = $_POST['gambar'];

        // Update data menu kecuali stok
        $sql = "UPDATE menu 
                SET nama_menu = '$nama_menu', 
                    kategori = '$kategori', 
                    harga = '$harga', 
                    gambar = '$gambar'
                WHERE id_menu = '$id_menu'";

        if (mysqli_query($conn, $sql)) {
            echo "Menu berhasil diperbarui!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    if (isset($_POST['hapus'])) {
        $id_menu = $_POST['id_menu'];
        $sql = "DELETE FROM menu WHERE id_menu = '$id_menu'";
        if (mysqli_query($conn, $sql)) {
            echo "Menu berhasil dihapus!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}

// Fungsi untuk mengurangi stok saat ada pesanan dan memperbarui stok keluar
function kurangiStok($id_menu, $jumlah) {
    global $conn;
    $query = "SELECT stok_masuk, stok_keluar FROM menu WHERE id_menu = '$id_menu'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stok_masuk = $row['stok_masuk'];
        $stok_keluar_sebelumnya = $row['stok_keluar'];

        // Kurangi stok masuk sesuai dengan jumlah pesanan per item menu
        if ($stok_masuk >= $jumlah) {
            $stok_masuk -= $jumlah;
        } else {
            $jumlah -= $stok_masuk;
            $stok_masuk = 0;
        }

        // Hitung stok keluar baru
        $stok_keluar = $stok_keluar_sebelumnya + $jumlah;

        // Perbarui stok masuk dan stok keluar di database
        $sql = "UPDATE menu 
                SET stok_masuk = '$stok_masuk', 
                    stok_keluar = '$stok_keluar'
                WHERE id_menu = '$id_menu'";
        mysqli_query($conn, $sql);
    }
}

// Contoh pemrosesan pesanan
if (isset($_POST['pesanan'])) {
    $pesanan = json_decode($_POST['pesanan'], true);
    foreach ($pesanan as $item) {
        kurangiStok($item['id_menu'], $item['jumlah']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu</title>
    <link rel="stylesheet" href="kelola_menu.css">
</head>
<body>
    <div class="container">
        <nav>
            <ul class="horizontal-nav">
                <li><a href="kelola_menu.php">Kelola Menu</a></li>
                <li><a href="kelola_products.php">Kelola Products</a></li>
                <li><a href="kelola_meja.php">Kelola Meja</a></li>
                <li><a href="kelola_pengguna.php">Kelola Pengguna</a></li>
                <li><a href="kelola_pemesanan.php">Kelola Pemesanan</a></li>
                <li><a href="kelola_reservasi.php">Kelola Reservasi</a></li>
                <li><a href="laporan_harian.php">Laporan Harian</a></li>
            </ul>
        </nav>
        <h2>Kelola Menu</h2>
        <form method="POST" action="">
            <input type="hidden" name="id_menu" id="id_menu">
            <label for="nama_menu">Nama Menu:</label>
            <input type="text" name="nama_menu" id="nama_menu" required>
            <label for="kategori">Kategori:</label>
            <input type="text" name="kategori" id="kategori" required>
            <label for="harga">Harga:</label>
            <input type="number" name="harga" id="harga" required>
            <label for="gambar">Gambar:</label>
            <input type="text" name="gambar" id="gambar" required>
            <button type="submit" name="edit">Perbarui</button>
        </form>

        <h2>Tambah Stok</h2>
        <form method="POST" action="">
            <input type="hidden" name="id_menu" id="id_menu_tambah_stok">
            <label for="stok_masuk">Stok Masuk:</label>
            <input type="number" name="stok_masuk" id="stok_masuk" required>
            <button type="submit" name="tambah_stok">Tambah Stok</button>
        </form>

        <h2>Daftar Menu</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Gambar</th>
                    <th>Stok Masuk</th>
                    <th>Stok Keluar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT id_menu, nama_menu, kategori, harga, gambar, stok_masuk, stok_keluar FROM menu";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_menu']}</td>";
                    echo "<td>{$row['nama_menu']}</td>";
                    echo "<td>{$row['kategori']}</td>";
                    echo "<td>{$row['harga']}</td>";
                    echo "<td><img src='../images/{$row['gambar']}' alt='{$row['nama_menu']}' width='50'></td>";
                    echo "<td>{$row['stok_masuk']}</td>";
                    echo "<td>{$row['stok_keluar']}</td>";
                    echo "<td>
                            <button type='button' onclick=\"editMenu('{$row['id_menu']}', '{$row['nama_menu']}', '{$row['kategori']}', '{$row['harga']}', '{$row['gambar']}')\">Edit</button>
                            <button type='button' onclick=\"tambahStok('{$row['id_menu']}')\">Tambah Stok</button>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='id_menu' value='{$row['id_menu']}'>
                                <button type='submit' name='hapus'>Hapus</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function editMenu(id, nama, kategori, harga, gambar) {
            document.getElementById('id_menu').value = id;
            document.getElementById('nama_menu').value = nama;
            document.getElementById('kategori').value = kategori;
            document.getElementById('harga').value = harga;
            document.getElementById('gambar').value = gambar;
        }

        function tambahStok(id) {
            document.getElementById('id_menu_tambah_stok').value = id;
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
