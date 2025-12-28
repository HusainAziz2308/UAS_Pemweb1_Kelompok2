<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

if (!isset($_GET['id'])) {
    header("Location: pesanan-saya.php");
    exit();
}

$id_pesanan = (int) $_GET['id'];

$stmt = $koneksi->prepare("
    SELECT * FROM tb_pesanan
    WHERE id_pesanan = ? AND username = ?
");
$stmt->bind_param("is", $id_pesanan, $username);
$stmt->execute();
$pesanan = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$pesanan) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

$stmt = $koneksi->prepare("
    SELECT
        d.jumlah,
        d.harga,
        k.nama_kopi
    FROM tb_pesanan_detail d
    JOIN tb_kopi k ON d.id_kopi = k.id_kopi
    WHERE d.id_pesanan = ?
");
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$detail = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= $id_pesanan ?></title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">
    <style>
        .detail-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .info-pesanan {
            flex: 2;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .qr-card {
            flex: 1;
            min-width: 300px;
            background: #4b3832;
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #be9b7b;
        }

        .qr-image {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            width: 200px;
            height: 200px;
        }

        .qr-image img {
            width: 100%;
            height: 100%;
        }

        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            background: #be9b7b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            text-align: left;
            padding: 10px;
            border-bottom: 2px solid #eee;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .total-row {
            font-size: 20px;
            font-weight: bold;
            color: #4b3832;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #be9b7b;
            font-weight: bold;
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
            <a href="dashboard-user.php">📊 Dashboard</a>
            <a href="profil.php">👤 Profil</a>
            <a href="pesanan-saya.php" class="active" style="background-color: #be9b7b; color: white;">🧾 Pesanan Saya</a>
            <a href="ganti-password.php">🔐 Ganti Password</a>
            <hr style="border: 0; border-top: 1px solid #6b5048; margin: 10px 0;">
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
        </nav>
    </aside>

    <div class="main-content">
        <h2>Detail Transaksi</h2>

        <div class="detail-wrapper">
            <div class="info-pesanan">
                <p><b>ID Pesanan:</b> #<?= $id_pesanan; ?></p>
                <p><b>Tanggal:</b> <?= date('d F Y', strtotime($pesanan['tanggal'])); ?></p>
                <p><b>Status:</b> <span class="status-badge"><?= ucfirst($pesanan['status']); ?></span></p>

                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $detail->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['nama_kopi']); ?></td>
                                <td><?= $row['jumlah']; ?>x</td>
                                <td>Rp <?= number_format($row['jumlah'] * $row['harga'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <p class="total-row">Total Bayar: Rp <?= number_format($pesanan['total_harga'], 0, ',', '.'); ?></p>

                <a href="pesanan-saya.php" class="back-link">← Kembali ke Riwayat</a>
            </div>

            <div class="qr-card">
                <h3>QR CODE PEMBAYARAN</h3>
                <p style="font-size: 12px; opacity: 0.8;">Tunjukkan QR ini ke Barista kami di kasir</p>

                <div class="qr-image">
                    <img src="../admin/assets/img/qr-code.png" alt="QR Code Pesanan">
                </div>

                <p style="font-weight: bold; letter-spacing: 2px;">RK-<?= $id_pesanan; ?><?= strtoupper(substr($username, 0, 3)); ?></p>
                <small style="margin-top: 15px; display: block; line-height: 1.4;">
                    Visi: Nikmati kopi berkualitas <br> dengan harga web yang lebih hemat!
                </small>
            </div>
        </div>
    </div>

</body>

</html>