<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
// Periksa apakah id_soal dan aksi ada di parameter GET
    $status = 'Nonaktif';
    $query = "UPDATE soal SET status = '$status', token = NULL";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = 'Soal berhasil dinonaktifkan.';
    } else {
        $_SESSION['error'] = 'Gagal menonaktifkan soal: ' . mysqli_error($koneksi);
    }
    header('Location: soal_aktif.php');
    exit;
