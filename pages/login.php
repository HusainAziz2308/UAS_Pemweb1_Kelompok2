<?php
session_start();
require "../admin/config/koneksi.php";

if (isset($_SESSION['user'])) {
    header("Location: profil.php");
    exit();
}

$pesan = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT * FROM tb_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            $_SESSION['nama_user'] = $user['nama'];
            $_SESSION['email_user'] = $user['email'];

            header("Location: profil.php");
            exit();
        } else {
            $pesan = "Password salah!";
        }
    } else {
        $pesan = "Username tidak ditemukan!";
    }
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login User | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <style>
        body {
            /*background-color: #0080ffff;*/
            background-image: url('../admin/assets/img/bg_kopi_1.jpg');
            background-size: cover;
            background-position: center;
            backdrop-filter: brightness(60%);
            background-blend-mode: darken;

            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        img {
            max-width: 400px;
        }
    </style>
</head>

<body>
    <img src="../admin/assets/img/logo1.png" alt="logo">

    <div class="card-body">
        <h1>Login User</h1>

        <form method="POST">
            <div class="form-login">
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="form-login">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button class="tb-login" name="login">Login</button>

            <?php if ($pesan): ?>
                <div class="alert-login"><?= $pesan ?></div>
            <?php endif; ?>
        </form>

        <p class="tanya">
            Belum punya akun?
            <a class="jawab" href="register.php">Daftar</a>
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