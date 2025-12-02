<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <nav>
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminnNav"> 
                <span class ="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li clas="navbar-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Data kopi</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="data-kopi.php">Daftar Kopi</a></li>
                            <li><a class="dropdown-item" href="tambah-kopi.php">Tambah kopi</a></li>

                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data-pemesanan.php">Data Pemesanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php">Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
        </div>
    </nav>
    
</body>
</html>