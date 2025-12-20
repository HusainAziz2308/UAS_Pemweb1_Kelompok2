<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = (int) $_GET['id'];

$query = mysqli_query($koneksi, "SELECT gambar FROM tb_kopi WHERE id_kopi = '$id'");
$data  = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: dashboard.php");
    exit();
}

if (!empty($data['gambar'])) {
    $path = "assets/img/" . $data['gambar'];
    if (file_exists($path)) {
        unlink($path);
    }
}

mysqli_query($koneksi, "DELETE FROM tb_kopi WHERE id_kopi = '$id'");

header("Location: dashboard.php");
exit();
?>