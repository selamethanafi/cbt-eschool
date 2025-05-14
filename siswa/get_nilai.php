<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php'; // Mengandung $id_siswa

// Cek status sembunyikan_nilai dari tabel pengaturan
$cek = mysqli_query($koneksi, "SELECT sembunyikan_nilai FROM pengaturan LIMIT 1");
$row_cek = mysqli_fetch_assoc($cek);
$sembunyikan_nilai = (int) $row_cek['sembunyikan_nilai'];

// Ambil hasil ujian siswa
$query = mysqli_query($koneksi, "
    SELECT 
        n.kode_soal,
        s.mapel,
        n.nilai,
        n.tanggal_ujian,
        m.nama_siswa
    FROM nilai n
    JOIN soal s ON n.kode_soal = s.kode_soal
    JOIN siswa m ON n.id_siswa = m.id_siswa
    WHERE n.id_siswa = '$id_siswa'
    ORDER BY n.tanggal_ujian DESC
");

$data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $nilai_display = $sembunyikan_nilai ? '-' : $row['nilai'];

    if ($sembunyikan_nilai) {
        $aksi = '<button class="btn btn-outline-secondary" disabled>
                    <i class="fa fa-lock"></i> Preview Nilai
                 </button>';
    } else {
        $aksi = '<a class="btn btn-outline-secondary" href="preview.php?id_siswa=' . $id_siswa . '&kode_soal=' . $row['kode_soal'] . '">
                    <i class="fa fa-eye"></i> Preview Nilai
                 </a>';
    }

    $data[] = [
        'nama_siswa' => $row['nama_siswa'],
        'kode_soal' => $row['kode_soal'],
        'mapel' => $row['mapel'],
        'nilai' => $nilai_display,
        'tanggal_ujian' => date('d M Y, H:i', strtotime($row['tanggal_ujian'])),
        'aksi' => $aksi
    ];
}

header('Content-Type: application/json');
echo json_encode([
    'data' => $data,
    'sembunyikan_nilai' => $sembunyikan_nilai
]);
