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
    $usernameInput = trim($_POST['username'] ?? '');//mengambil nilai input username dari data POST yang dikirimkan melalui formulir login. Jika tidak ada nilai yang dikirimkan, maka akan diisi dengan string kosong ('').
    $passwordInput = $_POST['password'] ?? '';//mengambil nilai input password dari data POST yang dikirimkan melalui formulir login. Jika tidak ada nilai yang dikirimkan, maka akan diisi dengan string kosong ('').

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
             // Menyimpan ID user ke dalam memori sesi server agar sistem tahu siapa yang sedang login di halaman lain
            $_SESSION['id'] = $authenticatedUser['id_user'];
             // Menyimpan username ke dalam sesi untuk ditampilkan (misalnya tulisan "Halo, admin" di pojok layar)
            $_SESSION['username'] = $authenticatedUser['username'];
// Mengalihkan (redirect) pengguna yang berhasil login secara otomatis ke halaman rekap_presensi.php
            header('Location: rekap_presensi.php');
// Menghentikan eksekusi kode di bawahnya segera setelah perintah redirect dijalankan agar halaman langsung berpindah
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
             <!-- 
            Kotak isian teks:
            - type="text" : Jenis isian teks biasa
            - id          : Identitas unik agar terhubung dengan <label> di atas
            - name        : Nama kunci yang dikirim ke PHP ($_POST['username'])
            - class       : Gaya desain kotak input yang rapi (Bootstrap)
            - value       : Mengisi ulang ketikan lama jika login gagal & mencegah serangan XSS
            - autofocus   : Kursor otomatis aktif di kotak ini saat halaman dibuka
        -->
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