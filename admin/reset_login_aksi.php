<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

if (isset($_POST['id_siswa'])) {
    $id_siswa = $_POST['id_siswa'];

    $query = "UPDATE jawaban_siswa SET status_ujian = 'Non-Aktif' WHERE id_siswa = '$id_siswa'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = "Login siswa berhasil direset.";
    } else {
        $_SESSION['error'] = "Gagal mereset login siswa.";
    }
} else {
    $_SESSION['error'] = "Data tidak valid.";
}

header("Location: reset_login.php");
exit;
?>