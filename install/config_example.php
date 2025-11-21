<?php
date_default_timezone_set('Asia/Jakarta');

$koneksi = mysqli_connect('{DB_HOST}', '{DB_USER}', '{DB_PASS}', '{DB_NAME}');
$key = 'cbteschool@#12345';

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
