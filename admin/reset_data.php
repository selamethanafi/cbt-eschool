<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

// Ambil parameter
$start  = $_GET['start'] ?? 0;
$length = $_GET['length'] ?? 10;
$orderColumn = $_GET['order'][0]['column'] ?? 0;
$orderDir = $_GET['order'][0]['dir'] ?? 'asc';
$search = $_GET['search']['value'] ?? '';
$filterKelas = $_GET['filterKelas'] ?? '';
$filterStatus = $_GET['filterStatus'] ?? '';

// Mapping kolom
$columns = ['nama_siswa', 'kelas', 'rombel', 'kode_soal', 'waktu_dijawab', 'status_ujian'];
$orderBy = $columns[$orderColumn - 1] ?? 'nama_siswa'; // -1 karena ada kolom "No" di depan

// Filter query, tambah filter status_ujian != 'Selesai'
$searchCondition = "WHERE jawaban_siswa.status_ujian != 'Selesai'";

if (!empty($search)) {
    $search = mysqli_real_escape_string($koneksi, $search);
    $searchCondition .= " AND (siswa.nama_siswa LIKE '%$search%' 
                            OR siswa.kelas LIKE '%$search%' 
                            OR siswa.rombel LIKE '%$search%' 
                            OR jawaban_siswa.kode_soal LIKE '%$search%')";
}
if (!empty($filterKelas)) {
    $filterKelas = mysqli_real_escape_string($koneksi, $filterKelas);
    $searchCondition .= " AND siswa.kelas = '$filterKelas'";
}
if (!empty($filterStatus)) {
    $filterStatus = mysqli_real_escape_string($koneksi, $filterStatus);
    $searchCondition .= " AND jawaban_siswa.status_ujian = '$filterStatus'";
}

// Total data (hanya hitung yang status_ujian != 'Selesai')
$totalQuery = mysqli_query($koneksi, "
    SELECT COUNT(*) as total 
    FROM jawaban_siswa 
    JOIN siswa ON siswa.id_siswa = jawaban_siswa.id_siswa
    WHERE jawaban_siswa.status_ujian != 'Selesai'
");
$totalData = mysqli_fetch_assoc($totalQuery)['total'];

// Filtered count
$filteredQuery = mysqli_query($koneksi, "
    SELECT COUNT(*) as total 
    FROM jawaban_siswa 
    JOIN siswa ON siswa.id_siswa = jawaban_siswa.id_siswa
    $searchCondition
");
$filteredTotal = mysqli_fetch_assoc($filteredQuery)['total'];

// Ambil data utama
$dataQuery = mysqli_query($koneksi, "
    SELECT siswa.nama_siswa, siswa.kelas, siswa.rombel, jawaban_siswa.kode_soal, jawaban_siswa.status_ujian, jawaban_siswa.waktu_dijawab, siswa.id_siswa
    FROM jawaban_siswa
    JOIN siswa ON siswa.id_siswa = jawaban_siswa.id_siswa
    $searchCondition
    ORDER BY $orderBy $orderDir
    LIMIT $start, $length
");

$data = [];
while ($row = mysqli_fetch_assoc($dataQuery)) {
    $data[] = $row;
}

echo json_encode([
    "draw" => intval($_GET['draw'] ?? 0),
    "recordsTotal" => $totalData,
    "recordsFiltered" => $filteredTotal,
    "data" => $data
]);
