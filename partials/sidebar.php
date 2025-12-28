<aside class="sidebar">
    <h2>Ruang Kopi</h2>

    <div class="user-info">
        <p>👋 Halo,</p>
        <strong><?= htmlspecialchars($_SESSION['nama_user']); ?></strong>
    </div>

    <nav>
        <a href="dashboard-user.php">Dashboard</a>
        <a href="profil.php">Profil</a>
        <a href="ganti-password.php">Ganti Password</a>
        <a href="pesanan-saya.php">Pesanan Saya</a>
        <a href="logout.php">Logout</a>
    </nav>
</aside>