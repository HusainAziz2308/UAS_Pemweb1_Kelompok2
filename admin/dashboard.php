<?php
session_start();
require "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['tambah_kopi'])) {
    $nama   = $_POST['nama'];
    $desk   = $_POST['deskripsi'];
    $harga  = $_POST['harga'];
    $jenis  = $_POST['jenis_kopi'];

    $namaFile = $_FILES['gambar']['name'];
    $tmpFile  = $_FILES['gambar']['tmp_name'];
    $folder   = "../assets/img_kopi/";

    move_uploaded_file($tmpFile, $folder . $namaFile);

    mysqli_query($koneksi, "INSERT INTO kopi VALUES(
        NULL, '$nama', '$desk', '$harga', '$jenis', '$namaFile'
    )");
}


$dataKopi = mysqli_query($koneksi, "SELECT * FROM kopi ORDER BY id DESC");
$dataPesanan = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kopi, k.harga 
    FROM tb_pesanan p 
    LEFT JOIN kopi k ON p.id_kopi = k.id
    ORDER BY p.id DESC
");
$dataLaporan = mysqli_query($koneksi, "SELECT * FROM tb_laporan ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/img/favicon1.png" type="image/png">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Dashboard Admin</title>

    <style>
        body {
            margin: 0;
            background: #f5f5f5;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        nav {
            width: 250px;
            background: #482915;
            color: white;
            padding: 20px 0;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .brand {
            font-size: 22px;
            padding: 0 20px 20px;
            display: block;
            border-bottom: 1px solid #34495e;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav li a {
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            color: white;
            transition: 0.3s;
        }

        nav li a:hover,
        nav li a.active {
            background: #b35313;
        }

        .content {
            flex-grow: 1;
            padding: 30px;
        }

        h2 {
            color: #b35313;
            margin-top: 40px;
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background: #964B00;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        button:hover {
            scale: 1.04;
            background-color: #964B00;
            box-shadow: 1 8px 8px #ffffff55;
            transition: 0.3s;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            width: 260px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .card-body {
            padding: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        table th {
            background: #b35313;
            color: white;
        }
    </style>

    <script>
        function showSection(id) {
            document.querySelectorAll(".section").forEach(s => s.style.display = "none");
            document.getElementById(id).style.display = "block";
        }
    </script>
</head>

<body>

    <div class="layout">

        <nav>
            <span class="brand">Admin Panel</span>

            <ul>
                <li><a href="#" class="active" onclick="showSection('dashboard')">Dashboard</a></li>
                <li><a href="#" onclick="showSection('kopi')">Data Kopi</a></li>
                <li><a href="#" onclick="showSection('pesanan')">Data Pemesanan</a></li>
                <li><a href="#" onclick="showSection('laporan')">Laporan</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="content">

            <div id="dashboard" class="section">
                <h2>Dashboard</h2>
                <p>Selamat datang Admin! Ini adalah ringkasan singkat:</p>

                <ul>
                    <li>Total Kopi: <b>
                            <?= mysqli_num_rows($dataKopi) ?>
                        </b></li>
                    <li>Total Pesanan: <b>
                            <?= mysqli_num_rows($dataPesanan) ?>
                        </b></li>
                    <li>Total Laporan: <b>
                            <?= mysqli_num_rows($dataLaporan) ?>
                        </b></li>
                </ul>
            </div>

            <div id="kopi" class="section" style="display:none;">
                <h2>Tambah Kopi Baru</h2>

                <form method="POST" enctype="multipart/form-data">
                    <label>Nama Kopi</label>
                    <input type="text" name="nama" required>

                    <label>Deskripsi</label>
                    <textarea name="deskripsi" required></textarea>

                    <label>Harga</label>
                    <input type="number" name="harga" required>

                    <label>Jenis Kopi</label>
                    <select name="jenis_kopi" required>
                        <option value="Biji_kopi">Biji Kopi</option>
                        <option value="Kopi_jadi">Kopi Jadi</option>
                    </select>

                    <label>Upload Gambar</label>
                    <input type="file" name="gambar" required>

                    <button type="submit" name="tambah_kopi">Tambah Kopi</button>
                </form>

                <h2>Daftar Kopi</h2>
                <div class="cards">
                    <?php while ($k = mysqli_fetch_assoc($dataKopi)) { ?>
                    <div class="card">
                        <img src="../assets/img_kopi/<?= $k['gambar'] ?>">
                        <div class="card-body">
                            <h3>
                                <?= $k['nama_kopi'] ?>
                            </h3>
                            <p>
                                <?= $k['deskripsi'] ?>
                            </p>
                            <p><b>Rp
                                    <?= number_format($k['harga']) ?>
                                </b></p>
                            <p><i>
                                    <?= $k['jenis_kopi'] ?>
                                </i></p>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div id="pesanan" class="section" style="display:none;">
                <h2>Data Pemesanan</h2>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pemesan</th>
                        <th>Kopi</th>
                        <th>Jumlah</th>
                        <th>Total Harga</th>
                    </tr>

                    <?php while ($p = mysqli_fetch_assoc($dataPesanan)) { ?>
                    <tr>
                        <td>
                            <?= $p['id'] ?>
                        </td>
                        <td>
                            <?= $p['nama_pelanggan'] ?>
                        </td>
                        <td>
                            <?= $p['nama_kopi'] ?>
                        </td>
                        <td>
                            <?= $p['jumlah'] ?>
                        </td>
                        <td>Rp
                            <?= number_format($p['jumlah'] * $p['harga']) ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

            <div id="laporan" class="section" style="display:none;">
                <h2>Laporan</h2>

                <table>
                    <tr>
                        <th>ID</th>
                        <th>Isi Laporan</th>
                        <th>Tanggal</th>
                    </tr>

                    <?php while ($l = mysqli_fetch_assoc($dataLaporan)) { ?>
                    <tr>
                        <td>
                            <?= $l['id'] ?>
                        </td>
                        <td>
                            <?= $l['isi_laporan'] ?>
                        </td>
                        <td>
                            <?= $l['tanggal'] ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>

        </div>
    </div>

</body>

</html>