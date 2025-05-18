<?php
include '../koneksi/koneksi.php'; // ganti dengan path koneksi Anda

$query = mysqli_query($koneksi, "SELECT question, answer FROM faq");
$faq = [];

while ($row = mysqli_fetch_assoc($query)) {
    $faq[$row['question']] = $row['answer'];
}

header('Content-Type: application/json');
echo json_encode($faq, JSON_UNESCAPED_UNICODE);
?>