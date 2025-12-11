<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | Ruang Kopi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/favicon1.png" type="image/png">
</head>
<style>
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

<body>
    <nav class="navbar">
        <div class="container">
            <h2 class="logo">Ruang Kopi</h2>
            <ul>
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="pages/menu-kopi.php">Menu</a></li>
                <li><a href="tentang.php">Tentang</a></li>
                <li><a href="kontak.php">Kontak</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>
    </nav>
    <section class="gambar-bg-section">
        <div class="container-gambar">
            <img class="gambar-bg" src="assets/img/bg_kopi_1.jpg" alt="gambar bg">
        </div>
        
        <div class="text-bg">
            <h1>Selamat Datang di Ruang Kopi</h1>
            <p>Tempatnya Inspirasi Rasa</p>
        </div>
    </section>  

    <header class="hero">
        <div class="hero-text">
            <h1>Selamat Datang di Kedai Kopi Kami</h1>
            <p>Temukan racikan kopi terbaik untuk menemani harimu.</p>
            <a href="menu-kopi.html" class="btn-primary">Lihat Menu Kopi</a>
        </div>
    </header>

    <section class="features">
        <div class="container">
            <h2>Mengapa Memilih Kopi Kami?</h2>
            <div class="feature-grid">
                <div class="feature-item">
                    <h3>Biji Kopi Premium</h3>
                    <p>Kami memilih biji kopi lokal terbaik untuk rasa yang maksimal.</p>
                </div>

                <div class="feature-item">
                    <h3>Proses Modern</h3>
                    <p>Dibuat dengan teknik barista profesional.</p>
                </div>

                <div class="feature-item">
                    <h3>Harga Terjangkau</h3>
                    <p>Kualitas tinggi dengan harga yang ramah di kantong.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="produk">
        <h2>Produk Kopi Populer</h2>
        <div class="kopi-grid">
            <div class="kopi-card">
            <img src="assets/img/<?= $row['foto'] ?>" alt="<?= $row['nama_kopi'] ?>">

            <h3><?= $row['nama_kopi'] ?></h3>

            <p class="harga">Rp <?= number_format($row['harga'], 0, ',', '.') ?></p>

            <p><?= substr($row['deskripsi'], 0, 60) ?>...</p>

            <a href="pesan.php?id=<?= $row['id_kopi'] ?>" class="tb-order">Pesan Sekarang</a>
        </div>
        </div>
    </section>

    <section class="pemesanan">
        <h2>Cara Pemesanan</h2>
        <ol>
            <li>Pilih kopi</li>
            <li>Isi form pemesanan</li>
            <li>Konfirmasi</li>
            <li>Selesai</li>
        </ol>
    </section>

    <section class="tentang">
        <h2>Tentang Kami</h2>
        <p>Kami menyediakan kopi premium dengan cita rasa terbaik.</p>
    </section>

    <section class="kontak">
        <h2>Kontak</h2>
        <p>WhatsApp: 08xxxxxxx</p>
        <p>Email: kopi@ruangkopi.com</p>
    </section>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2025 Ruang Kopi. All rights reserved.</p>
            <div class="social-media">
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>
            </div>
        </div>
    </footer>
</body>

</html>