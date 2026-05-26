<?php
include '../config.php'; // Pastikan jalur menuju file config benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $id_menu = $_POST['id_menu'];
        $id_pengguna = $_POST['id_pengguna'];
        $jumlah = $_POST['jumlah'];
        $tanggal = $_POST['tanggal'];
        $status = $_POST['status'];
        $id_metode = $_POST['id_metode'];
        $total_harga = $_POST['total_harga'];
        $sql = "INSERT INTO pemesanan (id_menu, id_pengguna, jumlah, tanggal, status, id_metode, total_harga) VALUES ('$id_menu', '$id_pengguna', '$jumlah', '$tanggal', '$status', '$id_metode', '$total_harga')";
        if (mysqli_query($conn, $sql)) {
            echo "Pemesanan berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['hapus'])) {
        $id_pemesanan = $_POST['id_pemesanan'];
        $sql = "DELETE FROM pemesanan WHERE id_pemesanan = '$id_pemesanan'";
        if (mysqli_query($conn, $sql)) {
            echo "Pemesanan berhasil dihapus!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['edit'])) {
        $id_pemesanan = $_POST['id_pemesanan'];
        $id_menu = $_POST['id_menu'];
        $id_pengguna = $_POST['id_pengguna'];
        $jumlah = $_POST['jumlah'];
        $tanggal = $_POST['tanggal'];
        $status = $_POST['status'];
        $id_metode = $_POST['id_metode'];
        $total_harga = $_POST['total_harga'];
        $sql = "UPDATE pemesanan SET id_menu = '$id_menu', id_pengguna = '$id_pengguna', jumlah = '$jumlah', tanggal = '$tanggal', status = '$status', id_metode = '$id_metode', total_harga = '$total_harga' WHERE id_pemesanan = '$id_pemesanan'";
        if (mysqli_query($conn, $sql)) {
            echo "Pemesanan berhasil diperbarui!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pemesanan</title>
    <link rel="stylesheet" href="kelola_pemesanan.css">
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
        
        <h2>Kelola Pemesanan</h2>
        <form method="POST" action="">
            <input type="hidden" name="id_pemesanan" id="id_pemesanan">
            <label for="id_menu">ID Menu:</label>
            <input type="number" name="id_menu" id="id_menu" required>
            <label for="id_pengguna">ID Pengguna:</label>
            <input type="number" name="id_pengguna" id="id_pengguna" required>
            <label for="jumlah">Jumlah:</label>
            <input type="number" name="jumlah" id="jumlah" required>
            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" required>
            <label for="id_metode">ID Metode:</label>
            <input type="number" name="id_metode" id="id_metode" required>
            <label for="total_harga">Total Harga:</label>
            <input type="number" step="0.01" name="total_harga" id="total_harga" required>
            <button type="submit" name="tambah">Tambah</button>
        </form>

        <h2>Daftar Pemesanan</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Pemesanan</th>
                    <th>ID Menu</th>
                    <th>Nama Menu</th>
                    <th>ID Pengguna</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>ID Metode</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT p.*, m.nama_menu 
                        FROM pemesanan p
                        JOIN menu m ON p.id_menu = m.id_menu";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_pemesanan']}</td>";
                    echo "<td>{$row['id_menu']}</td>";
                    echo "<td>{$row['nama_menu']}</td>"; // Menampilkan nama menu
                    echo "<td>{$row['id_pengguna']}</td>";
                    echo "<td>{$row['jumlah']}</td>";
                    echo "<td>{$row['tanggal']}</td>";
                    echo "<td>{$row['id_metode']}</td>";
                    echo "<td>" . number_format($row['total_harga'], 2, ',', '.') . "</td>";
                    echo "<td>
                            <button type='button' onclick=\"editPemesanan('{$row['id_pemesanan']}', '{$row['id_menu']}', '{$row['nama_menu']}', '{$row['id_pengguna']}', '{$row['jumlah']}', '{$row['tanggal']}', '{$row['id_metode']}', '{$row['total_harga']}')\">Edit</button>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='id_pemesanan' value='{$row['id_pemesanan']}'>
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
        function editPemesanan(id, id_menu, nama_menu, id_pengguna, jumlah, tanggal, status, id_metode, total_harga) {
            document.getElementById('id_pemesanan').value = id;
            document.getElementById('id_menu').value = id_menu;
            document.getElementById('id_pengguna').value = id_pengguna;
            document.getElementById('jumlah').value = jumlah;
            document.getElementById('tanggal').value = tanggal;
            document.getElementById('status').value = status;
            document.getElementById('id_metode').value = id_metode;
            document.getElementById('total_harga').value = total_harga;
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
