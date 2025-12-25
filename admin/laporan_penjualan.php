<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$data = mysqli_query($koneksi, "
    SELECT 
        p.id,
        p.tanggal_pesanan,
        k.nama_kopi,
        p.jumlah,
        k.harga,
        (p.jumlah * k.harga) AS total
    FROM tb_pesanan p
    JOIN tb_kopi k ON p.id_kopi = k.id_kopi
    ORDER BY p.tanggal_pesanan DESC
");


$totalQ = mysqli_query($koneksi, "
    SELECT SUM(p.jumlah * k.harga) AS total_penjualan
    FROM tb_pesanan p
    JOIN tb_kopi k ON p.id_kopi = k.id_kopi
");
$total = mysqli_fetch_assoc($totalQ);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Laporan Penjualan</title>
</head>

<body>
    <h2>Laporan Penjualan</h2>

    <p><b>Total Penjualan:</b> Rp <?= number_format($total['total_penjualan'] ?? 0); ?></p>

    <table border="1" cellpadding="8">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Kopi</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>

        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($data)) {
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $row['tanggal_pesanan']; ?></td>
                <td><?= $row['nama_kopi']; ?></td>
                <td><?= $row['jumlah']; ?></td>
                <td>Rp <?= number_format($row['harga']); ?></td>
                <td>Rp <?= number_format($row['total']); ?></td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>