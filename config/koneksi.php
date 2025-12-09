<?php
    $Host = "localhost";
    $User = "u652573098_ruangkopi";
    $Password = "@PemwebKelompok2";
    $Database = "u652573098_db_Kopi";

    $koneksi = mysqli_connect($Host, $User, $Password, $Database);
    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }else{
        echo "Koneksi berhasil";
        mysqli_close($koneksi);
    }       
?>