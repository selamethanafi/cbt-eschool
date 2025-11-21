<?php
session_start();
include '../koneksi/koneksi.php';

if (isset($_SESSION['siswa_id'])) {
    $id = $_SESSION['siswa_id'];
    $now = date('Y-m-d H:i:s');

    // Perbarui page_url dan last_activity dalam satu query
    if (isset($_POST['page_url'])) {
        $page_url = $_POST['page_url'];

        // Update page_url dan last_activity di tabel siswa
        $stmt = mysqli_prepare($koneksi, "UPDATE siswa SET page_url = ?, last_activity = ? WHERE id_siswa = ?");
        
        if (!$stmt) {
            die("Prepare failed: " . mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param($stmt, 'ssi', $page_url, $now, $id);
        $execute_result = mysqli_stmt_execute($stmt);

        if (!$execute_result) {
            die("Execute failed: " . mysqli_error($koneksi));
        }

        mysqli_stmt_close($stmt);
    }
}
?>
