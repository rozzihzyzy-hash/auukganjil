<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();//mengecek status sesi sesion status session none tanda sesi belum berjalan, sesion strt memulai sesi php
}

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>