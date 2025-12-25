<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM tb_laporan ORDER BY tanggal DESC");

if (!$query) {
    die("Query error: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #eee;
        }
    </style>
</head>
<body>

<h2>Laporan Penjualan</h2>
<a href="dashboard.php">← Kembali ke Dashboard</a>

<table>
    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Total Penjualan</th>
        <th>Keterangan</th>
    </tr>

    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($query)) :
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $row['tanggal']; ?></td>
        <td>Rp <?= number_format($row['total_penjualan']); ?></td>
        <td><?= $row['keterangan']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
