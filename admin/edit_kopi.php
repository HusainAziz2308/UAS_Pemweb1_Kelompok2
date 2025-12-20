<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM tb_kopi WHERE id_kopi='$id'");
$kopi = mysqli_fetch_assoc($data);

if (!$kopi) {
    die("Data tidak ditemukan");
}

$error = "";

if (isset($_POST['update'])) {
    $nama   = htmlspecialchars($_POST['nama_kopi']);
    $stok   = (int) $_POST['stok'];
    $harga  = (int) $_POST['harga'];
    $jenis  = $_POST['jenis_kopi'];

    $gambarBaru = $kopi['gambar'];

    if (isset($_POST['hapus_gambar']) && $kopi['gambar']) {
        unlink("assets/img/" . $kopi['gambar']);
        $gambarBaru = NULL;
    }

    if ($_FILES['gambar']['error'] !== 4 && !$kopi['gambar']) {

        $namaFile = $_FILES['gambar']['name'];
        $tmpFile  = $_FILES['gambar']['tmp_name'];
        $sizeFile = $_FILES['gambar']['size'];

        $extValid = ['jpg', 'jpeg', 'png'];
        $ext = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        if (!in_array($ext, $extValid)) {
            $error = "Format gambar harus JPG, JPEG, atau PNG!";
        } else if ($sizeFile > 5 * 1024 * 1024) {
            $error = "Ukuran gambar maksimal 5MB!";
        } else {
            $gambarBaru = uniqid() . "." . $ext;
            move_uploaded_file($tmpFile, "assets/img/" . $gambarBaru);
        }
    }

    if ($error === "") {
        mysqli_query($koneksi, "
            UPDATE tb_kopi SET
                nama_kopi='$nama',
                stok='$stok',
                harga='$harga',
                jenis_kopi='$jenis',
                gambar=" . ($gambarBaru ? "'$gambarBaru'" : "NULL") . "
            WHERE id_kopi='$id'
        ");

        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Edit Kopi</title>
</head>
<body>
    <h2>Edit Kopi</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?= $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Nama Kopi</label><br>
        <input type="text" name="nama_kopi" value="<?= $kopi['nama_kopi']; ?>" required><br><br>

        <label>Jenis Kopi</label><br>
        <select name="jenis_kopi" required>
            <option value="Biji_kopi" <?= $kopi['jenis_kopi'] == 'Biji_kopi' ? 'selected' : '' ?>>Biji Kopi</option>
            <option value="Kopi_jadi" <?= $kopi['jenis_kopi'] == 'Kopi_jadi' ? 'selected' : '' ?>>Kopi Jadi</option>
        </select><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" value="<?= $kopi['stok']; ?>" required><br><br>

        <label>Harga</label><br>
        <input type="number" name="harga" value="<?= $kopi['harga']; ?>" required><br><br>

        <?php if ($kopi['gambar']) : ?>
            <p>Gambar saat ini:</p>
            <img src="assets/img/<?= $kopi['gambar']; ?>" style="max-width:80px;"><br>
            <label>
                <input type="checkbox" name="hapus_gambar"> Hapus gambar
            </label>
        <?php else : ?>
            <p>Tidak ada gambar</p>
        <?php endif; ?>

        <br><br>
        <label>Upload Gambar Baru</label><br>
        <input type="file" name="gambar">
        <small>*Upload hanya bisa jika gambar lama dihapus</small><br><br>

        <button type="submit" name="update">Update</button>
        <a href="dashboard.php">Batal</a>
    </form>
</body>
</html>

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