<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

$data = mysqli_query($koneksi, "SELECT gambar FROM tb_kopi WHERE id_kopi='$id'");
$k = mysqli_fetch_assoc($data);

// hapus file gambar
if ($k && file_exists("assets/img/" . $k['gambar'])) {
    unlink("assets/img/" . $k['gambar']);
}

mysqli_query($koneksi, "DELETE FROM tb_kopi WHERE id_kopi='$id'");

header("Location: dashboard.php");
exit();
?>