<?php
date_default_timezone_set('Asia/Jakarta');
// Koneksi ke database MySQL pakai mysqli
$koneksi = mysqli_connect('localhost', 'root', '', 'cbt_db');

// Cek koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>