<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php'; // diasumsikan variabel $id_siswa dan $kelas_siswa tersedia di sini

$sekarang = date("Y-m-d H:i:s");
$query = mysqli_query($koneksi, "SELECT * FROM soal WHERE status='Aktif' AND kelas='$kelas_siswa' and `tanggal` < '$sekarang'");
//$query = mysqli_query($koneksi, "SELECT * FROM soal WHERE status='Aktif' AND kelas='$kelas_siswa'");
//$query = mysqli_query($koneksi, "SELECT * FROM soal WHERE status='Aktif' AND kelas='$kelas_siswa' ORDER BY tanggal DESC");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $kode_soal = $row['kode_soal'];

    // Cek apakah siswa sudah punya nilai untuk soal ini
    $cek_nilai = mysqli_query($koneksi, "SELECT 1 FROM nilai WHERE id_siswa='$id_siswa' AND kode_soal='$kode_soal' LIMIT 1");

    if (mysqli_num_rows($cek_nilai) == 0) {
        // Belum mengerjakan, tambahkan ke data
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
