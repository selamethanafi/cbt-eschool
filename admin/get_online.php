<?php
include '../koneksi/koneksi.php';

// Ambil parameter dari GET
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$threshold = date('Y-m-d H:i:s', strtotime('-5 minutes'));

// Total data (untuk hitung jumlah halaman)
$count_query = "SELECT COUNT(*) as total FROM siswa WHERE last_activity >= '$threshold'";
$count_result = mysqli_query($koneksi, $count_query);
$total = mysqli_fetch_assoc($count_result)['total'];

// Ambil data sesuai halaman
$query = "SELECT nama_siswa, kelas, rombel, last_activity, page_url 
          FROM siswa 
          WHERE last_activity >= '$threshold' 
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
        $status
    ];
}

// Kembalikan data dan total
echo json_encode([
    'data' => $data,
    'total' => $total
]);
?>
