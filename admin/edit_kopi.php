<?php
session_start();
require 'config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit();
}

$query = mysqli_query($koneksi, "SELECT * FROM tb_kopi WHERE id_kopi='$id'");
$kopi  = mysqli_fetch_assoc($query);

if (!$kopi) {
    die("Data kopi tidak ditemukan");
}

if (isset($_POST['update'])) {
    $nama  = $_POST['nama_kopi'];
    $stok  = $_POST['stok'];
    $harga = $_POST['harga'];

    $gambar = $kopi['gambar'];

    if (isset($_POST['hapus_gambar']) && $gambar != "") {
        if (file_exists("assets/img/" . $gambar)) {
            unlink("assets/img/" . $gambar);
        }
        $gambar = "";
    }

    if (!empty($_FILES['gambar']['name'])) {
        $namaFile = $_FILES['gambar']['name'];
        $tmp      = $_FILES['gambar']['tmp_name'];
        $size     = $_FILES['gambar']['size'];
        $ext      = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $allowed)) {
            die("Format gambar harus JPG / PNG");
        }

        if ($size > 5 * 1024 * 1024) {
            die("Ukuran gambar maksimal 5MB");
        }

        if ($gambar != "" && file_exists("assets/img/" . $gambar)) {
            unlink("assets/img/" . $gambar);
        }

        $gambar = time() . "_" . $namaFile;
        move_uploaded_file($tmp, "assets/img/" . $gambar);
    }

    mysqli_query($koneksi, "
        UPDATE tb_kopi SET
        nama_kopi='$nama',
        stok='$stok',
        harga='$harga',
        gambar='$gambar'
        WHERE id_kopi='$id'
    ");

    header("Location: dashboard.php");
    exit();
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
    <form method="POST" enctype="multipart/form-data">
        <label>Nama Kopi</label><br>
        <input type="text" name="nama_kopi" value="<?= htmlspecialchars($kopi['nama_kopi']); ?>" required><br><br>

        <label>Stok</label><br>
        <input type="number" name="stok" value="<?= $kopi['stok']; ?>" required><br><br>

        <label>Harga</label><br>
        <input type="number" name="harga" value="<?= $kopi['harga']; ?>" required><br><br>

        <label>Gambar Saat Ini</label><br>
        <?php if ($kopi['gambar']) : ?>
            <img src="assets/img/<?= $kopi['gambar']; ?>" style="max-width:80px;"><br>
            <label>
                <input type="checkbox" name="hapus_gambar" value="1">
                Hapus gambar
            </label>
        <?php else : ?>
            <p>Tidak ada gambar</p>
        <?php endif; ?>

        <br><br>

        <label>Upload Gambar Baru</label><br>
        <input type="file" name="gambar">
        <br>
        <small>*Upload hanya jika ingin mengganti gambar</small>

        <br><br>

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