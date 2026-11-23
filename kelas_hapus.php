<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/kelas.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kelas.php');
    exit;
}

$id = isset($_POST['id_kelas']) ? (int) $_POST['id_kelas'] : 0;

$database = new Database();
$db = $database->getConnection();
$kelasModel = new Kelas($db);

if (!$kelasModel->getById($id)) {
    $_SESSION['flash_error'] = 'Data kelas tidak ditemukan.';
} elseif ($kelasModel->hasSiswa($id)) {
    $_SESSION['flash_error'] = 'Kelas tidak dapat dihapus karena masih memiliki siswa terdaftar.';
} else {
    $kelasModel->delete($id);
    $_SESSION['flash_success'] = 'Data kelas berhasil dihapus.';
}

header('Location: kelas.php');
exit;