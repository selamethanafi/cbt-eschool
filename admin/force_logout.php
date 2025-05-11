<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        throw new Exception('Method not allowed');
    }

    if (!isset($_POST['id_siswa'])) {
        throw new Exception('ID siswa tidak valid');
    }

    $id_siswa = intval($_POST['id_siswa']);
    
    $query = "UPDATE siswa SET session_token = NULL, last_activity = NULL, force_logout = TRUE WHERE id_siswa = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    
    if (!$stmt) {
        throw new Exception('Persiapan query gagal: ' . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, "i", $id_siswa);
    
    if (mysqli_stmt_execute($stmt)) {
        $response['success'] = true;
        $response['message'] = 'Siswa berhasil di-logout paksa';
    } else {
        throw new Exception('Eksekusi query gagal: ' . mysqli_stmt_error($stmt));
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    echo json_encode($response);
    exit;
}
?>