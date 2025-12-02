<?php
include 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Kopi | Menu Utama</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Ruang Kopi</h1>
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.html" class="navbar-logo">RuangKopi</a>
            <button class="nav-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-togler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home">home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="daftar-kopi">Daftar kopi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pemesanan">Pemesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tentang-kami">Tentang kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="kontak">Kontak</a>
                    </li>
                </ul>

            </div>

    </nav>
    <section>
        <h2>Menu Utama</h2>
        <div class="menu-container">
            <div class="menu-item">
                <img src="img/espresso.jpg" alt="Espresso">
                <h3>Espresso</h3>
                <p>Rp 15.000</p>
            </div>
            <div class="menu-item">
                <img src="img/latte.jpg" alt="Latte">
                <h3>Latte</h3>
                <p>Rp 20.000</p>
            </div>
            <div class="menu-item">
                <img src="img/cappuccino.jpg" alt="Cappuccino">
                <h3>Cappuccino</h3>
                <p>Rp 22.000</p>
            </div>
            <div class="menu-item">
                <img src="img/americano.jpg" alt="Americano">
                <h3>Americano</h3>
                <p>Rp 18.000</p>
            </div>
    </section>
</body>

</html>