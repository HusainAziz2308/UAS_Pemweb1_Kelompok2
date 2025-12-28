<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

$qTotalPesanan = $koneksi->prepare("
    SELECT COUNT(*) AS total
    FROM tb_pesanan
    WHERE username = ?
");
$qTotalPesanan->bind_param("s", $username);
$qTotalPesanan->execute();
$totalPesanan = $qTotalPesanan->get_result()->fetch_assoc()['total'];

$qTotalBelanja = $koneksi->prepare("
    SELECT SUM(total_harga) AS total
    FROM tb_pesanan
    WHERE username = ?
      AND status != 'dibatalkan'
");
$qTotalBelanja->bind_param("s", $username);
$qTotalBelanja->execute();
$totalBelanja = $qTotalBelanja->get_result()->fetch_assoc()['total'] ?? 0;

$qLastOrder = $koneksi->prepare("
    SELECT tanggal, total_harga, status
    FROM tb_pesanan
    WHERE username = ?
    ORDER BY tanggal DESC
    LIMIT 1
");
$qLastOrder->bind_param("s", $username);
$qLastOrder->execute();
$lastOrder = $qLastOrder->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard User | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
</head>

<body>

    <?php include "../partials/sidebar.php"; ?>

    <div class="main-content">
        <h1>Dashboard</h1>

        <p>Halo <?= htmlspecialchars($_SESSION['nama_user']); ?> 👋</p>

        <div class="card">
            <h3>Total Pesanan</h3>
            <p><?= $totalPesanan; ?></p>
        </div>

        <div class="card">
            <h3>Total Belanja</h3>
            <p>Rp <?= number_format($totalBelanja); ?></p>
        </div>

        <div class="card">
            <h3>Pesanan Terakhir</h3>
            <?php if ($lastOrder): ?>
                <p>Tanggal: <?= date('d-m-Y', strtotime($lastOrder['tanggal'])); ?></p>
                <p>Total: Rp <?= number_format($lastOrder['total_harga']); ?></p>
                <p>Status: <?= ucfirst($lastOrder['status']); ?></p>
            <?php else: ?>
                <p>Belum ada pesanan</p>
            <?php endif; ?>
        </div>

        <div class="aksi">
            <a href="pesanan-saya.php">📦 Lihat Pesanan</a> |
            <a href="profil.php">👤 Profil</a>
        </div>
    </div>

</body>

</html>