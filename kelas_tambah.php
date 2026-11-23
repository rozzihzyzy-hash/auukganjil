<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/kelas.php';

$database = new Database();
$db = $database->getConnection();
$kelasModel = new Kelas($db);

$errors = [];
$old = [
    'nama_kelas' => '',
    'jurusan'    => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['nama_kelas'] = trim($_POST['nama_kelas'] ?? '');
    $old['jurusan']    = trim($_POST['jurusan'] ?? '');

    if ($old['nama_kelas'] === '') {
        $errors['nama_kelas'] = 'Nama kelas wajib diisi.';
    } elseif (strlen($old['nama_kelas']) > 20) {
        $errors['nama_kelas'] = 'Nama kelas maksimal 20 karakter.';
    } elseif ($kelasModel->isNamaKelasExists($old['nama_kelas'])) {
        $errors['nama_kelas'] = 'Nama kelas sudah terdaftar, gunakan nama lain.';
    }

    if ($old['jurusan'] === '') {
        $errors['jurusan'] = 'Jurusan wajib diisi.';
    } elseif (strlen($old['jurusan']) > 50) {
        $errors['jurusan'] = 'Jurusan maksimal 50 karakter.';
    }

    if (empty($errors)) {
        $kelasModel->setNamaKelas($old['nama_kelas']);
        $kelasModel->setJurusan($old['jurusan']);
        $kelasModel->create();

        $_SESSION['flash_success'] = 'Data kelas berhasil ditambahkan.';
        header('Location: kelas.php');
        exit;
    }
}

$pageTitle = 'Tambah Kelas - Aplikasi Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Tambah Kelas</h2>
        <a href="kelas.php" class="btn btn-secondary">Kembali</a>
    </div>

    <form method="POST" action="kelas_tambah.php">
        <div class="form-group">
            <label for="nama_kelas">Nama Kelas</label>
            <input
                type="text"
                id="nama_kelas"
                name="nama_kelas"
                class="form-control <?= isset($errors['nama_kelas']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['nama_kelas'], ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Contoh: XI PPLG 2"
            >
            <?php if (isset($errors['nama_kelas'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['nama_kelas'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="jurusan">Jurusan</label>
            <input
                type="text"
                id="jurusan"
                name="jurusan"
                class="form-control <?= isset($errors['jurusan']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['jurusan'], ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Contoh: Pengembangan Perangkat Lunak dan Gim"
            >
            <?php if (isset($errors['jurusan'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['jurusan'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Kelas</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>