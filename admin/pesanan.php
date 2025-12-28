<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$query = mysqli_query($koneksi, "
    SELECT p.*, u.nama
    FROM tb_pesanan p
    JOIN tb_user u ON p.username = u.username
    ORDER BY p.tanggal DESC
");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/icon/favicon1.png" type="image/png">
    <title>Data Pesanan</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
    </style>
</head>

<body>
    <a href="logout.php" class="logout">Logout</a>

    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <b><?= $_SESSION['nama_admin']; ?></b></p>

    <div class="top-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="tambah_kopi.php">Tambah Kopi</a>
    </div>

    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>

        <?php $no = 1;
        while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['nama']); ?></td>
                <td><?= date('d-m-Y H:i', strtotime($row['tanggal'])); ?></td>
                <td>Rp <?= number_format($row['total_harga']); ?></td>
                <td>
                    <form method="POST" action="update_status.php">
                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan']; ?>">
                        <select name="status" onchange="this.form.submit()">
                            <option <?= $row['status'] == 'pending' ? 'selected' : '' ?>>pending</option>
                            <option <?= $row['status'] == 'diproses' ? 'selected' : '' ?>>diproses</option>
                            <option <?= $row['status'] == 'selesai' ? 'selected' : '' ?>>selesai</option>
                            <option <?= $row['status'] == 'dibatalkan' ? 'selected' : '' ?>>dibatalkan</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="detail-pesanan.php?id=<?= $row['id_pesanan']; ?>">Detail</a>
                </td>
            </tr>
        <?php endwhile; ?>

    </table>

</body>

</html>