<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/siswa.php';
require_once __DIR__ . '/models/kelas.php';

$nisnLama = trim($_GET['nisn'] ?? '');

$database = new Database();
$db = $database->getConnection();
$siswaModel = new Siswa($db);
$kelasModel = new Kelas($db);

$siswaData = $siswaModel->getByNisn($nisnLama);
if (!$siswaData) {
    $_SESSION['flash_error'] = 'Data siswa tidak ditemukan.';
    header('Location: siswa.php');
    exit;
}

$daftarKelas = $kelasModel->getAll();

$errors = [];
$old = [
    'nisn'     => $siswaData['nisn'],
    'nama'     => $siswaData['nama'],
    'alamat'   => $siswaData['alamat'] ?? '',
    'id_kelas' => (string) $siswaData['id_kelas']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['nisn']     = trim($_POST['nisn'] ?? '');
    $old['nama']     = trim($_POST['nama'] ?? '');
    $old['alamat']   = trim($_POST['alamat'] ?? '');
    $old['id_kelas'] = trim($_POST['id_kelas'] ?? '');

    if ($old['nisn'] === '') {
        $errors['nisn'] = 'NISN wajib diisi.';
    } elseif (!preg_match('/^\d{10}$/', $old['nisn'])) {
        $errors['nisn'] = 'NISN wajib terdiri dari 10 digit angka.';
    } elseif ($siswaModel->isNisnExists($old['nisn'], $nisnLama)) {
        $errors['nisn'] = 'NISN sudah digunakan oleh siswa lain.';
    }

    if ($old['nama'] === '') {
        $errors['nama'] = 'Nama siswa wajib diisi.';
    } elseif (strlen($old['nama']) > 100) {
        $errors['nama'] = 'Nama siswa maksimal 100 karakter.';
    }

    if (strlen($old['alamat']) > 255) {
        $errors['alamat'] = 'Alamat maksimal 255 karakter.';
    }

    $idKelasInt = (int) $old['id_kelas'];
    if ($old['id_kelas'] === '' || $idKelasInt <= 0) {
        $errors['id_kelas'] = 'Kelas wajib dipilih.';
    } elseif (!$kelasModel->getById($idKelasInt)) {
        $errors['id_kelas'] = 'Kelas yang dipilih tidak valid.';
    }

    if (empty($errors)) {
        $siswaModel->setNisn($old['nisn']);
        $siswaModel->setNama($old['nama']);
        $siswaModel->setAlamat($old['alamat']);
        $siswaModel->setIdKelas($idKelasInt);
        $siswaModel->update($nisnLama);

        $_SESSION['flash_success'] = 'Data siswa berhasil diperbarui.';
        header('Location: siswa.php');
        exit;
    }
}

$pageTitle = 'Ubah Siswa - Aplikasi Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card">
    <div class="card-header">
        <h2>Ubah Siswa</h2>
        <a href="siswa.php" class="btn btn-secondary">Kembali</a>
    </div>

    <form method="POST" action="siswa_edit.php?nisn=<?= urlencode($nisnLama) ?>">
        <div class="form-group">
            <label for="nisn">NISN (10 Digit Angka)</label>
            <input
                type="text"
                id="nisn"
                name="nisn"
                maxlength="10"
                inputmode="numeric"
                class="form-control <?= isset($errors['nisn']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['nisn'], ENT_QUOTES, 'UTF-8') ?>"
            >
            <?php if (isset($errors['nisn'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['nisn'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="nama">Nama Siswa</label>
            <input
                type="text"
                id="nama"
                name="nama"
                class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($old['nama'], ENT_QUOTES, 'UTF-8') ?>"
            >
            <?php if (isset($errors['nama'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['nama'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="id_kelas">Kelas</label>
            <select
                id="id_kelas"
                name="id_kelas"
                class="form-control <?= isset($errors['id_kelas']) ? 'is-invalid' : '' ?>"
            >
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($daftarKelas as $k): ?>
                    <option
                        value="<?= (int) $k['id_kelas'] ?>"
                        <?= ((string) $old['id_kelas'] === (string) $k['id_kelas']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($k['nama_kelas'] . ' - ' . $k['jurusan'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($errors['id_kelas'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['id_kelas'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="alamat">Alamat (Boleh Kosong)</label>
            <textarea
                id="alamat"
                name="alamat"
                rows="3"
                class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>"
            ><?= htmlspecialchars($old['alamat'], ENT_QUOTES, 'UTF-8') ?></textarea>
            <?php if (isset($errors['alamat'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['alamat'], ENT_QUOTES, 'UTF-8') ?></span>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>