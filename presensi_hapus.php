<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/presensi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: rekap_presensi.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

$database = new Database();
$db = $database->getConnection();
$presensiModel = new Presensi($db);

if ($id <= 0 || !$presensiModel->getById($id)) {
    $_SESSION['flash_error'] = 'Log presensi tidak ditemukan.';
} else {
    $presensiModel->delete($id);
    $_SESSION['flash_success'] = 'Log presensi berhasil dihapus.';
}

header('Location: rekap_presensi.php');
exit;