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
        p.tanggal,
        p.total_harga,
        p.status
    FROM tb_pesanan p
    WHERE p.username = ?
    ORDER BY p.tanggal DESC
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
    <title>Pesanan Saya | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">

    <style>
        .table-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #4b3832;
            color: #fff;
            font-weight: 500;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .bg-success {
            background: #d4edda;
            color: #155724;
        }

        .bg-warning {
            background: #fff3cd;
            color: #856404;
        }

        .bg-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .btn-batal {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: 0.3s;
        }

        .btn-batal:hover {
            background: #c0392b;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #777;
        }

        .btn-pesan {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background: #be9b7b;
            color: white;
            text-decoration: none;
            border-radius: 5px;
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
        <h2>Riwayat Pesanan</h2>

        <?php if ($pesan) echo $pesan; ?>

        <div class="table-container">
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Detail Order</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td>
                                    <?= date('d M Y', strtotime($row['tanggal'])); ?>
                                    <br>
                                    <small><?= date('H:i', strtotime($row['tanggal'])); ?> WIB</small>
                                </td>
                                <td>
                                    ID: #<?= $row['id_pesanan']; ?>
                                </td>
                                <td style="font-weight: bold; color: #4b3832;">
                                    Rp <?= number_format($row['total_harga'], 0, ',', '.'); ?>
                                </td>
                                <td>
                                    <?php
                                    $status = strtolower($row['status']);
                                    $badgeClass = 'bg-warning';

                                    if ($status == 'selesai') $badgeClass = 'bg-success';
                                    if ($status == 'dibatalkan') $badgeClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass; ?>">
                                        <?= ucfirst($status); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 5px;">
                                        <a href="detail-pesanan.php?id=<?= $row['id_pesanan']; ?>"
                                            style="background: #be9b7b; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none; font-size: 12px;">
                                            🔍 Detail
                                        </a>
                                        <?php if ($status == 'pending' || $status == 'diproses'): ?>
                                            <form method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                                                <input type="hidden" name="id_pesanan_batal" value="<?= $row['id_pesanan']; ?>">
                                                <button type="submit" name="batalkan" class="btn-batal">❌ Batal</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-state">
                    <h3>Belum ada pesanan</h3>
                    <p>Kamu belum pernah melakukan transaksi kopi.</p>
                    <a href="menu-kopi.php" class="btn-pesan">Pesan Kopi Sekarang</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>