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
        background-color: #0080ffff;
        /*background-image: url('../assets/img/background.jpg');*/
        background-size: cover;
        background-position: center;

        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .container.mt-5 {
        max-width: 400px;
        width: 100%;
    }

    .card-body {
        display: block;
        align-content: space-between;
        margin-bottom: 15px;

        align-content: center;
        background-color: #ffffffff;
        padding: 50px;
        border-radius: 15px;
    }

    .card-body h1 {
        text-align: center;
        margin-bottom: 30px;
    }

    .form-login label {
        font-weight: bold;
        text-align: left;
        display: block;
        margin-bottom: 5px;
    }

    .form-login input {
        border: 3px solid #acacacff;
        border-radius: 25px;
        padding: 15px;
        width: 100%;
        box-sizing: border-box;
        margin-bottom: 15px;
    }

    .card-body form {
        display: block;
        align-content: space-between;
        justify-content: center;
    }

    .tb-login {
        width: 100%;
        background-color: #ff0000ff;
        color: #fff;
        border: none;
        padding: 10px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 16px;
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
    <div class="container mt-5">
        <div class="col-md-4 offset-md-4">
            <div class="card shadow">
                <div class="card-body">
                    <h1>Login Admin</h1>
                    <form method="POST">
                        <div class="form-login">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" placeholder="username" required>
                        </div>
                        <div class="form-login">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="password" required>
                        </div>

                        <button class="tb-login" name="login">Login</button>

                        <?php if ($pesan != "") { ?>
                            <div class="alert-login"><?= $pesan ?></div>
                        <?php } ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>