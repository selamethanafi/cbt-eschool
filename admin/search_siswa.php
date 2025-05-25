<?php
include '../koneksi/koneksi.php';

$search = $_GET['query'] ?? '';

$query = mysqli_query($conn, "SELECT id, nama FROM siswa WHERE nama LIKE '%$search%' LIMIT 10");

$result = [];
while ($row = mysqli_fetch_assoc($query)) {
  $result[] = $row;
}

echo json_encode($result);
