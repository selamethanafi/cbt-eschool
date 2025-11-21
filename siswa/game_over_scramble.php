<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');

// Simpan skor ke database sebelum menghapus session
if (isset($_SESSION['scramble'])) {
    $id_siswa = $_SESSION['siswa_id'] ?? 0;
    $skor = $_SESSION['scramble']['skor'] ?? 0;
    
    // Pastikan skor adalah integer
    $skor = (int)$skor;
    
    // Simpan atau update skor ke database
    $sql = $koneksi->prepare("SELECT id FROM skor_game WHERE id_siswa = ? AND nama_game = 'scramble'");
    $sql->bind_param("i", $id_siswa);
    $sql->execute();
    $sql->store_result();
    
    if ($sql->num_rows > 0) {
        // Update skor jika sudah ada
        $update = $koneksi->prepare("UPDATE skor_game SET skor = GREATEST(skor, ?), waktu = NOW() WHERE id_siswa = ? AND nama_game = 'scramble'");
        $update->bind_param("ii", $skor, $id_siswa);
        $update->execute();
        $update->close();
    } else {
        // Insert skor baru jika belum ada
        $insert = $koneksi->prepare("INSERT INTO skor_game (id_siswa, nama_game, skor, waktu) VALUES (?, 'scramble', ?, NOW())");
        $insert->bind_param("ii", $id_siswa, $skor);
        $insert->execute();
        $insert->close();
    }
    
    $sql->close();
}

// Hapus session scramble
unset($_SESSION['scramble']);

// Redirect ke halaman game.php dengan parameter skor
header("Location: game.php?log=1&skor=" . ($skor ?? 0));
exit;
?>