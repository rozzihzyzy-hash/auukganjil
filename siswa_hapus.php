<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/siswa.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: siswa.php');
    exit;
}

$nisn = trim($_POST['nisn'] ?? '');

$database = new Database();
$db = $database->getConnection();
$siswaModel = new Siswa($db);

if ($nisn === '' || !$siswaModel->getByNisn($nisn)) {
    $_SESSION['flash_error'] = 'Data siswa tidak ditemukan.';
} else {
    $siswaModel->delete($nisn);
    $_SESSION['flash_success'] = 'Data siswa berhasil dihapus.';
}

header('Location: siswa.php');
exit;