<?php
session_start();
require "../admin/config/koneksi.php";

$pesan = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $nama     = trim($_POST['nama']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $cekUser = $koneksi->prepare("SELECT username FROM tb_user WHERE username = ?");
    $cekUser->bind_param("s", $username);
    $cekUser->execute();
    $cekUser->store_result();

    if ($cekUser->num_rows > 0) {
        $pesan = "Username sudah digunakan!";
    } else {
        $cekEmail = $koneksi->prepare("SELECT email FROM tb_user WHERE email = ?");
        $cekEmail->bind_param("s", $email);
        $cekEmail->execute();
        $cekEmail->store_result();

        if ($cekEmail->num_rows > 0) {
            $pesan = "Email sudah digunakan!";
        } else {
            $stmt = $koneksi->prepare("
                INSERT INTO tb_user (username, email, nama, password)
                VALUES (?, ?, ?, ?)
            ");
            $stmt->bind_param("ssss", $username, $email, $nama, $password);
            $stmt->execute();
            $stmt->close();

            header("Location: login.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Register User | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/login.css">
</head>

<body>
    <img src="../admin/assets/img/logo1.png" alt="logo">

    <div class="card-body">
        <h1>Register User</h1>

        <form method="POST">
            <div class="form-login">
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="form-login">
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="form-login">
                <input type="text" name="nama" placeholder="Nama Lengkap" required>
            </div>

            <div class="form-login">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button class="tb-login" name="register">Daftar</button>

            <?php if ($pesan): ?>
                <div class="alert-login">
                    <?= $pesan ?>
                </div>
            <?php endif; ?>
        </form>

        <p class="tanya">
            Sudah punya akun?
            <a class="jawab" href="login.php">Login</a>
        </p>

        <div class="google-login">
            <img src="../admin/assets/icon/google.png" alt="google">
            <a href="login_google.php" id="google">
                Login dengan Google
            </a>
        </div>
    </div>
</body>

</html>