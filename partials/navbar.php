<?php session_start(); ?>

<nav class="navbar">
    <a href="index.php" class="brand">Ruang Kopi</a>

    <ul class="nav-menu">
        <li><a href="menu-kopi.php">Menu</a></li>

        <?php if (isset($_SESSION['user'])): ?>
            <li class="nav-user">👋 <?= htmlspecialchars($_SESSION['nama_user']); ?></li>
            <li><a href="profil.php">Profil</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login / Daftar</a></li>
        <?php endif; ?>
    </ul>
</nav>