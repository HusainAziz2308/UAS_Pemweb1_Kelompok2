<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: menu-kopi.php");
    exit();
}

$id_kopi = (int) $_GET['id'];
$username = $_SESSION['user'];

$stmt = $koneksi->prepare("SELECT * FROM tb_kopi WHERE id_kopi = ?");
$stmt->bind_param("i", $id_kopi);
$stmt->execute();
$kopi = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$kopi) {
    echo "Menu kopi tidak ditemukan";
    exit();
}

if (isset($_POST['pesan'])) {
    $jumlah = (int) $_POST['jumlah'];
    if ($jumlah < 1) $jumlah = 1;

    $total = $jumlah * $kopi['harga'];

    $stmt = $koneksi->prepare("
        INSERT INTO tb_pesanan (username, tanggal, total_harga, status)
        VALUES (?, NOW(), ?, 'pending')
    ");
    $stmt->bind_param("si", $username, $total);
    $stmt->execute();
    $id_pesanan = $stmt->insert_id;
    $stmt->close();

    $stmt = $koneksi->prepare("
        INSERT INTO tb_pesanan_detail (id_pesanan, id_kopi, jumlah, harga)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iiii", $id_pesanan, $id_kopi, $jumlah, $kopi['harga']);
    $stmt->execute();
    $stmt->close();

    header("Location: pesanan-saya.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../admin/assets/icon/favicon1.png" type="image/png">
    <title>Konfirmasi Pesanan | Ruang Kopi</title>
    <link rel="stylesheet" href="../admin/assets/css/style.css">
    <link rel="stylesheet" href="../admin/assets/css/profil.css">
    <style>
        .order-card {
            background: white;
            display: flex;
            gap: 30px;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            flex-wrap: wrap;
        }

        .order-img {
            flex: 1;
            min-width: 250px;
        }

        .order-img img {
            width: 100%;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .order-info {
            flex: 1.5;
            min-width: 280px;
        }

        .price-tag {
            font-size: 24px;
            color: #be9b7b;
            font-weight: bold;
            margin: 10px 0;
        }

        .form-group {
            margin: 20px 0;
        }

        input[type="number"] {
            width: 80px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-confirm {
            background-color: #4b3832;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            width: 100%;
        }

        .btn-confirm:hover {
            background-color: #3c2a24;
        }

        .note {
            background: #fff3cd;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #856404;
            margin-top: 15px;
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
            <a href="pesanan-saya.php">🧾 Pesanan Saya</a>
            <a href="ganti-password.php">🔐 Ganti Password</a>
            <hr style="border: 0; border-top: 1px solid #6b5048; margin: 10px 0;">
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
        </nav>
    </aside>

    <div class="main-content">
        <h2>Konfirmasi Pesanan</h2>

        <div class="order-card">
            <div class="order-img">
                <img src="../admin/assets/img/<?= $kopi['gambar']; ?>" alt="<?= htmlspecialchars($kopi['nama_kopi']); ?>">
            </div>

            <div class="order-info">
                <h3><?= htmlspecialchars($kopi['nama_kopi']); ?></h3>
                <p class="price-tag">Rp <?= number_format($kopi['harga'], 0, ',', '.'); ?></p>
                <p style="color: #666;"><?= htmlspecialchars($kopi['deskripsi']); ?></p>

                <form method="POST">
                    <div class="form-group">
                        <label><b>Jumlah Pesanan:</b></label><br>
                        <input type="number" name="jumlah" value="1" min="1" required id="jumlah">
                    </div>

                    <div class="form-group">
                        <p>Total Bayar: <span id="total-view" style="font-weight: bold; font-size: 18px;">Rp <?= number_format($kopi['harga'], 0, ',', '.'); ?></span></p>
                    </div>

                    <button type="submit" name="pesan" class="btn-confirm">Konfirmasi & Dapatkan QR Code</button>
                </form>

                <div class="note">
                    📌 <b>Info:</b> Setelah memesan, kamu akan mendapatkan QR Code. Tunjukkan QR tersebut ke kasir untuk mendapatkan harga khusus website!
                </div>

                <a href="menu-kopi.php" style="display: block; margin-top: 20px; color: #be9b7b; text-decoration: none;">← Kembali Pilih Menu</a>
            </div>
        </div>
    </div>
    <script src="../admin/assets/js/index.js"></script>
    <script>
        const inputJumlah = document.getElementById('jumlah');
        const totalView = document.getElementById('total-view');
        const hargaSatuan = <?= $kopi['harga']; ?>;

        inputJumlah.addEventListener('input', function() {
            let total = this.value * hargaSatuan;
            if (total < 0) total = 0;
            totalView.innerText = 'Rp ' + total.toLocaleString('id-ID');
        });
    </script>
</body>

</html>