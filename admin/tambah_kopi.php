<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if (isset($_POST['simpan'])) {
    $nama  = htmlspecialchars($_POST['nama_kopi']);
    $stok  = (int) $_POST['stok'];
    $harga = (int) $_POST['harga'];

    $namaGambar = NULL;

    if ($_FILES['gambar']['error'] !== 4) {

        $namaFile  = $_FILES['gambar']['name'];
        $tmpFile   = $_FILES['gambar']['tmp_name'];
        $sizeFile  = $_FILES['gambar']['size'];
        $errorFile = $_FILES['gambar']['error'];

        $extValid = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ext, $extValid)) {
            $error = "Format gambar harus JPG, JPEG, atau PNG!";
        } elseif ($sizeFile > 5 * 1024 * 1024) {
            $error = "Ukuran gambar maksimal 5MB!";
        } else {
            $namaGambar = uniqid() . "." . $ext;
            move_uploaded_file($tmpFile, "assets/img/" . $namaGambar);
        }
    }

    if ($error === "") {
        mysqli_query($koneksi, "
            INSERT INTO tb_kopi (nama_kopi, stok, harga, gambar)
            VALUES ('$nama', '$stok', '$harga', " .
            ($namaGambar ? "'$namaGambar'" : "NULL") . ")
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
    <title>Dashboard | Tambah Kopi</title>
</head>
<body>
    <h2>Tambah Kopi</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nama Kopi</label><br>
        <input type="text" name="nama_kopi" required><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" required><br><br>

        <label>Harga</label><br>
        <input type="number" name="harga" required><br><br>

        <label>Upload Gambar (Opsional)</label><br>
        <input type="file" name="gambar" id="gambar" accept="image/*">

        <!-- PREVIEW -->
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