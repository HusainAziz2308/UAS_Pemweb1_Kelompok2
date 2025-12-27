<?php
require "middleware_user.php";
require "../admin/config/koneksi.php";

$username = $_SESSION['user'];
$pesan = "";

if (isset($_POST['ubah'])) {
    $lama = $_POST['password_lama'];
    $baru = $_POST['password_baru'];
    $ulang = $_POST['password_ulang'];

    if ($baru !== $ulang) {
        $pesan = "Password baru tidak sama!";
    } else {
        $stmt = $koneksi->prepare("SELECT password FROM tb_user WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!password_verify($lama, $res['password'])) {
            $pesan = "Password lama salah!";
        } else {
            $hash = password_hash($baru, PASSWORD_DEFAULT);
            $stmt = $koneksi->prepare("UPDATE tb_user SET password=? WHERE username=?");
            $stmt->bind_param("ss", $hash, $username);
            $stmt->execute();
            $stmt->close();

            $pesan = "Password berhasil diubah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Ganti Password | Ruang Kopi</title>
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
    <div class="main-content">
        <div class="card">
            <h2>Ganti Password</h2>

            <?php if ($pesan): ?>
            <div class="alert-login">
                <?= $pesan ?>
            </div>
            <?php endif; ?>

            <form method="POST">
                <input type="password" name="password_lama" placeholder="Password Lama" required>
                <input type="password" name="password_baru" placeholder="Password Baru" required>
                <input type="password" name="password_ulang" placeholder="Ulangi Password Baru" required>
                <button name="ubah">Simpan</button>
            </form>
        </div>
    </div>
</body>
</html>