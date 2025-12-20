<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$tgl_awal  = $_GET['tgl_awal']  ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';
$id_kopi   = $_GET['id_kopi']   ?? '';
$sort      = $_GET['sort']      ?? 'tanggal_desc';

$sql = "
SELECT 
    p.id,
    p.nama_pelanggan,
    k.nama_kopi,
    p.jumlah,
    k.harga,
    (p.jumlah * k.harga) AS total,
    p.tanggal
FROM tb_pesanan p
JOIN tb_kopi k ON p.id_kopi = k.id_kopi
WHERE 1=1
";

if ($tgl_awal && $tgl_akhir) {
    $sql .= " AND DATE(p.tanggal) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

if ($id_kopi) {
    $sql .= " AND p.id_kopi = '$id_kopi'";
}

switch ($sort) {
    case 'nama_asc':
        $sql .= " ORDER BY k.nama_kopi ASC";
        break;
    case 'nama_desc':
        $sql .= " ORDER BY k.nama_kopi DESC";
        break;
    case 'tanggal_asc':
        $sql .= " ORDER BY p.tanggal ASC";
        break;
    default:
        $sql .= " ORDER BY p.tanggal DESC";
}

$data = mysqli_query($koneksi, $sql);

$listKopi = mysqli_query($koneksi, "SELECT * FROM tb_kopi ORDER BY nama_kopi ASC");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <h2>Laporan Penjualan</h2>

    <form method="GET">
        <label>Dari Tanggal</label>
        <input type="date" name="tgl_awal" value="<?= $tgl_awal ?>">

        <label>Sampai Tanggal</label>
        <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>">

        <label>Produk Kopi</label>
        <select name="id_kopi">
            <option value="">-- Semua Kopi --</option>
            <?php while ($k = mysqli_fetch_assoc($listKopi)) : ?>
                <option value="<?= $k['id_kopi'] ?>"
                    <?= ($id_kopi == $k['id_kopi']) ? 'selected' : '' ?>>
                    <?= $k['nama_kopi'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Urutkan</label>
        <select name="sort">
            <option value="tanggal_desc">Tanggal Terbaru</option>
            <option value="tanggal_asc">Tanggal Terlama</option>
            <option value="nama_asc">Nama Kopi A-Z</option>
            <option value="nama_desc">Nama Kopi Z-A</option>
        </select>

        <button type="submit">Filter</button>
        <a href="laporan.php">Reset</a>
    </form>

    <br>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Pelanggan</th>
            <th>Kopi</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Total</th>
        </tr>

        <?php
        $no = 1;
        $grandTotal = 0;
        while ($row = mysqli_fetch_assoc($data)) :
            $grandTotal += $row['total'];
        ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                <td><?= $row['nama_kopi'] ?></td>
                <td><?= $row['jumlah'] ?></td>
                <td>Rp <?= number_format($row['harga']) ?></td>
                <td>Rp <?= number_format($row['total']) ?></td>
            </tr>
        <?php endwhile; ?>

        <tr>
            <th colspan="6" align="right">TOTAL PENJUALAN</th>
            <th>Rp <?= number_format($grandTotal) ?></th>
        </tr>
    </table>

    <br>
    <a href="dashboard.php">⬅ Kembali ke Dashboard</a>

</body>

</html>