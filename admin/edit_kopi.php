<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM tb_kopi WHERE id_kopi='$id'");
$k = mysqli_fetch_assoc($data);
?>

<form method="POST" enctype="multipart/form-data">
    <label>Nama Kopi</label>
    <input type="text" name="nama_kopi" value="<?= $k['nama_kopi'] ?>" required>

    <label>Deskripsi</label>
    <textarea name="deskripsi"><?= $k['deskripsi'] ?></textarea>

    <label>Stok</label>
    <input type="number" name="stok" value="<?= $k['stok'] ?>" required>

    <label>Harga</label>
    <input type="number" name="harga" value="<?= $k['harga'] ?>" required>

    <label>Ganti Gambar (optional)</label>
    <input type="file" name="gambar">

    <button type="submit" name="update">Update</button>
</form>

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