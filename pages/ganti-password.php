<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$pesan = "";
$error = "";

if (isset($_POST['ubah'])) {
    $password_lama = trim($_POST['password_lama']);
    $password_baru = trim($_POST['password_baru']);
    $konfirmasi    = trim($_POST['konfirmasi']);

    // Ambil password lama dari database
    $stmt = $koneksi->prepare("SELECT password FROM tb_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    if (!$data) {
        $error = "User tidak ditemukan!";
    } elseif ($password_lama !== $data['password']) {
        $error = "Password lama salah!";
    } elseif ($password_baru === "") {
        $error = "Password baru tidak boleh kosong!";
    } elseif ($password_baru !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Update password
        $stmt = $koneksi->prepare("
            UPDATE tb_user 
            SET password = ? 
            WHERE username = ?
        ");
        $stmt->bind_param("ss", $password_baru, $username);
        $stmt->execute();
        $stmt->close();

        $pesan = "Password berhasil diperbarui!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Ganti Password</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>

<body>

    <div class="card-body">
        <h1>Ganti Password</h1>

        <?php if ($error): ?>
            <div class="alert-login"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($pesan): ?>
            <div class="alert-login" style="background:#ddffdd;border-left:6px solid #4CAF50;color:#2e7d32;">
                <?= $pesan ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-login">
                <input type="password" name="password_lama" placeholder="Password Lama" required>
            </div>

            <div class="form-login">
                <input type="password" name="password_baru" placeholder="Password Baru" required>
            </div>

            <div class="form-login">
                <input type="password" name="konfirmasi" placeholder="Konfirmasi Password Baru" required>
            </div>

            <button class="tb-login" name="ubah">Simpan Perubahan</button>
        </form>

        <div class="aksi">
            <a href="profil.php">← Kembali ke Profil</a>
        </div>
    </div>

</body>

</html>