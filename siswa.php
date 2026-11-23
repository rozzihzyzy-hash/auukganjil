<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/siswa.php';

$database = new Database();
$db = $database->getConnection();
$siswaModel = new Siswa($db);

$keyword = trim($_GET['cari'] ?? '');
if ($keyword !== '') {
    $daftarSiswa = $siswaModel->search($keyword);
} else {
    $daftarSiswa = $siswaModel->getAll();
}

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$pageTitle = 'Kelola Siswa - Aplikasi Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Data Siswa</h2>
        <a href="siswa_tambah.php" class="btn btn-primary">+ Tambah Siswa</a>
    </div>

    <?php if ($flashSuccess !== ''): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($flashError !== ''): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="GET" action="siswa.php" style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px;">
        <input
            type="text"
            name="cari"
            class="form-control"
            style="max-width: 320px;"
            placeholder="Cari nama siswa atau NISN..."
            value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>"
        >
        <button type="submit" class="btn btn-primary">Cari</button>
        <?php if ($keyword !== ''): ?>
            <a href="siswa.php" class="btn btn-secondary">Reset</a>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarSiswa)): ?>
                    <tr>
                        <td colspan="6">Data siswa tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarSiswa as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row['nisn'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['alamat'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <div class="aksi-group">
                                    <a href="siswa_edit.php?nisn=<?= urlencode($row['nisn']) ?>" class="btn btn-warning btn-sm">Ubah</a>
                                    <form method="POST" action="siswa_hapus.php" onsubmit="return confirm('Yakin ingin menghapus siswa ini?');">
                                        <input type="hidden" name="nisn" value="<?= htmlspecialchars($row['nisn'], ENT_QUOTES, 'UTF-8') ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>