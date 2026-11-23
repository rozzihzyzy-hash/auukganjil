<?php
if (session_status() === PHP_SESSION_NONE) {//mengecek status sesi sesion status session none tanda sesi belum berjalan, sesion strt memulai sesi php
    session_start();//mengecek status sesi sesion status session none tanda sesi belum berjalan, sesion strt memulai sesi php
}

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {//mengecek apakah variabel sesi id dan username tidak diatur, jika tidak diatur maka pengguna belum login
    header('Location: login.php');//mengalihkan pengguna ke halaman login.php jika variabel sesi id dan username tidak diatur, yang berarti pengguna belum login
    exit;// menghentikan eksekusi skrip saat ini setelah mengirimkan header pengalihan, sehingga tidak ada kode tambahan yang dijalankan setelah pengalihan.
}
?>