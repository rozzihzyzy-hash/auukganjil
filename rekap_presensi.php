<?php
require_once __DIR__ . '/includes/auth.php';

$pageTitle = 'Rekap Presensi - Aplikasi Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Rekap Presensi Siswa</h2>
    </div>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?></strong>. Login dan proteksi session sudah berhasil.</p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>