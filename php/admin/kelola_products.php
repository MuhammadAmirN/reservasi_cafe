<?php
include '../config.php'; // Pastikan jalur menuju file config benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $id_products = $_POST['id_products'];
        $kategori = $_POST['kategori'];
        $gambar = $_POST['gambar'];
        $sql = "INSERT INTO products (id_products, kategori, gambar) VALUES ('$id_products', '$kategori', '$gambar')";
        if (mysqli_query($conn, $sql)) {
            echo "Produk berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['hapus'])) {
        $id_products = $_POST['id_products'];
        $sql = "DELETE FROM products WHERE id_products = '$id_products'";
        if (mysqli_query($conn, $sql)) {
            echo "Produk berhasil dihapus!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['edit'])) {
        $id_products = $_POST['id_products'];
        $kategori = $_POST['kategori'];
        $gambar = $_POST['gambar'];
        $sql = "UPDATE products SET kategori = '$kategori', gambar = '$gambar' WHERE id_products = '$id_products'";
        if (mysqli_query($conn, $sql)) {
            echo "Produk berhasil diperbarui!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Products</title>
    <link rel="stylesheet" href="kelola_products.css">
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
        
        <h2>Kelola Products</h2>
        <form method="POST" action="">
            <label for="id_products">ID Produk:</label>
            <input type="number" name="id_products" id="id_products" required>
            <label for="kategori">Kategori:</label>
            <input type="text" name="kategori" id="kategori" required>
            <label for="gambar">Gambar:</label>
            <input type="text" name="gambar" id="gambar" required>
            <button type="submit" name="tambah">Tambah</button>
        </form>

        <h2>Daftar Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kategori</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM products";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_products']}</td>";
                    echo "<td>{$row['kategori']}</td>";
                    echo "<td><img src='../images/{$row['gambar']}' alt='{$row['kategori']}' width='50'></td>";
                    echo "<td>
                            <button type='button' onclick=\"editProduct('{$row['id_products']}', '{$row['kategori']}', '{$row['gambar']}')\">Edit</button>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='id_products' value='{$row['id_products']}'>
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
        function editProduct(id, kategori, gambar) {
            document.getElementById('id_products').value = id;
            document.getElementById('kategori').value = kategori;
            document.getElementById('gambar').value = gambar;
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
