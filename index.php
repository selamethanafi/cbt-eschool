<?php
// Cek apakah file koneksi.php sudah ada (berarti sudah instalasi)
if (!file_exists(__DIR__ . '/koneksi/koneksi.php')) {
    // Jika belum ada, arahkan ke folder install
    header("Location: install/");
    exit;
}

// Jika sudah ada, arahkan ke halaman login siswa
header("Location: siswa/login.php");
exit;
?>
