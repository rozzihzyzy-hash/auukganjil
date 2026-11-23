<?php
session_start();
if (isset($_SESSION['id'])) {//mengecek apakah variabel sesi id diatur, jika diatur maka pengguna sudah login
    header('Location: rekap_presensi.php');//mengalihkan pengguna ke halaman rekap_presensi.php jika variabel sesi id diatur, yang berarti pengguna sudah login
} else {
    header('Location: login.php');//mengalihkan pengguna ke halaman login.php jika variabel sesi id tidak diatur, yang berarti pengguna belum login
}
exit;