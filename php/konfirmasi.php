<?php
$message = isset($_GET['message']) ? urldecode($_GET['message']) : 'Konfirmasi tidak tersedia.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Reservasi - Daams Cafe</title>
    <link rel="stylesheet" href="reservasi.css">
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
        <h2>Reservasi Berhasil</h2>
        <div class="confirmation">
            <?php echo $message; ?>
        </div>
        <a href="reservasi.php">Kembali ke Halaman Reservasi</a>
    </div>
</body>
</html>
