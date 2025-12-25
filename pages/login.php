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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Login User | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/login.css">
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