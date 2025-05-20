<?php
$id_admin = $_SESSION['admin_id']; // Ambil ID admin dari session
if (!$id_admin) {
    header("Location: login.php");
    exit;
}
// Query untuk mengambil data admin berdasarkan ID
$query = "SELECT * FROM admins WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_admin);
if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if ($admin = mysqli_fetch_assoc($result)) {
        $nama_admin = $admin['nama_admin']; // Ambil nama admin
        $id_saya = $admin['id']; // Ambil id admin
    } else {
        echo "Admin tidak ditemukan.";
        exit;
    }
} else {
    echo "Error executing query.";
    exit;
}
?>