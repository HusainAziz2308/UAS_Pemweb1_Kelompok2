<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$pesan = "";

$stmt = $koneksi->prepare("SELECT username, nama, email FROM tb_user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (isset($_POST['update'])) {
    $nama  = trim($_POST['nama']);
    $email = trim($_POST['email']);

    if ($nama == "" || $email == "") {
        $pesan = "<div class='alert' style='background:#f8d7da; color:#721c24; border-color:#f5c6cb;'>Nama dan email wajib diisi!</div>";
    } else {
        $stmt = $koneksi->prepare("
            UPDATE tb_user 
            SET nama = ?, email = ?
            WHERE username = ?
        ");
        $stmt->bind_param("sss", $nama, $email, $username);
        
        if ($stmt->execute()) {
            $_SESSION['nama_user'] = $nama;
            $pesan = "<div class='alert'>Profil berhasil diperbarui!</div>";
            // Refresh data user agar form langsung terupdate
            $user['nama'] = $nama;
            $user['email'] = $email;
        } else {
            $pesan = "<div class='alert' style='background:#f8d7da; color:#721c24;'>Gagal update database.</div>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Profil Saya</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">
</head>

<body>
    
    <aside class="sidebar">
        <h2>Ruang Kopi</h2>
        <div class="user-info">
            <p>👋 Halo,</p>
            <strong>
                <?= htmlspecialchars($_SESSION['nama_user']); ?>
            </strong>
        </div>
        <nav>
            <a href="menu-kopi.php">☕ Menu Utama</a>
            <a href="dashboard-user.php">📊 Dashboard</a>
            <a href="profil.php" class="active">👤 Profil</a> <a href="pesanan-saya.php">🧾 Pesanan Saya</a>
            <a href="ganti-password.php">🔐 Ganti Password</a>
            <hr style="border: 0; border-top: 1px solid #6b5048; margin: 10px 0;">
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
        </nav>
    </aside>

    <div class="main-content">
        <div class="container-profile">
            <h2>Profil Saya</h2>
            
            <?php if ($pesan): ?>
                <?= $pesan; ?>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" value="<?= htmlspecialchars($user['username']); ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required>
                </div>

                <button type="submit" name="update">Update Profil</button>
            </form>
        </div>
    </div>

</body>
</html>