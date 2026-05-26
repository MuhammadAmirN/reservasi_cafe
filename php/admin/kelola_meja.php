<?php
include '../config.php'; // Pastikan jalur menuju file config benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $id_meja = $_POST['id_meja'];
        $kapasitas = $_POST['kapasitas'];
        $nomer_meja = $_POST['nomer_meja'];
        $sql = "INSERT INTO meja (id_meja, kapasitas, nomer_meja) VALUES ('$id_meja', '$kapasitas', '$nomer_meja')";
        if (mysqli_query($conn, $sql)) {
            echo "Meja berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['hapus'])) {
        $id_meja = $_POST['id_meja'];
        $sql = "DELETE FROM meja WHERE id_meja = '$id_meja'";
        if (mysqli_query($conn, $sql)) {
            echo "Meja berhasil dihapus!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['edit'])) {
        $id_meja = $_POST['id_meja'];
        $kapasitas = $_POST['kapasitas'];
        $nomer_meja = $_POST['nomer_meja'];
        $sql = "UPDATE meja SET kapasitas = '$kapasitas', nomer_meja = '$nomer_meja' WHERE id_meja = '$id_meja'";
        if (mysqli_query($conn, $sql)) {
            echo "Meja berhasil diperbarui!";
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
    <title>Kelola Meja</title>
    <link rel="stylesheet" href="kelola_meja.css">
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
        
        <h2>Kelola Meja</h2>
        <form method="POST" action="">
            <label for="id_meja">ID Meja:</label>
            <input type="number" name="id_meja" id="id_meja" required>
            <label for="kapasitas">Kapasitas:</label>
            <input type="number" name="kapasitas" id="kapasitas" required>
            <label for="nomer_meja">Nomor Meja:</label>
            <input type="number" name="nomer_meja" id="nomer_meja" required>
            <button type="submit" name="tambah">Tambah</button>
        </form>

        <h2>Daftar Meja</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kapasitas</th>
                    <th>Nomor Meja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM meja";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_meja']}</td>";
                    echo "<td>{$row['kapasitas']}</td>";
                    echo "<td>{$row['nomer_meja']}</td>";
                    echo "<td>
                            <button type='button' onclick=\"editMeja('{$row['id_meja']}', '{$row['kapasitas']}', '{$row['nomer_meja']}')\">Edit</button>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='id_meja' value='{$row['id_meja']}'>
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
        function editMeja(id, kapasitas, nomer_meja) {
            document.getElementById('id_meja').value = id;
            document.getElementById('kapasitas').value = kapasitas;
            document.getElementById('nomer_meja').value = nomer_meja;
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
