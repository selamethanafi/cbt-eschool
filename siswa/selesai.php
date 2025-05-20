<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

$id_siswa = $id_siswa ?? $dataSiswa['id_siswa'] ?? null;
if (!$id_siswa) {
    die("Error: ID siswa tidak ditemukan.");
}

if (!isset($_SESSION['score'])) {
    header("Location: math_puzzle.php");
    exit;
}

$skor = $_SESSION['score'];
$nama_game = 'math_puzzle';

// Cek apakah data sudah ada
$stmt = $koneksi->prepare("SELECT skor FROM skor_game WHERE id_siswa = ? AND nama_game = ?");
$stmt->bind_param("is", $id_siswa, $nama_game);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Data ada, update jika skor baru lebih tinggi
    if ($skor > $row['skor']) {
        $stmt = $koneksi->prepare("UPDATE skor_game SET skor = ? WHERE id_siswa = ? AND nama_game = ?");
        $stmt->bind_param("iis", $skor, $id_siswa, $nama_game);
        $stmt->execute();
    }
} else {
    // Data belum ada, insert baru
    $stmt = $koneksi->prepare("INSERT INTO skor_game (id_siswa, nama_game, skor) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $id_siswa, $nama_game, $skor);
    $stmt->execute();
}

unset($_SESSION['score']);
 // Tampilkan SweetAlert lalu redirect ke game.php
 echo '
 <!DOCTYPE html>
 <html>
 <head>
 <link rel="icon" type="image/png" href="../assets/images/icon.png" />
 <script src="../assets/js/sweetalert.js"></script>
 </head>
 <body>';
 echo "<script>
     Swal.fire({
         icon: 'info',
         title: 'Game Over',
         text: 'Skor Anda: $skor',
         confirmButtonText: 'OK',
         background: '#fff',
         backdrop: `
             rgba(0,0,123,0.4)
             url('../assets/images/nyan-cat.gif')
             left top
             no-repeat
         `
     }).then(() => {
         window.location.href = 'game.php?game=math_puzzle';
     });
 </script>";
 echo '</body></html>';
 exit;
