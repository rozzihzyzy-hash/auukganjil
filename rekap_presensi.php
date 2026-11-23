<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/presensi.php';
require_once __DIR__ . '/models/kelas.php';

$database = new Database();
$db = $database->getConnection();
$presensiModel = new Presensi($db);
$kelasModel = new Kelas($db);

$daftarKelas = $kelasModel->getAll();

// Secara bawaan yang tampil adalah presensi hari ini
$tanggalFilter = isset($_GET['tanggal']) ? trim($_GET['tanggal']) : date('Y-m-d');
$kelasFilter   = isset($_GET['id_kelas']) ? (int) $_GET['id_kelas'] : 0;
$keywordCari   = trim($_GET['cari'] ?? '');

$daftarPresensi = $presensiModel->getFiltered($tanggalFilter, $kelasFilter, $keywordCari);

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$pageTitle = 'Rekap Presensi - Aplikasi Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Rekap Presensi Siswa</h2>
        <a href="tapkartu.php" class="btn btn-primary">+ Simulasi Tap Kartu</a>
    </div>

    <?php if ($flashSuccess !== ''): ?>
        <div class="alert alert-success"><?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($flashError !== ''): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="GET" action="rekap_presensi.php" style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 18px; align-items: end;">
        <div class="form-group" style="margin-bottom: 0;">
            <label for="tanggal">Tanggal</label>
            <input
                type="date"
                id="tanggal"
                name="tanggal"
                class="form-control"
                value="<?= htmlspecialchars($tanggalFilter, ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div class="form-group" style="margin-bottom: 0;">
            <label for="id_kelas">Kelas</label>
            <select id="id_kelas" name="id_kelas" class="form-control">
                <option value="0">-- Semua Kelas --</option>
                <?php foreach ($daftarKelas as $k): ?>
                    <option
                        value="<?= (int) $k['id_kelas'] ?>"
                        <?= $kelasFilter === (int) $k['id_kelas'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($k['nama_kelas'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 0; flex: 1; min-width: 200px;">
            <label for="cari">Cari Nama / NISN</label>
            <input
                type="text"
                id="cari"
                name="cari"
                class="form-control"
                placeholder="Ketik nama atau NISN..."
                value="<?= htmlspecialchars($keywordCari, ENT_QUOTES, 'UTF-8') ?>"
            >
        </div>

        <div style="display: flex; gap: 6px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="rekap_presensi.php" class="btn btn-secondary">Hari Ini</a>
        </div>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NISN</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Tanggal & Waktu Tap</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($daftarPresensi)): ?>
                    <tr>
                        <td colspan="6">Tidak ada data presensi pada filter yang dipilih.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($daftarPresensi as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($row['nisn'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars($row['nama_kelas'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars(date('d-m-Y H:i:s', strtotime($row['tanggal_waktu'])), ENT_QUOTES, 'UTF-8') ?></td>
                            <td>
                                <form method="POST" action="presensi_hapus.php" onsubmit="return confirm('Hapus log presensi yang salah catat ini?');">
                                    <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>