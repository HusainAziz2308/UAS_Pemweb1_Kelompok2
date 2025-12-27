<?php

require "middleware_user.php";
require "../admin/config/koneksi.php";

$username = $_SESSION['user'];
$data = mysqli_query(
    $koneksi,
    "SELECT * FROM tb_pesanan WHERE username='$username' ORDER BY id_pesanan DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Pesanan Saya | Ruang Kopi</title>
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
            <h2>Pesanan Saya</h2>

            <table>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
                <?php $no = 1; while ($p = mysqli_fetch_assoc($data)): ?>
                <tr>
                    <td>
                        <?= $no++ ?>
                    </td>
                    <td>
                        <?= $p['tanggal'] ?>
                    </td>
                    <td>Rp
                        <?= number_format($p['total']) ?>
                    </td>
                    <td>
                        <?= $p['status'] ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
</body>
</html>