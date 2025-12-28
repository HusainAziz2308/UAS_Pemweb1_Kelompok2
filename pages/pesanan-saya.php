<?php
session_start();
require "../admin/config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['user'];

$query = "
    SELECT *
    FROM tb_pesanan
    WHERE username = ?
    ORDER BY tanggal_pesanan DESC
";
?>