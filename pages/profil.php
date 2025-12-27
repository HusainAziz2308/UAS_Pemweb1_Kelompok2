<?php
session_start();
require "middleware_user.php";
require "../admin/config/koneksi.php";


if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$pesan = "";

$stmt = $koneksi->prepare("SELECT * FROM tb_user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (isset($_POST['update'])) {
    $nama  = $_POST['nama'];
    $email = $_POST['email'];

    $stmt = $koneksi->prepare("
        UPDATE tb_user 
        SET nama = ?, email = ?
        WHERE username = ?
    ");
    $stmt->bind_param("sss", $nama, $email, $username);
    $stmt->execute();
    $stmt->close();

    $_SESSION['nama_user'] = $nama;
    $pesan = "Profil berhasil diperbarui!";
}
if (isset($_POST['update_foto'])) {
    if ($_FILES['foto']['error'] !== 4) {
        $extValid = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $extValid)) {
            $namaFoto = uniqid() . "." . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], "assets/img/user/" . $namaFoto);

            $stmt = $koneksi->prepare("UPDATE tb_user SET foto=? WHERE username=?");
            $stmt->bind_param("ss", $namaFoto, $username);
            $stmt->execute();
            $stmt->close();
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
    <title>Profil User | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">
</head>

<body>
    <aside class="sidebar">
        <h2>Ruang Kopi</h2>

        <div class="user-info">
            <p>👋 Halo,</p>
            <strong>
                <?= htmlspecialchars($user['nama']); ?>
            </strong>
        </div>

        <nav>
            <a href="dashboard-user.php">Dashboard</a>
            <a href="profil.php">Profil</a>
            <a href="pesanan-saya.php">Pesanan</a>
            <a href="ganti-password.php">Ganti Password</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <div class="content-card">
            <h1>Profil Saya</h1>

            <?php if ($pesan): ?>
            <div class="alert-login">
                <?= $pesan ?>
            </div>
            <?php endif; ?>
            <img src="assets/img/user/<?= $user['foto'] ?? 'default.png' ?>" width="80">
            <form method="POST">
                <div class="form-login">
                    <input type="file" name="foto" accept="image/*">
                    <button name="update_foto">Update Foto</button>
                </div>

                <div class="form-login">
                    <input type="text" value="<?= htmlspecialchars($user['username']); ?>" readonly>
                </div>

                <div class="form-login">
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-login">
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required>
                </div>

                <button class="tb-login" name="update">
                    Update Profil
                </button>
            </form>

            <div class="aksi">
                <a href="dashboard-user.php">Dashboard</a> |
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </main>
</body>

</html>