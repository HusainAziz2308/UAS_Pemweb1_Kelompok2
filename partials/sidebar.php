<aside class="sidebar">
    <h2>Ruang Kopi</h2>
    <div class="user-info">
        <p>👋 Halo,</p>
        <strong><?= htmlspecialchars($_SESSION['nama_user']); ?></strong>
    </div>
    <nav>
        <a href="menu-kopi.php">☕ Menu Utama</a>
        <a href="dashboard-user.php">📊 Dashboard</a>
        <a href="profil.php">👤 Profil</a>
        <a href="pesanan-saya.php">🧾 Pesanan Saya</a>
        <a href="ganti-password.php">🔐 Ganti Password</a>
        <hr>
        <a href="logout.php" onclick="return confirm('Yakin ingin logout?')">🚪 Logout</a>
    </nav>
</aside>