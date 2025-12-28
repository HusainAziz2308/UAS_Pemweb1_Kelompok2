<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

$stmt = $koneksi->prepare("
    SELECT 
        p.id_pesanan,
        p.tanggal_pesanan,
        p.total_harga,
        p.status
    FROM tb_pesanan p
    WHERE p.username = ?
    ORDER BY p.tanggal_pesanan DESC
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
    <title>Pesanan Saya | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>

<body>

    <?php include "../partials/sidebar.php"; ?>

    <div class="main-content">
        <h1>Pesanan Saya</h1>

        <?php if ($result->num_rows === 0): ?>
            <p>Belum ada pesanan.</p>
        <?php else: ?>
            <table border="1" cellpadding="10" cellspacing="0">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

                <?php $no = 1; ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tanggal_pesanan'])); ?></td>
                        <td>Rp <?= number_format($row['total_harga']); ?></td>
                        <td>
                            <?php
                            if ($row['status'] == 'pending') echo '⏳ Pending';
                            elseif ($row['status'] == 'diproses') echo '☕ Diproses';
                            elseif ($row['status'] == 'selesai') echo '✅ Selesai';
                            else echo '❌ Dibatalkan';
                            ?>
                        </td>
                        <td>
                            <a href="detail-pesanan.php?id=<?= $row['id_pesanan']; ?>">
                                Detail
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
    </div>

</body>

</html>

<?php
$stmt->close();
?>