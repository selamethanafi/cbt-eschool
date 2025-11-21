<?php
session_start();
include '../koneksi/koneksi.php';

if (isset($_SESSION['siswa_id'])) {
    $id = $_SESSION['siswa_id'];
    mysqli_query($koneksi, "UPDATE siswa SET session_token = NULL WHERE id_siswa = '$id'");
}

session_destroy();
header("Location: login.php");
exit;
?>
