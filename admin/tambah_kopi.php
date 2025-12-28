<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if (isset($_POST['simpan'])) {
    $nama   = htmlspecialchars($_POST['nama_kopi']);
    $stok   = (int) $_POST['stok'];
    $harga  = (int) $_POST['harga'];
    $jenis  = $_POST['jenis_kopi'];

    $namaGambar = NULL;

    if ($_FILES['gambar']['error'] !== 4) {

        $namaFile  = $_FILES['gambar']['name'];
        $tmpFile   = $_FILES['gambar']['tmp_name'];
        $sizeFile  = $_FILES['gambar']['size'];

        $extValid = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ext, $extValid)) {
            $error = "Format gambar harus JPG, JPEG, atau PNG!";
        } else if ($sizeFile > 5 * 1024 * 1024) {
            $error = "Ukuran gambar maksimal 5MB!";
        } else {
            $namaGambar = uniqid() . "." . $ext;
            move_uploaded_file($tmpFile, "assets/img/" . $namaGambar);
        }
    }

    if ($error === "") {
        mysqli_query($koneksi, "
            INSERT INTO tb_kopi (nama_kopi, stok, harga, jenis_kopi, gambar)
            VALUES (
                '$nama',
                '$stok',
                '$harga',
                '$jenis',
                " . ($namaGambar ? "'$namaGambar'" : "NULL") . "
            )
        ");

        header("Location: dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/icon/favicon1.png" type="image/png">
    <title>Dashboard | Tambah Kopi</title>
    <style>
        .preview img {
            max-width: 80px;
            margin-top: 10px;
            border: 1px solid #ccc;
            padding: 4px;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <a href="logout.php" class="logout">Logout</a>

    <h1>Dashboard Admin</h1>
    <p>Selamat datang, <b><?= $_SESSION['nama_admin']; ?></b></p>

    <div class="top-nav">
        <a href="dashboard.php">Dashboard</a>
        <a href="pesanan.php">Pesanan</a>
        <a href="tambah_kopi.php">Tambah Kopi</a>
    </div>


    <?php if ($error): ?>
        <div class="error"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nama Kopi</label><br>
        <input type="text" name="nama_kopi" required><br><br>

        <label>Jenis Kopi</label><br>
        <select name="jenis_kopi" required>
            <option value="">-- Pilih Jenis --</option>
            <option value="Biji_kopi">Biji Kopi</option>
            <option value="Kopi_jadi">Kopi Jadi</option>
        </select><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" required><br><br>

        <label>Harga</label><br>
        <input type="number" name="harga" required><br><br>

        <label>Upload Gambar (Opsional)</label><br>
        <input type="file" name="gambar" id="gambar" accept="image/*">

        <div class="preview">
            <img id="previewImg" style="display:none;">
        </div>

        <br>
        <button type="submit" name="simpan">Simpan</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
<script>
    document.getElementById("gambar").addEventListener("change", function(e) {
        const img = document.getElementById("previewImg");
        const file = e.target.files[0];

        if (file) {
            img.src = URL.createObjectURL(file);
            img.style.display = "block";
        } else {
            img.style.display = "none";
        }
    });
</script>

</html>