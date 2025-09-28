<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Cek apakah data siswa ada
    $data = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id'");
    if (mysqli_num_rows($data) == 0) {
        $_SESSION['error'] = 'Data siswa tidak ditemukan.';
        header('Location: hapus_login.php');
        exit;
    }

    // Hapus data siswa
    if (mysqli_query($koneksi, "update siswa set `session_token` = NULL WHERE id_siswa = '$id'")) {
        $_SESSION['success'] = 'Token perangkat murid berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Gagal menghapus token perangkat murid: ' . mysqli_error($koneksi);
    }

    header('Location: hapus_login.php');
    exit;
} else {
    header('Location: hapus_login.php');
    exit;
}
