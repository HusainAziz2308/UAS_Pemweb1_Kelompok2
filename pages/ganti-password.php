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
        $stmt = $koneksi->prepare("UPDATE tb_user SET password = ? WHERE username = ?");
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
    <title>Ganti Password | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">
</head>

<body>

    <aside class="sidebar">
        <h2>Ruang Kopi</h2>
        <div class="user-info">
            <p>👋 Halo,</p>
            <strong><?= htmlspecialchars($_SESSION['nama_user']); ?></strong>
        </div>
        <nav>
            <a href="menu-kopi.php">☕ Menu Utama</a>
            <a href="dashboard-user.php">📊 Dashboard</a>
            <a href="profil.php">👤 Profil</a>
            <a href="pesanan-saya.php">🧾 Pesanan Saya</a>
            <a href="ganti-password.php" class="active" style="background-color: #be9b7b; color: white;">🔐 Ganti Password</a>
            <hr style="border: 0; border-top: 1px solid #6b5048; margin: 10px 0;">
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
        </nav>
    </aside>

    <div class="main-content">
        <div class="container-profile">
            <h2>Ganti Password</h2>
            <p style="color: #666; margin-bottom: 20px;">Gunakan password yang kuat agar akunmu tetap aman.</p>

            <?php if ($error): ?>
                <div class="alert" style="background:#f8d7da; color:#721c24; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ⚠️ <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if ($pesan): ?>
                <div class="alert" style="background:#d4edda; color:#155724; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    ✅ <?= $pesan ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Password Lama</label>
                    <input type="password" name="password_lama" placeholder="Masukkan password saat ini" required>
                </div>

                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="password_baru" placeholder="Masukkan password baru" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="konfirmasi" placeholder="Ulangi password baru" required>
                </div>

                <button type="submit" name="ubah" style="background-color: #4b3832; color: white; padding: 12px 25px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-weight: bold; transition: 0.3s;">
                    Simpan Perubahan
                </button>
            </form>

            <div style="margin-top: 20px; text-align: center;">
                <a href="profil.php" style="color: #be9b7b; text-decoration: none; font-size: 14px;">← Kembali ke Profil</a>
            </div>
        </div>
    </div>

</body>

</html>