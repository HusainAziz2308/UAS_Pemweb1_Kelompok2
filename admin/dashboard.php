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
    <link rel="shortcut icon" href="assets/img/favicon1.png" type="image/png">
    <title>Dashboard Admin</title>
    <style>

    </style>
    
</head>
<body>
    <a href="logout.php" class="logout">Logout</a>
    <h1>Dashboard Admin</h1>
    <p>Selamat datang <b><?= $_SESSION['admin']; ?></b></p>

    <table>
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Nama Kopi</th>
            <th>Harga</th>
            <th>Stok</th>
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
                        <img src="assets/img/<?= $row['gambar']; ?>">
                    <?php } else { ?>
                        Tidak ada gambar
                    <?php } ?>
                </td>
                <td><?= htmlspecialchars($row['nama_kopi']); ?></td>
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