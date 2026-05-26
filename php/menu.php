<?php
include 'config.php';

$menu_items = [];
$nama_pengguna = isset($_GET['nama_pengguna']) ? $_GET['nama_pengguna'] : '';

// Mendapatkan data dari tabel menu
$sql = "SELECT * FROM menu";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pengguna = $_POST['nama_pengguna'];
    $tanggal = date("Y-m-d");
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $menu_items = isset($_POST['menu']) ? json_decode($_POST['menu'], true) : [];

    if (empty($menu_items)) {
        die("Data menu tidak terkirim atau format JSON salah.");
    }

    // Mendapatkan ID pengguna berdasarkan nama pengguna
    $sql_pengguna = "SELECT id_pengguna FROM pengguna WHERE nama_pengguna = '$nama_pengguna'";
    $result_pengguna = mysqli_query($conn, $sql_pengguna);

    if (!$result_pengguna) {
        die("Query gagal: " . mysqli_error($conn));
    }

    $row_pengguna = mysqli_fetch_assoc($result_pengguna);
    if (!$row_pengguna) {
        die("Pengguna tidak ditemukan.");
    }

    $id_pengguna = $row_pengguna['id_pengguna'];
    $total_harga = 0;
    $pemesanan_ids = [];

// Memasukkan data pemesanan ke tabel pemesanan
$stmt = $conn->prepare("INSERT INTO pemesanan (id_menu, id_pengguna, jumlah, tanggal, id_metode, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
$update_stok_sql = "UPDATE menu SET stok_masuk = stok_masuk - ? WHERE id_menu = ?";
$stmt_update_stok = $conn->prepare($update_stok_sql);

foreach ($menu_items as $menu_item) {
    $id_menu = $menu_item['id_menu'];
    $jumlah = $menu_item['jumlah'];
    $harga = $menu_item['harga'];

    // Validasi stok masuk per item menu
    $sql_stok = "SELECT stok_masuk, nama_menu FROM menu WHERE id_menu = ?";
    $stmt_stok = $conn->prepare($sql_stok);
    $stmt_stok->bind_param("i", $id_menu);
    $stmt_stok->execute();
    $result_stok = $stmt_stok->get_result();

    if ($result_stok->num_rows > 0) {
        $row_stok = $result_stok->fetch_assoc();
        $stok_masuk = $row_stok['stok_masuk'];
        $nama_menu = $row_stok['nama_menu'];

        // Periksa apakah stok mencukupi
        if ($stok_masuk < $jumlah) {
            die("Stok tidak mencukupi untuk menu: " . $nama_menu);
        }
    } else {
        die("Menu tidak ditemukan.");
    }

    // Mendapatkan ID metode pembayaran
    $sql_pembayaran = "SELECT id_metode FROM metode_pembayaran WHERE nama_metode = ?";
    $stmt_pembayaran = $conn->prepare($sql_pembayaran);
    $stmt_pembayaran->bind_param("s", $metode_pembayaran);
    $stmt_pembayaran->execute();
    $result_pembayaran = $stmt_pembayaran->get_result();

    if ($result_pembayaran->num_rows > 0) {
        $row_pembayaran = $result_pembayaran->fetch_assoc();
        $id_metode = $row_pembayaran['id_metode'];
    } else {
        die("Metode pembayaran tidak ditemukan.");
    }

    // Hitung total harga untuk item
    $total_harga_item = $harga * $jumlah;
    $total_harga += $total_harga_item;

    // Simpan ke tabel pemesanan
    $stmt->bind_param("iiisis", $id_menu, $id_pengguna, $jumlah, $tanggal, $id_metode, $total_harga_item);
    if (!$stmt->execute()) {
        die("Gagal menyimpan pesanan: " . $stmt->error);
    }

    // Kurangi stok masuk untuk setiap item menu
    $stmt_update_stok->bind_param("ii", $jumlah, $id_menu);
    if (!$stmt_update_stok->execute()) {
        die("Gagal memperbarui stok: " . $stmt_update_stok->error);
    }

    // Mengumpulkan ID pemesanan
    $pemesanan_ids[] = $stmt->insert_id;
}

    

    // Redirect ke halaman konfirmasi
    $confirmation_message = "Pesanan berhasil disimpan dengan total harga: Rp " . number_format($total_harga, 0, ',', '.') . "!";
    header("Location: konfirmasi_pemesanan.php?message=" . urlencode($confirmation_message) . "&nama_pengguna=" . urlencode($nama_pengguna) . "&pemesanan_ids=" . urlencode(implode(',', $pemesanan_ids)));
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu & Order - Daams Cafe</title>
    <link rel="stylesheet" href="menu.css?v=1.0">
    <style>
        .menu-controls {
            display: flex;
            align-items: center;
        }
        .menu-controls > div {
            margin-right: 10px;
        }
        .menu-controls select {
            min-width: 150px;
        }
        .menu-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 10px;
        }
        .menu-image-item {
            margin: 10px;
            text-align: center;
        }
        .menu-image-item img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
        }
        .order-item img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="order-container">
        <h2>Menu & Pesanan</h2>
        <p>Selamat datang, <?php echo htmlspecialchars($nama_pengguna, ENT_QUOTES, 'UTF-8'); ?>!</p>
        <div class="order-content">
            <div class="menu-selection">
                <h3>Pilih Menu</h3>
                <div class="menu-controls">
                    <div>
                        <select id="nama_menu" name="nama_menu">
                            <option value="" data-gambar="" data-harga="" data-stok="">Pilih Menu</option>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <option value="<?php echo $row['id_menu']; ?>" data-gambar="<?php echo $row['gambar']; ?>" data-harga="<?php echo $row['harga']; ?>" data-stok="<?php echo $row['stok_masuk']; ?>"><?php echo $row['nama_menu']; ?> (Stok: <?php echo $row['stok_masuk']; ?>)</option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label for="jumlah_menu">Jumlah:</label>
                        <input type="number" id="jumlah_menu" name="jumlah_menu" min="1">
                    </div>
                    <button type="button" onclick="addMenu()">Tambah Menu</button>
                </div>
                <div class="menu-images">
                    <?php mysqli_data_seek($result, 0); while ($row = mysqli_fetch_assoc($result)): ?>
                        <div class="menu-image-item">
                            <img src="../images/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama_menu']; ?>">
                            <p><?php echo $row['nama_menu']; ?></p>
                            <p>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                            <p>Stok: <?php echo $row['stok_masuk']; ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
                <ul id="selected_menus"></ul>
            </div>
            <div class="order-form">
                <h3>Daftar Pesanan</h3>
                <div class="order-list"></div>
                <form method="post" action="">
                    <input type="hidden" name="menu" id="menu-input">
                    <label for="nama_pengguna">Nama Pengguna:</label>
                    <input type="text" id="nama_pengguna" name="nama_pengguna" value="<?php echo htmlspecialchars($nama_pengguna, ENT_QUOTES, 'UTF-8'); ?>" required>

                    <label for="metode_pembayaran">Metode Pembayaran:</label>
                    <select id="metode_pembayaran" name="metode_pembayaran" required>
                        <?php
                        $sql_pembayaran = "SELECT * FROM metode_pembayaran";
                        $result_pembayaran = mysqli_query($conn, $sql_pembayaran);
                        if (!$result_pembayaran) {
                            die("Query gagal: " . mysqli_error($conn));
                        }
                        while ($row_pembayaran = mysqli_fetch_assoc($result_pembayaran)) {
                            echo '<option value="' . htmlspecialchars($row_pembayaran['nama_metode'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row_pembayaran['nama_metode'], ENT_QUOTES, 'UTF-8') . '</option>';
                        }

                    ?>
                </select>

                <button type="submit">Simpan Pesanan</button>
            </form>
        </div>
    </div>
</div>

<script>
    const selectedMenus = [];
    const menuInput = document.getElementById('menu-input');
    const selectedMenusList = document.getElementById('selected_menus');
    const orderList = document.querySelector('.order-list');

    function addMenu() {
        const menuSelect = document.getElementById('nama_menu');
        const jumlahInput = document.getElementById('jumlah_menu');

        const selectedOption = menuSelect.options[menuSelect.selectedIndex];
        const idMenu = selectedOption.value;
        const namaMenu = selectedOption.text.split(" (")[0];
        const harga = parseFloat(selectedOption.getAttribute('data-harga'));
        const stok = parseInt(selectedOption.getAttribute('data-stok'));
        const gambar = selectedOption.getAttribute('data-gambar');
        const jumlah = parseInt(jumlahInput.value);

        if (!idMenu || isNaN(jumlah) || jumlah <= 0) {
            alert("Silakan pilih menu dan masukkan jumlah yang valid.");
            return;
        }

        if (jumlah > stok) {
            alert("Jumlah yang dimasukkan melebihi stok yang tersedia.");
            return;
        }

        const existingMenu = selectedMenus.find(menu => menu.id_menu === idMenu);
        if (existingMenu) {
            existingMenu.jumlah += jumlah;
        } else {
            selectedMenus.push({
                id_menu: idMenu,
                nama_menu: namaMenu,
                harga: harga,
                stok: stok,
                gambar: gambar,
                jumlah: jumlah
            });
        }

        updateOrderList();
    }

    function removeMenu(index) {
        selectedMenus.splice(index, 1);
        updateOrderList();
    }

    function updateOrderList() {
        menuInput.value = JSON.stringify(selectedMenus);
        selectedMenusList.innerHTML = '';
        orderList.innerHTML = '';

        selectedMenus.forEach((menu, index) => {
            const li = document.createElement('li');
            li.textContent = `${menu.nama_menu} (Jumlah: ${menu.jumlah}, Harga: Rp ${menu.harga * menu.jumlah})`;
            selectedMenusList.appendChild(li);

            const orderItem = document.createElement('div');
            orderItem.classList.add('order-item');
            orderItem.innerHTML = `
                <img src="../images/${menu.gambar}" alt="${menu.nama_menu}">
                <p>${menu.nama_menu}</p>
                <p>Jumlah: ${menu.jumlah}</p>
                <p>Total Harga: Rp ${menu.harga * menu.jumlah}</p>
                <button onclick="removeMenu(${index})">Hapus</button>
            `;
            orderList.appendChild(orderItem);
        });
    }
</script>
</body>
</html>
