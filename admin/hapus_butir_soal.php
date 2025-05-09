<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if (isset($_GET['id_soal'])) {
    $id_soal = mysqli_real_escape_string($koneksi, $_GET['id_soal']);
    
    // Query to get the kode_soal based on id_soal
    $query = "SELECT kode_soal FROM butir_soal WHERE id_soal = '$id_soal'";
    $result = mysqli_query($koneksi, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $kode_soal = $row['kode_soal'];
        
        // Delete the soal
        $delete_query = "DELETE FROM butir_soal WHERE id_soal = '$id_soal'";
        if (mysqli_query($koneksi, $delete_query)) {
            header('Location: daftar_butir_soal.php?status=success&kode_soal=' . urlencode($kode_soal));
            exit();
        } else {
            header('Location: daftar_butir_soal.php?status=error&message=' . urlencode(mysqli_error($koneksi)));
            exit();
        }
    } else {
        header('Location: daftar_butir_soal.php?status=error&message=Soal%20tidak%20ditemukan');
        exit();
    }
} else {
    header('Location: daftar_butir_soal.php?status=error&message=Parameter%20id_soal%20tidak%20ditemukan');
    exit();
}
