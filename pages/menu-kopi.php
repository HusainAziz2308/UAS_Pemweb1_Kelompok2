<?php
require "../admin/config/koneksi.php";

$query = $koneksi->query("SELECT * FROM tb_kopi ORDER BY id_kopi DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">

    <style>
        body {
            background: #482915;
            color: white;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        .kopi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 20px;
        }

        .kopi-card {
            background: #482915;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            text-align: center;
            transition: 0.3s;
        }

        .kopi-card:hover {
            background: #945225;
            transform: translateY(-5px);
        }

        .kopi-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .kopi-card h3 {
            margin: 15px 0 5px;
        }

        .harga {
            font-size: 18px;
            font-weight: bold;
            color: #e67e22;
            margin-bottom: 10px;
        }

        .tb-order {
            text-decoration: none;
            padding: 10px 15px;
            background: #c97800;
            color: #fff;
            border-radius: 8px;
            display: inline-block;
        }
        .tb-order:hover {
            background: #ee8f00;
            zoom: 1.05;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h2 class="logo">Ruang Kopi</h2>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu-kopi.php" class="active">Menu</a></li>
                <li><a href="tentang.php">Tentang</a></li>
                <li><a href="kontak.php">Kontak</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </nav>
    <section class="gambar-bg-section">
        <div class="container-gambar">
            <img class="gambar-bg" src="../admin/assets/img/bg_kopi_1.jpg" alt="gambar bg">
        </div>
        
        <div class="text-bg">
            <h2>Daftar Menu Kopi</h2>
            <p>Pilih Kopimu disini</p>
        </div>
    </section>  
    <div class="kopi-grid">

        <?php while ($row = $query->fetch_assoc()) { ?>
        <div class="kopi-card">
            <img src="../admin/assets/img/<?= $row['foto'] ?>" alt="<?= $row['nama_kopi'] ?>">

            <h3><?= $row['nama_kopi'] ?></h3>

            <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>

            <p><?= substr($row['deskripsi'], 0, 60) ?>...</p>

            <a href="pesan.php?id=<?= $row['id_kopi'] ?>" class="tb-order">Pesan Sekarang</a>
        </div>
        <?php } ?>

    </div>

</body>
</html>
