<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: pesanan.php");
    exit();
}

$id_pesanan = (int) $_GET['id'];

$stmt = $koneksi->prepare("
    SELECT *
    FROM tb_pesanan
    WHERE id_pesanan = ?
");
$stmt->bind_param("i", $id_pesanan);
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

if (isset($_POST['ubah_status'])) {
    $status = $_POST['status'];

    $stmt = $koneksi->prepare("
        UPDATE tb_pesanan
        SET status = ?
        WHERE id_pesanan = ?
    ");
    $stmt->bind_param("si", $status, $id_pesanan);
    $stmt->execute();
    $stmt->close();

    header("Location: detail-pesanan.php?id=" . $id_pesanan);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/icon/favicon1.png" type="image/png">
    <title>Detail Pesanan Admin</title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>

<body>

    <h2>Detail Pesanan #<?= $id_pesanan; ?></h2>

    <p><b>Username:</b> <?= htmlspecialchars($pesanan['username']); ?></p>
    <p><b>Tanggal:</b> <?= date('d M Y H:i', strtotime($pesanan['tanggal'])); ?></p>
    <p><b>Status:</b> <?= ucfirst($pesanan['status']); ?></p>
    <p><b>Total:</b> Rp <?= number_format($pesanan['total_harga'], 0, ',', '.'); ?></p>

    <hr>

    <h3>Detail Produk</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Kopi</th>
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

    <hr>

    <h3>Ubah Status Pesanan</h3>
    <form method="POST">
        <select name="status" required>
            <option value="pending" <?= $pesanan['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="diproses" <?= $pesanan['status'] == 'diproses' ? 'selected' : ''; ?>>Diproses</option>
            <option value="selesai" <?= $pesanan['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
            <option value="dibatalkan" <?= $pesanan['status'] == 'dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
        </select>

        <button type="submit" name="ubah_status">
            Simpan Status
        </button>
    </form>

    <br>
    <a href="pesanan.php">← Kembali ke daftar pesanan</a>

</body>

</html>