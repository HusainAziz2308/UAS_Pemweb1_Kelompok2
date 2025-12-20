<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$data = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT * FROM tb_kopi WHERE id_kopi='$id'")
);

if (isset($_POST['update'])) {
    $nama  = $_POST['nama_kopi'];
    $stok  = $_POST['stok'];
    $harga = $_POST['harga'];

    if ($_FILES['gambar']['name'] != "") {
        // hapus gambar lama
        $old = $_SERVER['DOCUMENT_ROOT']."/assets/img/".$data['gambar'];
        if (file_exists($old)) unlink($old);

        // upload gambar baru
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file(
            $_FILES['gambar']['tmp_name'],
            $_SERVER['DOCUMENT_ROOT']."/assets/img/".$gambar
        );

        mysqli_query($koneksi, "
            UPDATE tb_kopi SET 
            nama_kopi='$nama',
            stok='$stok',
            harga='$harga',
            gambar='$gambar'
            WHERE id_kopi='$id'
        ");
    } else {
        // tanpa ganti gambar
        mysqli_query($koneksi, "
            UPDATE tb_kopi SET 
            nama_kopi='$nama',
            stok='$stok',
            harga='$harga'
            WHERE id_kopi='$id'
        ");
    }

    header("Location: dashboard.php");
    exit();
}
?>

<h2>Edit Kopi</h2>

<form method="POST" enctype="multipart/form-data">
    <label>Nama Kopi</label>
    <input type="text" name="nama_kopi" value="<?= $data['nama_kopi'] ?>" required>

    <label>Stok</label>
    <input type="number" name="stok" value="<?= $data['stok'] ?>" required>

    <label>Harga</label>
    <input type="number" name="harga" value="<?= $data['harga'] ?>" required>

    <label>Gambar Saat Ini</label><br>
    <img src="/assets/img/<?= $data['gambar'] ?>" width="120"><br><br>

    <label>Ganti Gambar (opsional)</label>
    <input type="file" name="gambar">

    <br><br>
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