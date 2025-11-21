<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/datasiswa.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'game_over') {
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
    $nama_game = 'math_puzzle';
    $id_siswa = $_SESSION['siswa_id'];

    // Cek skor sebelumnya
    $cek = $koneksi->prepare("SELECT skor FROM skor_game WHERE id_siswa = ? AND nama_game = ?");
    $cek->bind_param("is", $id_siswa, $nama_game);
    $cek->execute();
    $res = $cek->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if ($score > $row['skor']) {
            $upd = $koneksi->prepare("UPDATE skor_game SET skor = ? WHERE id_siswa = ? AND nama_game = ?");
            $upd->bind_param("iis", $score, $id_siswa, $nama_game);
            $upd->execute();
        }
    } else {
        $ins = $koneksi->prepare("INSERT INTO skor_game (id_siswa, nama_game, skor) VALUES (?, ?, ?)");
        $ins->bind_param("isi", $id_siswa, $nama_game, $score);
        $ins->execute();
    }

    // Bersihkan session game
    unset($_SESSION['score'], $_SESSION['lives'], $_SESSION['start_time'], $_SESSION['current_question'], $_SESSION['question_data']);

    http_response_code(200);
    exit;
}
?>
