<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: menu-kopi.php");
    exit();
}

$id_kopi = (int) $_GET['id'];
$username = $_SESSION['user'];

$stmt = $koneksi->prepare("SELECT * FROM tb_kopi WHERE id_kopi = ?");
$stmt->bind_param("i", $id_kopi);
$stmt->execute();
$kopi = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$kopi) {
    echo "Menu kopi tidak ditemukan";
    exit();
}

if (isset($_POST['pesan'])) {
    $jumlah = (int) $_POST['jumlah'];
    if ($jumlah < 1) $jumlah = 1;

    $total = $jumlah * $kopi['harga'];

    $stmt = $koneksi->prepare("
        INSERT INTO tb_pesanan (username, tanggal, total_harga, status)
        VALUES (?, NOW(), ?, 'pending')
    ");
    $stmt->bind_param("si", $username, $total);
    $stmt->execute();
    $id_pesanan = $stmt->insert_id;
    $stmt->close();

    $stmt = $koneksi->prepare("
        INSERT INTO tb_pesanan_detail (id_pesanan, id_kopi, jumlah, harga)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiii", $id_pesanan, $id_kopi, $jumlah, $kopi['harga']);
    $stmt->execute();
    $stmt->close();

    header("Location: pesanan-saya.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Pesan Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>
<body>

<?php include "../partials/sidebar.php"; ?>

<div class="main-content">
    <h1>Pesan Kopi</h1>

    <img src="../admin/assets/img/<?= $kopi['gambar']; ?>" width="200">
    <h3><?= htmlspecialchars($kopi['nama_kopi']); ?></h3>
    <p>Harga: Rp <?= number_format($kopi['harga']); ?></p>

    <form method="POST">
        <label>Jumlah</label>
        <input type="number" name="jumlah" value="1" min="1" required>

        <button type="submit" name="pesan">Pesan Sekarang</button>
    </form>
</div>

</body>
</html>
