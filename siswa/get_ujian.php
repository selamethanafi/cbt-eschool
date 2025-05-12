<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

$query = mysqli_query($koneksi, "SELECT * FROM soal WHERE status='Aktif' AND kelas='$kelas_siswa' ORDER BY tanggal DESC");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>