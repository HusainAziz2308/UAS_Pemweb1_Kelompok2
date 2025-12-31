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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User | Ruang Kopi</title>
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">

    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border-left: 5px solid #4b3832;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            color: #6b5048;
            font-size: 16px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            padding: 0;
        }

        .card p.big-number {
            font-size: 28px;
            font-weight: bold;
            color: #4b3832;
            margin: 0;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            background: #eee;
            border-radius: 5px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
        }

        .welcome-text {
            margin-bottom: 30px;
            color: #555;
            font-size: 18px;
        }

        .aksi-cepat {
            margin-top: 30px;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .btn-link {
            text-decoration: none;
            color: #4b3832;
            font-weight: bold;
            margin-right: 15px;
        }

        .btn-link:hover {
            color: #be9b7b;
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <h2>Ruang Kopi</h2>
        <div class="user-info">
            <p>👋 Halo,</p>
            <strong><?= htmlspecialchars($_SESSION['nama_user']); ?></strong>
        </div>
        <nav>
            <a href="menu-kopi.php">☕ Menu Utama</a>
            <a href="dashboard-user.php" class="active" style="background-color: #be9b7b; color: white;">📊 Dashboard</a>
            <a href="profil.php">👤 Profil</a>
            <a href="pesanan-saya.php">🧾 Pesanan Saya</a>
            <a href="ganti-password.php">🔐 Ganti Password</a>
            <hr style="border: 0; border-top: 1px solid #6b5048; margin: 10px 0;">
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
        </nav>
    </aside>

    <div class="main-content">
        <h2>Dashboard</h2>
        <p class="welcome-text">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['nama_user']); ?></strong>! Berikut adalah ringkasan aktivitasmu.</p>

        <div class="dashboard-grid">
            <div class="card">
                <h3>📦 Total Pesanan</h3>
                <p class="big-number"><?= $totalPesanan; ?>x</p>
                <small style="color: #888;">Transaksi berhasil dilakukan</small>
            </div>

            <div class="card">
                <h3>💰 Total Pengeluaran</h3>
                <p class="big-number">Rp <?= number_format($totalBelanja, 0, ',', '.'); ?></p>
                <small style="color: #888;">Akumulasi belanja sukses</small>
            </div>

            <div class="card">
                <h3>⏱️ Pesanan Terakhir</h3>
                <?php if ($lastOrder): ?>
                    <p style="font-size: 14px; font-weight: bold;"><?= date('d M Y', strtotime($lastOrder['tanggal'])); ?></p>
                    <p>Rp <?= number_format($lastOrder['total_harga'], 0, ',', '.'); ?></p>

                    <?php
                    $statusColor = '#eee';
                    $statusText = '#333';
                    if ($lastOrder['status'] == 'selesai') {
                        $statusColor = '#d4edda';
                        $statusText = '#155724';
                    }
                    if ($lastOrder['status'] == 'diproses') {
                        $statusColor = '#fff3cd';
                        $statusText = '#856404';
                    }
                    if ($lastOrder['status'] == 'dibatalkan') {
                        $statusColor = '#f8d7da';
                        $statusText = '#721c24';
                    }
                    ?>
                    <span class="status-badge" style="background: <?= $statusColor; ?>; color: <?= $statusText; ?>;">
                        <?= ucfirst($lastOrder['status']); ?>
                    </span>
                <?php else: ?>
                    <p style="color: #888;">Belum ada riwayat pesanan.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="aksi-cepat">
            <h3>Aksi Cepat</h3>
            <p style="margin-top: 10px;">
                <a href="menu-kopi.php" class="btn-link">☕ Pesan Kopi Sekarang</a>
                <a href="pesanan-saya.php" class="btn-link">🧾 Cek Riwayat Lengkap</a>
            </p>
        </div>

    </div>

</body>

</html>