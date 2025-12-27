<?php
require "middleware_user.php";
require "../admin/config/koneksi.php";

$username = $_SESSION['user'];

$q1 = mysqli_query(
    $koneksi,
    "SELECT COUNT(*) total FROM tb_pesanan WHERE username='$username'"
);
$totalPesanan = mysqli_fetch_assoc($q1)['total'];

$q2 = mysqli_query(
    $koneksi,
    "SELECT SUM(total) total FROM tb_pesanan WHERE username='$username'"
);
$totalBelanja = mysqli_fetch_assoc($q2)['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Dashboard User | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">
</head>

<body>
    <aside class="sidebar">
        <h2>Ruang Kopi</h2>

        <div class="user-info">
            <p>👋 Halo,</p>
            <strong>
                <?= htmlspecialchars($user['nama']); ?>
            </strong>
        </div>

        <nav>
            <a href="dashboard-user.php">Dashboard</a>
            <a href="profil.php">Profil</a>
            <a href="pesanan-saya.php">Pesanan</a>
            <a href="ganti-password.php">Ganti Password</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>
    <div class="main-content">
        <div class="card">
            <h2>Dashboard User</h2>

            <div class="card">
                <h3>Total Pesanan</h3>
                <p>
                    <?= $totalPesanan ?>
                </p>
            </div>

            <div class="card">
                <h3>Total Belanja</h3>
                <p>Rp
                    <?= number_format($totalBelanja) ?>
                </p>
            </div>
        </div>
    </div>
</body>

</html>