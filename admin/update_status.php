<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id_pesanan = $_POST['id_pesanan'];
$status = $_POST['status'];

$stmt = $koneksi->prepare("
    UPDATE tb_pesanan SET status = ?
    WHERE id_pesanan = ?
");
$stmt->bind_param("si", $status, $id_pesanan);
$stmt->execute();

header("Location: pesanan.php");
exit();
?>