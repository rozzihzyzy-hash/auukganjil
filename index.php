<?php
session_start();
if (isset($_SESSION['id'])) {
    header('Location: rekap_presensi.php');
} else {
    header('Location: login.php');
}
exit;