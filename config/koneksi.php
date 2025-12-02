<?php
    $Host = "localhost";
    $User = "root";
    $Password = "";
    $Database = "db_kopi";

    $koneksi = mysqli_connect($Host, $User, $Password, $Database);
    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }else{
        //echo "Koneksi berhasil";
    }       
?>