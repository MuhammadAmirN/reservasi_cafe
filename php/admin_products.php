<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include 'config.php';

// Menambahkan atau Mengedit Produk
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $kategori = $_POST['kategori'];
    $gambar = $_POST['gambar'];

    if (isset($_POST['tambah'])) {
        $sql = "INSERT INTO products (id_products, kategori, gambar) VALUES ('$id', '$kategori', '$gambar')";
    } elseif (isset($_POST['edit'])) {
        $sql = "UPDATE products SET kategori='$kategori', gambar='$gambar' WHERE id_products='$id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Product berhasil disimpan!";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Products</title>
    <link rel="stylesheet" href="admin_products.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="admin_products.php">Kelola Products</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="reservations.php">Reservations</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="title">Kelola Products</div>
        <div class="form-section">
            <form method="post" action="">
                <label for="id">ID Produk</label>
                <input type="text" id="id" name="id">

                <label for="kategori">Kategori</label>
                <input type="text" id="kategori" name="kategori">

                <label for="gambar">Nama File Gambar</label>
                <input type="text" id="gambar" name="gambar">

                <button type="submit" name="tambah">Tambah</button>
                <button type="submit" name="edit">Edit</button>
            </form>
        </div>
        <div class="list-section">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Nama File Gambar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id_products']; ?></td>
                            <td><?php echo $row['kategori']; ?></td>
                            <td><?php echo $row['gambar']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
mysqli_close($conn);
?>
