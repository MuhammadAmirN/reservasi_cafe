<?php
include 'config.php';

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="products.css">
    <title>Products - Daams Cafe</title>
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

    <div class="products-container">
        <h2>Products</h2>
        <div class="products">
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <div class="product">
                    <p>ID Produk: <?php echo $row['id_products']; ?></p>
                    <a href="menu.php#<?php echo strtolower($row['kategori']); ?>">
                        <img src="../images/<?php echo $row['gambar']; ?>" alt="<?php echo $row['kategori']; ?>">
                        <h3><?php echo $row['kategori']; ?></h3>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>

<?php
mysqli_close($conn);
?>