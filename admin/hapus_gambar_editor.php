<?php
// hapus_gambar.php
header('Content-Type: application/json');

// Cek apakah ada parameter 'src' yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['src'])) {
    $src = $_POST['src'];  // Mendapatkan URL gambar yang akan dihapus
    
    // Ambil path relatif gambar
    $path = parse_url($src, PHP_URL_PATH); // Misalnya: ../gambar/gambar.jpg
    
    // Tentukan folder gambar relatif terhadap root
    $gambarDir = $_SERVER['DOCUMENT_ROOT'] . '/gambar'; // Sesuaikan dengan direktori gambar Anda
    
    // Gabungkan dengan path gambar untuk mendapatkan path absolut
    $file = realpath($gambarDir . $path);  // Menghitung path absolut gambar
    
    // Mengecek apakah file berada dalam folder gambar
    if ($file && strpos($file, $gambarDir) === 0) {
        if (file_exists($file)) {
            // Jika file ada, hapus file tersebut
            unlink($file);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'file_not_found']);
        }
    } else {
        echo json_encode(['status' => 'invalid_access']);
    }
} else {
    echo json_encode(['status' => 'invalid_request']);
}
