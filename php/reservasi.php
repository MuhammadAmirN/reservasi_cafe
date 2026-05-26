<?php
include 'config.php';

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
    $nama_pengguna = mysqli_real_escape_string($conn, $_POST['nama_pengguna']);
    $id_meja = mysqli_real_escape_string($conn, $_POST['nomor_meja']);
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $id_waktu = mysqli_real_escape_string($conn, $_POST['waktu']);
    $jumlah_orang = mysqli_real_escape_string($conn, $_POST['jumlah_orang']);
    $nama_menu = isset($_POST['nama_menu']) ? $_POST['nama_menu'] : [];
    $jumlah_menu = isset($_POST['jumlah_menu']) ? $_POST['jumlah_menu'] : [];
    $metode_pembayaran = mysqli_real_escape_string($conn, $_POST['metode_pembayaran']);

    $total_harga = 0;
    $menu_list = [];
    $id_menu_list = [];

    // Menghitung total harga dari semua menu yang dipilih
    foreach ($nama_menu as $index => $menu) {
        $sql_harga = "SELECT harga, id_menu FROM menu WHERE nama_menu = '$menu'";
        $result_harga = mysqli_query($conn, $sql_harga);
        if (!$result_harga) {
            die("Query Error: " . mysqli_error($conn));
        }
        $row_harga = mysqli_fetch_assoc($result_harga);
        $harga_menu = $row_harga['harga'];
        $id_menu = $row_harga['id_menu'];

        $total_harga += $harga_menu * $jumlah_menu[$index];
        $menu_list[] = "$menu (Jumlah: $jumlah_menu[$index])";
        $id_menu_list[] = $id_menu;
    }

    $nama_menu_serialized = implode(", ", $id_menu_list);
    $jumlah_menu_serialized = implode(", ", $jumlah_menu);

    // Menghitung waktu akhir reservasi (batasan maksimal 3 jam)
    $sql_waktu_awal = "SELECT waktu_mulai FROM waktu_reservasi WHERE id_waktu = '$id_waktu'";
    $result_waktu_awal = mysqli_query($conn, $sql_waktu_awal);
    if ($result_waktu_awal) {
        $row_waktu_awal = mysqli_fetch_assoc($result_waktu_awal);
        $waktu_awal = $row_waktu_awal['waktu_mulai'];
        $waktu_akhir = date('H:i:s', strtotime($waktu_awal . ' + 3 hours'));
    } else {
        die("Query gagal: " . mysqli_error($conn));
    }

    $sql = "INSERT INTO reservasi (id_pengguna, id_meja, tanggal, id_waktu, jumlah_orang, id_menu, jumlah_menu, total_harga, metode_pembayaran, status, waktu_akhir) 
            VALUES ('$nama_pengguna', '$id_meja', '$tanggal', '$id_waktu', '$jumlah_orang', '$nama_menu_serialized', '$jumlah_menu_serialized', '$total_harga', '$metode_pembayaran', 'pending', '$waktu_akhir')";

    if (mysqli_query($conn, $sql)) {
        $confirmation_message = "Reservasi berhasil disimpan dengan total harga: Rp " . number_format($total_harga, 0, ',', '.') . "!";
        $confirmation_message .= "<br>Nomor Meja: " . $id_meja;
        $confirmation_message .= "<br>Batas Waktu: " . $waktu_awal . " - " . $waktu_akhir;
        $confirmation_message .= "<br>Nama Pemesan: " . $nama_pengguna;
        header("Location: konfirmasi.php?message=" . urlencode($confirmation_message));
        exit();
    } else {
        $confirmation_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
        echo "<script>alert('$confirmation_message');</script>";
    }
}
?>
<!DOCTYPE html> 
<html lang="en"> 
    <head> <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Reservations - Daams Cafe</title> 
    <link rel="stylesheet" href="reservasi.css"> 
    <script> 
    function addMenu() {
            var menuSelect = document.getElementById('nama_menu');
            var jumlahInput = document.getElementById('jumlah_menu');
            var selectedMenus = document.getElementById('selected_menus');

            var menu = menuSelect.options[menuSelect.selectedIndex].text;
            var jumlah = jumlahInput.value;

            if (menu && jumlah) {
                var listItem = document.createElement('li');
                listItem.textContent = menu + ' (Jumlah: ' + jumlah + ') ';
                
                var deleteButton = document.createElement('button');
                deleteButton.textContent = 'Hapus';
                deleteButton.onclick = function() {
                    selectedMenus.removeChild(listItem);
                };

                listItem.appendChild(deleteButton);
                selectedMenus.appendChild(listItem);

                menuSelect.value = '';
                jumlahInput.value = '';
            }
        }
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="../index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="reservasi.php">Reservations</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </nav>
    </header>

    <div class="reservations-container">
        <h2>Reservasi</h2>
        <form method="post" action="">
            <label for="nama_pengguna">Nama Pemesan:</label>
            <input type="text" id="nama_pengguna" name="nama_pengguna" required>

            <label for="nomor_meja">Nomor Meja:</label>
            <select id="nomor_meja" name="nomor_meja" required>
                <?php
                while ($row_meja = mysqli_fetch_assoc($result_meja)) {
                    echo '<option value="' . $row_meja['id_meja'] . '">Meja ' . $row_meja['nomor_meja'] . ' (Kapasitas: ' . $row_meja['kapasitas'] . ' orang)</option>';
                }
                ?>
            </select>

            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" required>

            <label for="waktu">Waktu:</label>
            <select id="waktu" name="waktu" required>
                <?php
                while ($row_waktu = mysqli_fetch_assoc($result_waktu)) {
                    echo '<option value="' . $row_waktu['id_waktu'] . '">' . $row_waktu['waktu_mulai'] . '</option>';
                }
                ?>
            </select>

            <label for="jumlah_orang">Jumlah Orang:</label>
            <input type="number" id="jumlah_orang" name="jumlah_orang" required>

            <label for="nama_menu">Nama Menu:</label>
            <select id="nama_menu" name="nama_menu[]" multiple required>
                <?php
                while ($row_menu = mysqli_fetch_assoc($result_menu)) {
                    echo '<option value="' . $row_menu['nama_menu'] . '">' . $row_menu['nama_menu'] . '</option>';
                }
                ?>
            </select>

            <label for="jumlah_menu">Jumlah Menu:</label>
            <input type="number" id="jumlah_menu" name="jumlah_menu[]" required>

            <button type="button" onclick="addMenu()">Tambah Menu</button>
            
            <ul id="selected_menus"></ul>

            <label for="metode_pembayaran">Metode Pembayaran:</label>
            <select id="metode_pembayaran" name="metode_pembayaran" required>
                <?php
                while ($row_pembayaran = mysqli_fetch_assoc($result_pembayaran)) {
                    echo '<option value="' . $row_pembayaran['nama_metode'] . '">' . $row_pembayaran['nama_metode'] . '</option>';
                }
                ?>
            </select>

            <button type="submit">Reservasi</button>
        </form>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
