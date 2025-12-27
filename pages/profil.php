<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];
$pesan = "";

/* Ambil data user */
$stmt = $koneksi->prepare("SELECT * FROM tb_user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

/* Update profil */
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

<div class="card-body">
    <h1>Profil Saya</h1>

    <?php if ($pesan): ?>
        <div class="alert-login"><?= $pesan ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-login">
            <input type="text" value="<?= htmlspecialchars($user['username']); ?>" readonly>
        </div>

        <div class="form-login">
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-login">
            <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required>
        </div>

        <button class="tb-login" name="update">Update Profil</button>
    </form>

    <div class="aksi">
        <a href="menu-kopi.php">← Kembali ke Menu</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
