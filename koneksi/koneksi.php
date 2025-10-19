<?php
date_default_timezone_set('Asia/Jakarta');

$koneksi = mysqli_connect('localhost', 'super', '95EfR6wfFf^!', 'cbt_db');
$key = 'cbteschool@#12345';
$agen_1 = 'MAN2SEMARANG_CEMERLANG_2025';
$agen_2 = 'MAN2SEMARANG_CEMERLANG';
$agen_3 = 'MAN2SEMARANG_CEMERLANG_IPHONE';
$agen_4 = 'MAN2SEMARANG_CEMERLANG_WINDOWS';
$agen_5 = 'MAN2SEMARANG_CEMERLANG';
$agen_6 = 'MAN2SEMARANG_CEMERLANG';
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
