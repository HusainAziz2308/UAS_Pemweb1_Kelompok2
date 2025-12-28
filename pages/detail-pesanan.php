<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

if (!isset($_GET['id'])) {
    header("Location: pesanan-saya.php");
    exit();
}

$id_pesanan = (int) $_GET['id'];

$stmt = $koneksi->prepare("
    SELECT * FROM tb_pesanan
    WHERE id_pesanan = ? AND username = ?
");
$stmt->bind_param("is", $id_pesanan, $username);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pesanan) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

$stmt = $koneksi->prepare("
    SELECT
        d.jumlah,
        d.harga,
        k.nama_kopi
    FROM tb_pesanan_detail d
    JOIN tb_kopi k ON d.id_kopi = k.id_kopi
    WHERE d.id_pesanan = ?
");
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$detail = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Detail Pesanan</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>

<body>

    <?php include "../partials/sidebar.php"; ?>

    <div class="main-content">
        <h1>Detail Pesanan</h1>

        <p><b>Tanggal:</b> <?= date('d-m-Y', strtotime($pesanan['tanggal'])); ?></p>
        <p><b>Status:</b> <?= ucfirst($pesanan['status']); ?></p>

        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>

            <?php $no = 1; ?>
            <?php while ($row = $detail->fetch_assoc()): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['nama_kopi']); ?></td>
                    <td><?= $row['jumlah']; ?></td>
                    <td>Rp <?= number_format($row['harga']); ?></td>
                    <td>Rp <?= number_format($row['jumlah'] * $row['harga']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h3>Total: Rp <?= number_format($pesanan['total_harga']); ?></h3>

        <a href="pesanan-saya.php">← Kembali</a>
    </div>

</body>

</html>