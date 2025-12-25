<?php
session_start();
require "config/koneksi.php";

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

$pesan = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM tb_admin WHERE username = ?");
    
    if ($stmt === false) {
        $pesan = "Terjadi kesalahan database: " . $koneksi->error;
    } else {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();

        if ($data) {
            if ($password === $data['password']) {

                $_SESSION['admin'] = $data['username'];
                $_SESSION['nama_admin'] = $data['nama_admin'];

                header("Location: dashboard.php");
                exit();
            } else {
                $pesan = "Password salah!";
            }
        } else {
            $pesan = "Username tidak ditemukan!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="shortcut icon" href="assets/icon/favicon1.png" type="image/png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>

<body class="bg-light">
    <img src="/assets/img/logo1.png" alt="logo">
    <div class="card-body">
        <h1>Login Admin</h1>
        <form method="POST">
            <div class="form-login">

                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="form-login">

                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button class="tb-login" name="login">Login</button>

            <?php if ($pesan != "") { ?>
                <div class="alert-login"><?= $pesan ?></div>
            <?php } ?>
        </form>
    </div>
</body>
</html>