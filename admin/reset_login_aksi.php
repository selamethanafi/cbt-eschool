<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

if (isset($_POST['id_siswa']) && isset($_POST['kode_soal'])) {
    $id_siswa = mysqli_real_escape_string($koneksi, $_POST['id_siswa']);
    $kode_soal = mysqli_real_escape_string($koneksi, $_POST['kode_soal']);

    $query = "UPDATE jawaban_siswa SET status_ujian = 'Non-Aktif' WHERE id_siswa = '$id_siswa' AND kode_soal = '$kode_soal'";
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