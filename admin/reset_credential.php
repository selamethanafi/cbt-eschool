<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$query = "UPDATE siswa SET session_token = '', last_activity = '', force_logout = 1";

if (mysqli_query($koneksi, $query)) {
    $_SESSION['success'] = 'Credential login semua siswa berhasil direset.';
} else {
    $_SESSION['error'] = 'Gagal mereset credential: ' . mysqli_error($koneksi);
}

header('Location: online.php');
exit;
?>