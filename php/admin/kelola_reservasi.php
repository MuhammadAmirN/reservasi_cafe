<?php
include '../config.php'; // Pastikan jalur menuju file config benar

// Mendapatkan data nama menu, metode pembayaran, meja, dan waktu reservasi dari database
$sql_menu = "SELECT * FROM menu";
$result_menu = mysqli_query($conn, $sql_menu);

$sql_pembayaran = "SELECT * FROM metode_pembayaran";
$result_pembayaran = mysqli_query($conn, $sql_pembayaran);

$sql_meja = "SELECT * FROM meja";
$result_meja = mysqli_query($conn, $sql_meja);

$sql_waktu = "SELECT * FROM waktu_reservasi";
$result_waktu = mysqli_query($conn, $sql_waktu);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $id_pengguna = $_POST['id_pengguna'];
        $id_meja = $_POST['id_meja'];
        $tanggal = $_POST['tanggal'];
        $id_waktu = $_POST['id_waktu'];
        $jumlah_orang = $_POST['jumlah_orang'];
        $id_menu = $_POST['id_menu'];
        $metode_pembayaran = $_POST['metode_pembayaran'];
        $status = $_POST['status'];
        $jumlah_menu = $_POST['jumlah_menu'];
        $total_harga = $_POST['total_harga'];

        // Menghitung waktu akhir reservasi (batasan maksimal 3 jam)
        $sql_waktu_awal = "SELECT waktu FROM waktu_reservasi WHERE id_waktu = '$id_waktu'";
        $result_waktu_awal = mysqli_query($conn, $sql_waktu_awal);
        if ($result_waktu_awal) {
            $row_waktu_awal = mysqli_fetch_assoc($result_waktu_awal);
            $waktu_awal = $row_waktu_awal['waktu'];
            $waktu_akhir = date('H:i:s', strtotime($waktu_awal . ' + 3 hours'));
        } else {
            die("Query gagal: " . mysqli_error($conn));
        }

        $sql = "INSERT INTO reservasi (id_pengguna, id_meja, tanggal, id_waktu, jumlah_orang, id_menu, metode_pembayaran, status, jumlah_menu, total_harga, waktu_awal, waktu_akhir) VALUES ('$id_pengguna', '$id_meja', '$tanggal', '$id_waktu', '$jumlah_orang', '$id_menu', '$metode_pembayaran', '$status', '$jumlah_menu', '$total_harga', '$waktu_awal', '$waktu_akhir')";
        if (mysqli_query($conn, $sql)) {
            echo "Reservasi berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['hapus'])) {
        $id_reservasi = $_POST['id_reservasi'];
        $sql = "DELETE FROM reservasi WHERE id_reservasi = '$id_reservasi'";
        if (mysqli_query($conn, $sql)) {
            echo "Reservasi berhasil dihapus!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['edit'])) {
        $id_reservasi = $_POST['id_reservasi'];
        $id_pengguna = $_POST['id_pengguna'];
        $id_meja = $_POST['id_meja'];
        $tanggal = $_POST['tanggal'];
        $id_waktu = $_POST['id_waktu'];
        $jumlah_orang = $_POST['jumlah_orang'];
        $id_menu = $_POST['id_menu'];
        $metode_pembayaran = $_POST['metode_pembayaran'];
        $status = $_POST['status'];
        $jumlah_menu = $_POST['jumlah_menu'];
        $total_harga = $_POST['total_harga'];

        // Menghitung waktu akhir reservasi (batasan maksimal 3 jam)
        $sql_waktu_awal = "SELECT waktu FROM waktu_reservasi WHERE id_waktu = '$id_waktu'";
        $result_waktu_awal = mysqli_query($conn, $sql_waktu_awal);
        if ($result_waktu_awal) {
            $row_waktu_awal = mysqli_fetch_assoc($result_waktu_awal);
            $waktu_awal = $row_waktu_awal['waktu'];
            $waktu_akhir = date('H:i:s', strtotime($waktu_awal . ' + 3 hours'));
        } else {
            die("Query gagal: " . mysqli_error($conn));
        }

        $sql = "UPDATE reservasi SET id_pengguna = '$id_pengguna', id_meja = '$id_meja', tanggal = '$tanggal', id_waktu = '$id_waktu', jumlah_orang = '$jumlah_orang', id_menu = '$id_menu', metode_pembayaran = '$metode_pembayaran', status = '$status', jumlah_menu = '$jumlah_menu', total_harga = '$total_harga', waktu_awal = '$waktu_awal', waktu_akhir = '$waktu_akhir' WHERE id_reservasi = '$id_reservasi'";
        if (mysqli_query($conn, $sql)) {
            echo "Reservasi berhasil diperbarui!";
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
    <title>Kelola Reservasi</title>
    <link rel="stylesheet" href="kelola_reservasi.css">
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
        
        <h2>Kelola Reservasi</h2>
        <form method="POST" action="">
            <input type="hidden" name="id_reservasi" id="id_reservasi">
            <label for="id_pengguna">ID Pengguna:</label>
            <input type="number" name="id_pengguna" id="id_pengguna" required>

            <label for="id_meja">ID Meja:</label>
            <select id="id_meja" name="id_meja" required>
                <?php
                while ($row_meja = mysqli_fetch_assoc($result_meja)) {
                    echo '<option value="' . $row_meja['id_meja'] . '">Meja ' . $row_meja['nomor_meja'] . ' (Kapasitas: ' . $row_meja['kapasitas'] . ' orang)</option>';
                }
                ?>
            </select>

            <label for="tanggal">Tanggal:</label>
            <input type="date" name="tanggal" id="tanggal" required>

            <label for="id_waktu">ID Waktu:</label>
            <select id="id_waktu" name="id_waktu" required>
                <?php
                while ($row_waktu = mysqli_fetch_assoc($result_waktu)) {
                    echo '<option value="' . $row_waktu['id_waktu'] . '">' . $row_waktu['waktu_mulai'] . '</option>';
                }
                ?>
            </select>

            <label for="jumlah_orang">Jumlah Orang:</label>
            <input type="number" name="jumlah_orang" id="jumlah_orang" required>

            <label for="id_menu">Nama Menu:</label>
            <select id="id_menu" name="id_menu" required>
                <?php
                while ($row_menu = mysqli_fetch_assoc($result_menu)) {
                    echo '<option value="' . $row_menu['id_menu'] . '">' . $row_menu['nama_menu'] . '</option>';
                }
                ?>
            </select>

            <label for="metode_pembayaran">Metode Pembayaran:</label>
            <select id="metode_pembayaran" name="metode_pembayaran" required>
                <?php
                while ($row_pembayaran = mysqli_fetch_assoc($result_pembayaran)) {
                    echo '<option value="' . $row_pembayaran['nama_metode'] . '">' . $row_pembayaran['nama_metode'] . '</option>';
                }
                ?>
            </select>

            <label for="status">Status:</label>
            <input type="text" name="status" id="status" required>

            <label for="jumlah_menu">Jumlah Menu:</label>
            <input type="number" name="jumlah_menu" id="jumlah_menu" required>

            <label for="total_harga">Total Harga:</label>
            <input type="number" step="0.01" name="total_harga" id="total_harga" required>

            <label for="waktu_akhir">Waktu Akhir:</label>
            <input type="time" name="waktu_akhir" id="waktu_akhir" required readonly>

            <button type="submit" name="tambah">Tambah</button>
        </form>

        <h2>Daftar Reservasi</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Reservasi</th>
                    <th>ID Pengguna</th>
                    <th>ID Meja</th>
                    <th>Tanggal</th>
                    <th>ID Waktu</th>
                    <th>Waktu Akhir</th>
                    <th>Jumlah Orang</th>
                    <th>Nama Menu</th>
                    <th>Metode Pembayaran</th>
                    <th>Status</th>
                    <th>Jumlah Menu</th>
                    <th>Total Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT r.*, m.nama_menu 
                        FROM reservasi r
                        JOIN menu m ON r.id_menu = m.id_menu";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_reservasi']}</td>";
                    echo "<td>{$row['id_pengguna']}</td>";
                    echo "<td>{$row['id_meja']}</td>";
                    echo "<td>{$row['tanggal']}</td>";
                    echo "<td>{$row['id_waktu']}</td>";
                    echo "<td>{$row['waktu_akhir']}</td>";
                    echo "<td>{$row['jumlah_orang']}</td>";
                    echo "<td>{$row['nama_menu']}</td>"; // Menampilkan nama menu
                    echo "<td>{$row['metode_pembayaran']}</td>";
                    echo "<td>{$row['status']}</td>";
                    echo "<td>{$row['jumlah_menu']}</td>";
                    echo "<td>{$row['total_harga']}</td>";
                    echo "<td>
                            <button type='button' onclick=\"editReservasi('{$row['id_reservasi']}', '{$row['id_pengguna']}', '{$row['id_meja']}', '{$row['tanggal']}', '{$row['id_waktu']}', '{$row['waktu_akhir']}', '{$row['jumlah_orang']}', '{$row['id_menu']}', '{$row['metode_pembayaran']}', '{$row['status']}', '{$row['jumlah_menu']}', '{$row['total_harga']}')\">Edit</button>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='id_reservasi' value='{$row['id_reservasi']}'>
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
        function editReservasi(id_reservasi, id_pengguna, id_meja, tanggal, id_waktu, waktu_awal, waktu_akhir, jumlah_orang, id_menu, metode_pembayaran, status, jumlah_menu, total_harga) {
            document.getElementById('id_reservasi').value = id_reservasi;
            document.getElementById('id_pengguna').value = id_pengguna;
            document.getElementById('id_meja').value = id_meja;
            document.getElementById('tanggal').value = tanggal;
            document.getElementById('id_waktu').value = id_waktu;
            document.getElementById('waktu_awal').value = waktu_awal;
            document.getElementById('waktu_akhir').value = waktu_akhir;
            document.getElementById('jumlah_orang').value = jumlah_orang;
            document.getElementById('id_menu').value = id_menu;
            document.getElementById('metode_pembayaran').value = metode_pembayaran;
            document.getElementById('status').value = status;
            document.getElementById('jumlah_menu').value = jumlah_menu;
            document.getElementById('total_harga').value = total_harga;
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
