<?php
session_start();
if (isset($_SESSION['id'])) {
    header('Location: rekap_presensi.php');
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/user.php';

$error = '';
$usernameInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameInput = trim($_POST['username'] ?? '');
    $passwordInput = $_POST['password'] ?? '';

    if ($usernameInput === '' || $passwordInput === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $database = new Database();
        $db = $database->getConnection();

        $userModel = new User($db);
        $userModel->setUsername($usernameInput);
        $userModel->setPassword($passwordInput);

        $authenticatedUser = $userModel->authenticate();

        if ($authenticatedUser) {
            session_regenerate_id(true);
            $_SESSION['id'] = $authenticatedUser['id_user'];
            $_SESSION['username'] = $authenticatedUser['username'];

            header('Location: rekap_presensi.php');
            exit;
        } else {
            $error = 'Username atau password salah.';
        }
    }
}

$pageTitle = 'Login Administrator - Presensi Siswa';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div class="card login-card">
    <div class="card-header">
        <h2>Login Administrator</h2>
    </div>

    <?php if ($error !== ''): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                class="form-control"
                value="<?= htmlspecialchars($usernameInput, ENT_QUOTES, 'UTF-8') ?>"
                autofocus
            >
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                class="form-control"
            >
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%;">Masuk</button>
    </form>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>