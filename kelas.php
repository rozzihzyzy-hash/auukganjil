<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/kelas.php';

$database = new Database();
$db = $database->getConnection();
$kelasModel = new Kelas($db);
$daftarKelas = $kelasModel->getAll();

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$pageTitle = 'Kelola Kelas - Aplikasi Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Data Kelas</h2>
        <a href="kelas_tambah.php" class="btn btn-primary">+ Tambah Kelas</a>
    </div>

    <?php if ($flashSuccess !== ''): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($flashError !== ''): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarKelas)): ?>
                    <tr>
                        <td colspan="4">Belum ada data kelas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarKelas as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['jurusan'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <div class="aksi-group">
                                    <a href="kelas_edit.php?id=<?= (int) $row['id_kelas'] ?>" class="btn btn-warning btn-sm">Ubah</a>
                                    <form method="POST" action="kelas_hapus.php" onsubmit="return confirm('Yakin ingin menghapus kelas ini?');">
                                        <input type="hidden" name="id_kelas" value="<?= (int) $row['id_kelas'] ?>">
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