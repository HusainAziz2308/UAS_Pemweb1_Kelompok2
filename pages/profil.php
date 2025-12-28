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
        $pesan = "Nama dan email wajib diisi!";
    } else {
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
</head>

<body>
    <?php include "../partials/sidebar.php"; ?>
    <h2>Profil Saya</h2>
    <p>Halo <?= $_SESSION['nama_user']; ?>! </p>
    <?php if ($pesan): ?>
        <p><?= htmlspecialchars($pesan); ?></p>
    <?php endif; ?>

    <form method="POST">
        <p>
            <label>Username</label><br>
            <input type="text" value="<?= htmlspecialchars($user['username']); ?>" readonly>
        </p>

        <p>
            <label>Email</label><br>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </p>

        <p>
            <label>Nama Lengkap</label><br>
            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required>
        </p>

        <button type="submit" name="update">Update Profil</button>
    </form>
    <hr>

    <a href="logout.php">Logout</a>

</body>

</html>