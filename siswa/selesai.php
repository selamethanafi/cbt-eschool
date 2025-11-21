<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/datasiswa.php';

if (!isset($_SESSION['siswa_id'])) {
    header('Location: ../login.php');
    exit;
}

$id_siswa = $_SESSION['siswa_id'];
$skor = $_SESSION['score'] ?? 0;
$nama_game = 'math_puzzle';

// Simpan skor ke DB
$stmt = $koneksi->prepare("SELECT skor FROM skor_game WHERE id_siswa = ? AND nama_game = ?");
$stmt->bind_param("is", $id_siswa, $nama_game);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if ($skor > $row['skor']) {
        $stmt = $koneksi->prepare("UPDATE skor_game SET skor = ? WHERE id_siswa = ? AND nama_game = ?");
        $stmt->bind_param("iis", $skor, $id_siswa, $nama_game);
        $stmt->execute();
    }
} else {
    $stmt = $koneksi->prepare("INSERT INTO skor_game (id_siswa, nama_game, skor) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $id_siswa, $nama_game, $skor);
    $stmt->execute();
}

// Bersihkan session game
unset($_SESSION['score'], $_SESSION['lives'], $_SESSION['current_question'], $_SESSION['start_time'], $_SESSION['question_data']);

// Tampilkan alert lalu redirect ke leaderboard di game.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Game Selesai</title>
<script src="../assets/js/sweetalert.js"></script>
<link rel="icon" type="image/png" href="../assets/images/icon.png" />
</head>
<body>
<script>
Swal.fire({
    icon: 'info',
    title: 'Game Over',
    text: 'Skor Anda: <?= $skor ?>',
    allowOutsideClick: false,
    allowEscapeKey: false,
    confirmButtonText: 'Lihat Leaderboard',
    backdrop: `
        rgba(0,0,123,0.4)
        url('../assets/images/nyan-cat.gif')
        left top
        no-repeat
    `
}).then(() => {
    window.location.href = 'game.php?game=math_puzzle';
});
</script>
</body>
</html>
