<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$versi_baru = $data['versi_baru'] ?? '';
$url = $data['url'] ?? '';

if (!$versi_baru || !$url) {
    echo json_encode(['success' => false, 'message' => 'Data tidak lengkap']);
    exit;
}

$tmp_zip = __DIR__ . '/update.zip';
$folder_extract = __DIR__ . '/update_temp/';

file_put_contents($tmp_zip, file_get_contents($url));
$zip = new ZipArchive();
if ($zip->open($tmp_zip) === TRUE) {
    $zip->extractTo($folder_extract);
    $zip->close();

    // Menyalin file update (ini disederhanakan - sebaiknya backup & validasi)
    $subfolder = scandir($folder_extract);
    foreach ($subfolder as $folder) {
        if ($folder !== '.' && $folder !== '..') {
            $source = $folder_extract . $folder . '/';
            shell_exec("cp -r {$source}* ../"); // sesuaikan path sesuai struktur server
            break;
        }
    }

    unlink($tmp_zip);

    // Hapus folder update_temp
    @shell_exec("rm -rf " . escapeshellarg($folder_extract));

    // Fallback jika folder masih ada
    if (is_dir($folder_extract)) {
        function hapusFolder($folderPath) {
            if (!is_dir($folderPath)) return;
            $items = scandir($folderPath);
            foreach ($items as $item) {
                if ($item === '.' || $item === '..') continue;
                $path = $folderPath . DIRECTORY_SEPARATOR . $item;
                if (is_dir($path)) {
                    hapusFolder($path);
                } else {
                    @unlink($path);
                }
            }
            @rmdir($folderPath);
        }

        hapusFolder($folder_extract);
    }

    // Simpan versi ke database
    include '../koneksi/koneksi.php';
    mysqli_query($koneksi, "UPDATE pengaturan SET versi_aplikasi = '$versi_baru' WHERE id = 1");

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal ekstrak file ZIP']);
}
