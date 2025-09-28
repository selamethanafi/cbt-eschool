<?php
date_default_timezone_set('Asia/Jakarta');
// Koneksi ke database MySQL pakai mysqli
$koneksi = mysqli_connect('localhost', 'ubk', 'bPWX!jT8', 'cbt_db');
$key = 'cbteschool@#12345'; 
$agen_1 = 'MAN2SEMARANG_CEMERLANG_2025';
$agen_2 = 'MAN2SEMARANG_CEMERLANG';
$agen_3 = 'MAN2SEMARANG_CEMERLANG_IPHONE';
$agen_4 = 'MAN2SEMARANG_CEMERLANG_WINDOWS';
$agen_5 = 'MAN2SEMARANG_CEMERLANG';
$agen_6 = 'MAN2SEMARANG_CEMERLANG';
// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
