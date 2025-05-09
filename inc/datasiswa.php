<?php
$id_siswa = $_SESSION['siswa_id']; // Ambil ID siswa dari session

// Query untuk mengambil data siswa berdasarkan ID
$query = "SELECT * FROM siswa WHERE id_siswa = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_siswa);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($user = mysqli_fetch_assoc($result)) {
        $nama_siswa = $user['nama_siswa'];
    } else {
        echo "Siswa tidak ditemukan.";
        exit;
    }
} else {
    echo "Error executing query.";
    exit;
}
?>