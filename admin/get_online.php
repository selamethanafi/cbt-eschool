<?php
include '../koneksi/koneksi.php';

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$threshold = date('Y-m-d H:i:s', strtotime('-5 minutes'));

// Query dasar dengan pencarian
$base_query = "FROM siswa WHERE last_activity >= '$threshold'";
if (!empty($search)) {
    $search = mysqli_real_escape_string($koneksi, $search);
    $base_query .= " AND (nama_siswa LIKE '%$search%' OR kelas LIKE '%$search%' OR rombel LIKE '%$search%')";
}

// Hitung total data
$count_query = "SELECT COUNT(*) as total $base_query";
$count_result = mysqli_query($koneksi, $count_query);
$total = mysqli_fetch_assoc($count_result)['total'];

// Ambil data
$query = "SELECT id_siswa, nama_siswa, kelas, rombel, last_activity, page_url $base_query 
          ORDER BY nama_siswa ASC 
          LIMIT $limit OFFSET $offset";
$result = mysqli_query($koneksi, $query);

$data = [];
$nomor = $offset + 1;

while ($row = mysqli_fetch_assoc($result)) {
    $is_online = isset($row['last_activity']) && $row['last_activity'] >= $threshold;
    $status = $is_online 
        ? '<span class="badge bg-success">Online</span>' 
        : '<span class="badge bg-secondary">Offline</span>';

    $data[] = [
        $nomor++,
        htmlspecialchars($row['nama_siswa']),
        htmlspecialchars($row['kelas']),
        htmlspecialchars($row['rombel']),
        $row['last_activity'] ?? '-',
        htmlspecialchars($row['page_url']),
        $status,
        $row['id_siswa']
    ];
}

echo json_encode([
    'data' => $data,
    'total' => $total,
    'search' => $search
]);
?>