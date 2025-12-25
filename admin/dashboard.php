<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM tb_kopi ORDER BY id_kopi DESC");
if (!$query) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/icon/favicon1.png" type="image/png">
    <title>Dashboard Admin</title>
    <style>
        .img-kopi {
            max-width: 80px;
            height: auto;
            border-radius: 6px;
        }
    </style>

</head>

<body>
    <a href="logout.php" class="logout">Logout</a>
    <h1>Dashboard Admin</h1>
    <p>Selamat datang Kak <b><?= $_SESSION['nama_admin']; ?></b>! Selamat Bekerja.... </p>
    <a href="laporan_penjualan.php"> Laporan Penjualan</a>
    <br><br>    
    <a href="tambah_kopi.php">+ Tambah Kopi</a> 

    <table>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Kopi</th>
            <th>Jenis Kopi</th>
            <th>Stok</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>

        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($query)) {
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td>
                    <?php if ($row['gambar']) { ?>
                        <img src="assets/img/<?= $row['gambar']; ?>" class="img-kopi">
                    <?php } else { ?>
                        Tidak ada gambar
                    <?php } ?>
                </td>
                <td><?= htmlspecialchars($row['nama_kopi']); ?></td>
                <td><?= $row['jenis_kopi']; ?></td>
                <td><?= $row['stok']; ?></td>
                <td>Rp <?= number_format($row['harga']); ?></td>
                <td>
                    <a href="edit_kopi.php?id=<?= $row['id_kopi']; ?>" class="btn edit">Edit</a>
                    <a href="hapus_kopi.php?id=<?= $row['id_kopi']; ?>"
                        class="btn hapus"
                        onclick="return confirm('Yakin mau hapus data ini?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>