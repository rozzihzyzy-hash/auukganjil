<?php
if(session_status() === PHP_SESSION_NONE) {//mengecek status sesi sesion status session none tanda sesi belum berjalan, sesion strt memulai sesi php
    session_start();//mengecek status sesi sesion status session none tanda sesi belum berjalan, sesion strt memulai sesi php
}
$pageTitle =$pageTitle ?? 'Aplikasi Absensi Siswa';//??jika kosong, pkai yang di sebelah kanan
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=  htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') //htmlspecialchars pengamana karakter HTML dari serangan XSS?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>