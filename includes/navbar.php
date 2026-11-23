<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar">
    <div class="nav-container">
        <a href="rekap_presensi.php" class="nav-brand">Presensi RFID - SMK TI Airlangga</a>

        <?php if (isset($_SESSION['username'])): ?>
            <ul class="nav-menu">
                <li>
                    <a href="rekap_presensi.php" class="<?= $currentPage === 'rekap_presensi.php' ? 'active' : '' ?>">
                        Rekap Presensi
                    </a>
                </li>
                <li>
                    <a href="tapkartu.php" class="<?= $currentPage === 'tapkartu.php' ? 'active' : '' ?>">
                        Simulasi Tap
                    </a>
                </li>
                <li>
                    <a href="kelas.php" class="<?= in_array($currentPage, ['kelas.php', 'kelas_tambah.php', 'kelas_edit.php']) ? 'active' : '' ?>">
                        Kelola Kelas
                    </a>
                </li>
                <li>
                    <a href="siswa.php" class="<?= in_array($currentPage, ['siswa.php', 'siswa_tambah.php', 'siswa_edit.php']) ? 'active' : '' ?>">
                        Kelola Siswa
                    </a>
                </li>
            </ul>

            <div class="nav-user">
                <span>Admin: <strong><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></strong></span>
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        <?php endif; ?>
    </div>
</nav>
<main class="container">