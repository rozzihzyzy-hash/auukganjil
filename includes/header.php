<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle =$pageTitle ?? 'Aplikasi Absensi Siswa';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=  htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>