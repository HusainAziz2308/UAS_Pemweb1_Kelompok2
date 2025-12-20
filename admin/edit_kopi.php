<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM tb_kopi WHERE id_kopi='$id'");
$kopi = mysqli_fetch_assoc($data);

if (isset($_POST['update'])) {
    $nama   = $_POST['nama_kopi'];
    $stok  = $_POST['stok'];
    $harga = $_POST['harga'];

    if ($_FILES['gambar']['name'] != "") {
        $gambar = $_FILES['gambar']['name'];
        $tmp    = $_FILES['gambar']['tmp_name'];
        move_uploaded_file($tmp, "assets/img/" . $gambar);

        mysqli_query($koneksi, "
            UPDATE tb_kopi 
            SET nama_kopi='$nama', stok='$stok', harga='$harga', gambar='$gambar'
            WHERE id_kopi='$id'
        ");
    } else {
        mysqli_query($koneksi, "
            UPDATE tb_kopi 
            SET nama_kopi='$nama', stok='$stok', harga='$harga'
            WHERE id_kopi='$id'
        ");
    }

    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Edit Kopi</title>
</head>
<body>
    <h2>Edit Kopi</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Kopi</label><br>
        <input type="text" name="nama_kopi" value="<?= $kopi['nama_kopi']; ?>" required><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" value="<?= $kopi['stok']; ?>" required><br><br>

        <label>Harga</label><br>
        <input type="number" name="harga" value="<?= $kopi['harga']; ?>" required><br><br>

        <label>Gambar (kosongkan jika tidak diganti)</label><br>
        <input type="file" name="gambar"><br><br>

        <button type="submit" name="update">Update</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
</html>


<?php
if (isset($_POST['update'])) {
    $nama  = $_POST['nama_kopi'];
    $desk  = $_POST['deskripsi'];
    $stok  = $_POST['stok'];
    $harga = $_POST['harga'];

    if (!empty($_FILES['gambar']['name'])) {
        $namaFile = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];

        move_uploaded_file($tmp, "assets/img/" . $namaFile);

        mysqli_query($koneksi, "
            UPDATE tb_kopi SET
            nama_kopi='$nama',
            deskripsi='$desk',
            stok='$stok',
            harga='$harga',
            gambar='$namaFile'
            WHERE id_kopi='$id'
        ");
    } else {
        mysqli_query($koneksi, "
            UPDATE tb_kopi SET
            nama_kopi='$nama',
            deskripsi='$desk',
            stok='$stok',
            harga='$harga'
            WHERE id_kopi='$id'
        ");
    }

    header("Location: dashboard.php");
}
?>