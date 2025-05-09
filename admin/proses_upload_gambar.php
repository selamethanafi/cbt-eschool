<?php
session_start();

$targetDir = "../gambar/";
$allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$maxSize = 2 * 1024 * 1024; // 2MB
$responses = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['gambar'])) {
    $files = $_FILES['gambar'];

    for ($i = 0; $i < count($files['name']); $i++) {
        $filename = basename($files['name'][$i]);
        $targetFile = $targetDir . $filename;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validasi jika file sudah ada
        if (file_exists($targetFile)) {
            $responses[] = [
                'file' => $filename,
                'status' => 'error',
                'message' => 'Nama file sudah ada'
            ];
            continue;
        }

        // Validasi ekstensi
        if (!in_array($imageFileType, $allowedTypes)) {
            $responses[] = [
                'file' => $filename,
                'status' => 'error',
                'message' => 'Format tidak didukung'
            ];
            continue;
        }

        // Validasi ukuran
        if ($files['size'][$i] > $maxSize) {
            $responses[] = [
                'file' => $filename,
                'status' => 'error',
                'message' => 'Ukuran terlalu besar (maks 2MB)'
            ];
            continue;
        }

        // Validasi isi gambar
        if (!getimagesize($files["tmp_name"][$i])) {
            $responses[] = [
                'file' => $filename,
                'status' => 'error',
                'message' => 'Bukan file gambar valid'
            ];
            continue;
        }

        // Upload
        if (move_uploaded_file($files["tmp_name"][$i], $targetFile)) {
            $responses[] = [
                'file' => $filename,
                'status' => 'success',
                'message' => 'Berhasil diupload'
            ];
        } else {
            $responses[] = [
                'file' => $filename,
                'status' => 'error',
                'message' => 'Gagal upload'
            ];
        }
    }

    echo json_encode($responses);
}
