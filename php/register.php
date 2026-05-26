<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama_pengguna'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO pengguna (nama_pengguna, email, password) VALUES ('$nama', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        // Jika registrasi berhasil, arahkan ke halaman beranda
        header('Location: ../index.php');
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Registrasi Pengguna</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .register-container h2 {
            margin: 0 0 20px;
            text-align: center;
        }
        .register-container input {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .register-container button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background-color: #A0522D;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .register-container button:hover {
            background-color: #8B4513;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Registrasi Pengguna</h2>
        <form method="post" action="">
            <input type="text" name="nama_pengguna" placeholder="Nama" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Registrasi</button>
        </form>
    </div>
</body>
</html>
