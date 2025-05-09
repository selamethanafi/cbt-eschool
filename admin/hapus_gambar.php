<?php
// hapus_gambar.php

// Pastikan ada data file yang diterima
if (isset($_POST['files']) && !empty($_POST['files'])) {
    $files = $_POST['files'];  // Mendapatkan daftar file yang akan dihapus
    $response = [];

    foreach ($files as $file) {
        $filePath = "../gambar/" . $file;

        // Cek apakah file ada dan apakah file tersebut dapat dihapus
        if (file_exists($filePath)) {
            if (is_writable($filePath)) {
                if (unlink($filePath)) {
                    $response[] = ['file' => $file, 'status' => 'success', 'message' => 'Gambar berhasil dihapus'];
                } else {
                    $response[] = ['file' => $file, 'status' => 'error', 'message' => 'Gagal menghapus gambar, file tidak dapat dihapus'];
                }
            } else {
                $response[] = ['file' => $file, 'status' => 'error', 'message' => 'Gagal menghapus gambar, file tidak memiliki izin untuk dihapus'];
            }
        } else {
            $response[] = ['file' => $file, 'status' => 'error', 'message' => 'File tidak ditemukan'];
        }
    }
    
    // Kembalikan respons dalam format JSON
    echo json_encode(['success' => true, 'files' => $response]);
} else {
    echo json_encode(['success' => false, 'message' => 'Tidak ada file yang dipilih untuk dihapus']);
}
