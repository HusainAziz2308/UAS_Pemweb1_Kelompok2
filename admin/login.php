<?php
session_start();
require "../config/koneksi.php";

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
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<style>
    body {
        /*background-color: #0080ffff;*/
        background-image: url('../assets/img/bg_kopi_1.jpg');
        background-size: cover;
        background-position: center;
        backdrop-filter: brightness(60%);
        background-blend-mode: darken;

        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .card-body {
        display: block;
        max-width: 320px;
        width: 100%;
        align-content: space-between;
        margin-bottom: 15px;
        align-content: center;
        padding: 20px 40px 40px 40px;
        border-radius: 15px;

        border: 2px solid #ffffffaa;
        background: transparent;
        backdrop-filter: blur(25px);
        box-shadow: 0 10px 20px #00000055;
    }

    .card-body h1 {
        color: #fff;
        text-align: center;
        margin-bottom: 30px;
        border-bottom: 2px solid #ffffffff;
        padding-bottom: 20px;
    }

    .form-login input {
        font-size: medium;
        background: transparent;
        border: 3px solid #ffffffff;
        border-radius: 40px;
        padding: 20px 45px 20px 20px;
        width: 100%;
        
        color: #fff;
        outline: none;
        box-sizing: border-box;
        margin-bottom: 25px;
    }

    .form-login input::placeholder {
        font-size: medium;
        color: #ffffffce;
    }

    .card-body form {
        display: block;
        align-content: space-between;
        justify-content: center;
    }

    .tb-login {
        font-weight: 600;
        width: 100%;
        background-color: #eb7500ff;
        color: #fff;
        border: none;
        padding: 15px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 16px;
        
    }

    .tb-login:hover {
        scale: 1.04;
        background-color: #964B00;
        box-shadow: 1 8px 8px #ffffff55;
        transition: 0.3s;
    }

    .alert-login {
        margin-top: 15px;
        padding: 10px;
        background-color: #ffdddd;
        border-left: 6px solid #f44336;
        border-radius: 5px;
        color: #a94442;
    }
</style>

<body class="bg-light">
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