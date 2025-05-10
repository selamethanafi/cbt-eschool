<?php
header('Content-Type: application/json');
include '../koneksi/koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cek dan ambil parameter dari DataTables, beri nilai default jika tidak ada
$start = isset($_GET['start']) ? $_GET['start'] : 0;   // Offset untuk paging
$length = isset($_GET['length']) ? $_GET['length'] : 10; // Jumlah data per halaman
$orderColumn = isset($_GET['order'][0]['column']) ? $_GET['order'][0]['column'] : 0; // Kolom yang digunakan untuk sorting
$orderDirection = isset($_GET['order'][0]['dir']) ? $_GET['order'][0]['dir'] : 'asc'; // Ascending atau descending
$searchValue = isset($_GET['search']['value']) ? $_GET['search']['value'] : ''; // Nilai pencarian

// Tentukan nama kolom berdasarkan index order
$columns = ['s.nama_siswa', 'js.kode_soal', 'js.waktu_sisa', 'js.waktu_dijawab', 'js.status_ujian'];
$orderBy = $columns[$orderColumn];

// Membuat query dengan pencarian dan pagination
$where = "";
if (!empty($searchValue)) {
    $where = "WHERE s.nama_siswa LIKE '%$searchValue%' OR js.kode_soal LIKE '%$searchValue%'";
} else {
    // Jika tidak ada pencarian, hanya tampilkan yang status_ujian = 'Aktif'
    $where = "WHERE js.status_ujian = 'Aktif'";
}

$query = "SELECT js.*, s.nama_siswa
          FROM jawaban_siswa js
          JOIN siswa s ON js.id_siswa = s.id_siswa
          $where
          ORDER BY $orderBy $orderDirection
          LIMIT $start, $length";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . mysqli_error($koneksi)]);
    exit;
}

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $badge = ($row['status_ujian'] === 'Aktif')
        ? '<span class="badge bg-success">Aktif</span>'
        : '<span class="badge bg-danger">Non-Aktif</span>';

    $data[] = [
        'nama_siswa' => $row['nama_siswa'],
        'kode_soal' => $row['kode_soal'],
        'waktu_sisa' => $row['waktu_sisa'],
        'waktu_dijawab' => $row['waktu_dijawab'],
        'status_badge' => $badge
    ];
}

// Hitung total data (tanpa filter)
$queryCount = "SELECT COUNT(*) AS total FROM jawaban_siswa js JOIN siswa s ON js.id_siswa = s.id_siswa $where";
$countResult = mysqli_query($koneksi, $queryCount);
$total = mysqli_fetch_assoc($countResult)['total'];

// Kirim data dalam format JSON
echo json_encode([
    'draw' => isset($_GET['draw']) ? $_GET['draw'] : 1,
    'recordsTotal' => $total,
    'recordsFiltered' => $total,
    'data' => $data
]);
