<?php
session_start();
require "config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

// ambil nama gambar
$data = mysqli_fetch_assoc(
    mysqli_query($koneksi, "SELECT gambar FROM tb_kopi WHERE id_kopi='$id'")
);

if ($data && $data['gambar'] != "") {
    $file = $_SERVER['DOCUMENT_ROOT'] . "/assets/img/" . $data['gambar'];
    if (file_exists($file)) {
        unlink($file);
    }
}

// hapus data
mysqli_query($koneksi, "DELETE FROM tb_kopi WHERE id_kopi='$id'");

header("Location: dashboard.php");
exit();
?>