<?php
include '../config.php'; // Pastikan jalur menuju file config benar

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah'])) {
        $nama_pengguna = $_POST['nama_pengguna'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO pengguna (nama_pengguna, email, password) VALUES ('$nama_pengguna', '$email', '$password')";
        if (mysqli_query($conn, $sql)) {
            echo "Pengguna berhasil ditambahkan!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['hapus'])) {
        $id_pengguna = $_POST['id_pengguna'];
        $sql = "DELETE FROM pengguna WHERE id_pengguna = '$id_pengguna'";
        if (mysqli_query($conn, $sql)) {
            echo "Pengguna berhasil dihapus!";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } elseif (isset($_POST['edit'])) {
        $id_pengguna = $_POST['id_pengguna'];
        $nama_pengguna = $_POST['nama_pengguna'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE pengguna SET nama_pengguna = '$nama_pengguna', email = '$email', password = '$password' WHERE id_pengguna = '$id_pengguna'";
        if (mysqli_query($conn, $sql)) {
            echo "Pengguna berhasil diperbarui!";
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
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="kelola_pengguna.css">
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
        
        <h2>Kelola Pengguna</h2>
        <form method="POST" action="">
            <input type="hidden" name="id_pengguna" id="id_pengguna">
            <label for="nama_pengguna">Nama Pengguna:</label>
            <input type="text" name="nama_pengguna" id="nama_pengguna" required>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit" name="tambah">Tambah</button>
        </form>

        <h2>Daftar Pengguna</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM pengguna";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['id_pengguna']}</td>";
                    echo "<td>{$row['nama_pengguna']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>**********</td>"; // Jangan menampilkan password yang sebenarnya
                    echo "<td>
                            <button type='button' onclick=\"editPengguna('{$row['id_pengguna']}', '{$row['nama_pengguna']}', '{$row['email']}', '')\">Edit</button>
                            <form method='POST' action='' style='display:inline;'>
                                <input type='hidden' name='id_pengguna' value='{$row['id_pengguna']}'>
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
        function editPengguna(id, nama_pengguna, email, password) {
            document.getElementById('id_pengguna').value = id;
            document.getElementById('nama_pengguna').value = nama_pengguna;
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>

<?php
mysqli_close($conn);
?>
