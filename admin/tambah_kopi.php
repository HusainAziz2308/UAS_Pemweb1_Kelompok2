<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama_kopi'];
    $stok  = $_POST['stok'];
    $harga = $_POST['harga'];

    $gambar = $_FILES['gambar']['name'];
    $tmp    = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "assets/img/" . $gambar);

    mysqli_query($koneksi, "
        INSERT INTO tb_kopi (nama_kopi, stok, harga, gambar)
        VALUES ('$nama', '$stok', '$harga', '$gambar')
    ");

    header("Location: dashboard.php");
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
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Kopi</label><br>
        <input type="text" name="nama_kopi" required><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" required><br><br>

        <label>Harga</label><br>
        <input type="number" name="harga" required><br><br>

        <label>Gambar</label><br>
        <input type="file" name="gambar" required><br><br>

        <button type="submit" name="simpan">Simpan</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
</html>