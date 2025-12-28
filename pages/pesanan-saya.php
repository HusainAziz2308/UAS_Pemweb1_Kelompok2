<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

$stmt = $koneksi->prepare("
    SELECT * FROM tb_pesanan 
    WHERE username = ?
    ORDER BY tanggal DESC
");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>

<body>

    <h2>Pesanan Saya</h2>

    <?php if ($result->num_rows == 0): ?>
        <p>Belum ada pesanan.</p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id_pesanan']; ?></td>
                    <td><?= $row['tanggal']; ?></td>
                    <td>Rp <?= number_format($row['total']); ?></td>
                    <td><?= $row['status']; ?></td>
                    <td>
                        <a href="detail-pesanan.php?id=<?= $row['id_pesanan']; ?>">
                            Detail
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

    <br>
    <a href="profil.php">← Kembali ke Profil</a>

</body>

</html>