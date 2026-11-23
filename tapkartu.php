<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/siswa.php';
require_once __DIR__ . '/models/presensi.php';

$database = new Database();
$db = $database->getConnection();
$siswaModel = new Siswa($db);
$presensiModel = new Presensi($db);

$error = '';
$successMessage = '';
$nisnInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nisnInput = trim($_POST['nisn'] ?? '');

    if ($nisnInput === '') {
        $error = 'Silakan tempelkan kartu atau masukkan 10 digit NISN.';
    } elseif (!preg_match('/^\d{10}$/', $nisnInput)) {
        $error = 'Format kartu tidak valid: NISN harus terdiri dari 10 digit angka.';
    } else {
        $dataSiswa = $siswaModel->getByNisn($nisnInput);

        if (!$dataSiswa) {
            $error = "Tap kartu ditolak: NISN {$nisnInput} tidak terdaftar di data siswa.";
        } else {
            $presensiModel->setNisn($nisnInput);
            $presensiModel->tapKartu();

            $waktuSekarang = date('d-m-Y H:i:s');
            $successMessage = "Presensi berhasil dicatat! {$dataSiswa['nama']} ({$dataSiswa['nama_kelas']}) — Pukul {$waktuSekarang}.";
            $nisnInput = '';
        }
    }
}

$pageTitle = 'Simulasi Tap Kartu RFID - Aplikasi Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card" style="max-width: 540px; margin: 20px auto;">
    <div class="card-header">
        <h2>Simulasi Pembaca Kartu RFID</h2>
        <a href="rekap_presensi.php" class="btn btn-secondary btn-sm">Lihat Rekap</a>
    </div>

    <p style="margin-bottom: 16px; color: #475569; font-size: 0.92rem;">
        Tempelkan kartu RFID atau ketik 10 digit NISN siswa lalu tekan <strong>Enter</strong> untuk mencatat kehadiran.
    </p>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <?php if ($successMessage !== ''): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST" action="tapkartu.php">
        <div class="form-group">
            <label for="nisn">Nomor Kartu / NISN (10 Digit)</label>
            <input
                type="text"
                id="nisn"
                name="nisn"
                maxlength="10"
                inputmode="numeric"
                class="form-control <?= $error !== '' ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($nisnInput, ENT_QUOTES, 'UTF-8') ?>"
                placeholder="Contoh: 0081234567"
                autofocus
            >
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Tap Kartu</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>