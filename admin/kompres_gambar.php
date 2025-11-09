<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

// Lokasi folder gambar
$folder = realpath(__DIR__ . '/../gambar');
$progressFile = __DIR__ . '/progress.txt';

// Cek folder
if (!$folder || !is_dir($folder)) {
die("Folder 'gambar' tidak ditemukan.");
}

// Buat nama random untuk file tar.gz
$random = bin2hex(random_bytes(4)); // contoh: a3f9c2d1
$filename = 'backup_' . date('Ymd_His') . '_' . $random . '.tar.gz';
$tarTemp  = $folder . '/temp_' . $random . '.tar';   // file sementara .tar
$gzFile   = $folder . '/' . $filename;   // file hasil final .tar.gz

// Reset progress
file_put_contents($progressFile, "0");

// Ambil daftar file dalam folder
$files = new RecursiveIteratorIterator(
new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS)
);

$fileList = iterator_to_array($files);
$totalFiles = count($fileList);
$current = 0;

try {
	// Buat file TAR
	$tar = new PharData($tarTemp);
	foreach ($fileList as $file) {
		$current++;
	$localPath = str_replace($folder . DIRECTORY_SEPARATOR, '', $file->getPathname());
	$tar->addFile($file->getPathname(), $localPath);
	file_put_contents($progressFile, round(($current / $totalFiles) * 100, 1));
	}

	// Kompres ke .tar.gz
	$tar->compress(Phar::GZ);
	// Hapus file tar asli
	unlink($tarTemp);

	// -----------------------------------------
	// Pindahkan file ke luar folder admin
	// -----------------------------------------
	$destinationFolder = dirname(__DIR__) . '/backup/';
	if (!is_dir($destinationFolder)) {
	mkdir($destinationFolder, 0755, true);
	}
	$finalPath = $destinationFolder . $filename;
	rename($tarTemp . '.gz', $finalPath);
	// Perbarui progress
	file_put_contents($progressFile, "100");
	// Simpan nama file ke database
	$stmt = $koneksi->prepare("INSERT INTO gambar (filename, created_at) VALUES (?, NOW())");
	$stmt->bind_param("s", $filename);
	$stmt->execute();
	$stmt->close();
	$koneksi->close();
	// ======================================
	// HAPUS BACKUP LAMA (SIMPAN HANYA 5 FILE)
	// ======================================
	$destinationFolder = dirname(__DIR__) . '/backup/';
	// Ambil daftar file backup
	$backupFiles = glob($destinationFolder . 'backup_*.tar.gz');
	// Jika jumlah file lebih dari 5 → hapus yang paling lama
	if (count($backupFiles) > 5) 
	{
	
		// Urutkan berdasarkan waktu file (yang lama dulu)
		usort($backupFiles, function($a, $b) {
		return filemtime($a) <=> filemtime($b);
		});
		// Hitung berapa yang harus dihapus
		$toDelete = count($backupFiles) - 5;
		// Hapus file paling lama
		for ($i = 0; $i < $toDelete; $i++) {
		unlink($backupFiles[$i]);
		}
	}
} catch (Exception $e) {
file_put_contents($progressFile, "ERROR: " . $e->getMessage());
}

