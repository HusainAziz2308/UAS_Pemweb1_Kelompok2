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

    $query = mysqli_query($conn, "SELECT * FROM tb_admin WHERE username='$username'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        if (password_verify($password, $data['password'])) {
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<style>
    body {
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        background-color: #0080ffff;
        /*background-image: url('../assets/img/background.jpg');*/
        background-size: cover;
        background-position: center;
    }
    .card-body {
        display: block;
        align-content: space-between;
        margin-bottom: 15px;

        align-content: center;
        background-color: #ffffffff;
        padding: 50px;
        margin: 100px;
        border-radius: 15px;
    }

    .card-body h3 {
        text-align: center;
        margin-bottom: 20px;
    }
    .form-login {
        
    }
    
</style>
<body class="bg-light">

<div class="container mt-5">
    <div class="col-md-4 offset-md-4">
        <div class="card shadow">
            <div class="card-body">
                <h3>Login Admin</h3>
                <?php if ($pesan != "") { ?>
                    <div class="alert alert-danger"><?= $pesan ?></div>
                <?php } ?>

                <form method="POST">
                    <div class="form-login">
                        <label>Username</label>
                        <br>
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="form-login">
                        <label>Password</label>
                        <br>
                        <input type="password" name="password" class="form-control" placeholder="password" required>
                    </div>

                    <button class="btn btn-primary w-100" name="login">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
