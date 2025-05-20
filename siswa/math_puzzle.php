<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

// Jika sudah klik mulai, reset skor, nyawa, dan waktu mulai
if (isset($_POST['mulai'])) {
    $_SESSION['score'] = 0;
    $_SESSION['lives'] = 3; // Fixed typo from 'lives' to 'lives'
    $_SESSION['start_time'] = time();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Jika skor dan lives belum ada, tampilkan halaman penjelasan
if (!isset($_SESSION['score']) || !isset($_SESSION['lives'])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Penjelasan Math Puzzle</title>
    <?php include '../inc/css.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #4a6bff;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }
        h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: #4a6bff;
            border-radius: 2px;
        }
        ul {
            font-size: 1.1rem;
            line-height: 1.8;
            padding-left: 20px;
            color: #555;
        }
        ul li {
            margin-bottom: 15px;
            position: relative;
            padding-left: 30px;
        }
        ul li:before {
            content: '•';
            color: #4a6bff;
            font-size: 1.5rem;
            position: absolute;
            left: 0;
            top: -5px;
        }
        button {
            background: linear-gradient(to right, #4a6bff, #6a11cb);
            border: none;
            padding: 15px 25px;
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            margin-top: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(74, 107, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 107, 255, 0.6);
        }
        .game-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Penjelasan Fitur Math Puzzle</h2>
        <ul>
            <li><i class="fas fa-heart" style="color: #ff4757;"></i> <strong>Nyawa 3:</strong> Kalau salah, nyawa berkurang 1. Jika nyawa habis, game over, skor disimpan ke database (update jika lebih tinggi), lalu langsung ke leaderboard.</li>
            <li><i class="fas fa-star" style="color: #ffd700;"></i> <strong>Skor:</strong> Bertambah 10 poin tiap jawaban benar, dan tidak berkurang poin kalau salah (tapi nyawa berkurang).</li>
            <li><i class="fas fa-level-up-alt" style="color: #2ed573;"></i> <strong>Level:</strong> Naik tiap 30 detik, sehingga angka soal semakin besar (dari 1..10, 1..20, dst) agar soal makin menantang.</li>
            <li><i class="fas fa-clock" style="color: #3742fa;"></i> <strong>Timer:</strong> 10 detik per soal. Jika waktu habis, jawaban kosong otomatis dianggap salah dan soal akan submit otomatis.</li>
        </ul>
        <form method="POST" action="">
            <button type="submit" name="mulai">
                <i class="fas fa-play-circle game-icon"></i> Mulai Game
            </button>
        </form>
    </div>
</body>
</html>
<?php
exit; // stop, jangan lanjut ke game sebelum mulai
endif;

// Hitung level berdasarkan waktu main (start_time)
$elapsed = time() - $_SESSION['start_time'];
$level = floor($elapsed / 30) + 1;
if ($level > 5) $level = 5; // batasi level maksimal 5

// Atur angka maksimal sesuai level
$maxNumber = 10 * $level;

// Pilih operator acak dan angka acak sesuai level
$ops = ['+', '-', '*'];
$op = $ops[array_rand($ops)];
$a = rand(1, $maxNumber);
$b = rand(1, $maxNumber);

// Hitung hasil soal
$hasil = eval("return $a $op $b;");

$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jawaban'])) {
    $jawaban = trim($_POST['jawaban']);
    $jawaban_benar = intval($_POST['jawaban_benar']);

    if ($jawaban !== '' && intval($jawaban) === $jawaban_benar) {
        $_SESSION['score'] += 10;
        $feedback = "<div class='feedback-correct'><i class='fas fa-check-circle'></i> Benar! +10 poin</div>";
    } else {
        $_SESSION['lives'] -= 1;
        $feedback = "<div class='feedback-wrong'><i class='fas fa-times-circle'></i> Salah! Jawaban benar: $jawaban_benar</div>";
    }

    // Jika nyawa habis, simpan skor dan redirect ke leaderboard
    if ($_SESSION['lives'] <= 0) {
        $nama_game = 'math_puzzle';
        $skor_akhir = $_SESSION['score']; // simpan skor sebelum unset
    
        // Cek skor sebelumnya
        $cek = $koneksi->prepare("SELECT skor FROM skor_game WHERE id_siswa = ? AND nama_game = ?");
        $cek->bind_param("is", $id_siswa, $nama_game);
        $cek->execute();
        $res = $cek->get_result();
    
        if ($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if ($skor_akhir > $row['skor']) {
                $upd = $koneksi->prepare("UPDATE skor_game SET skor = ? WHERE id_siswa = ? AND nama_game = ?");
                $upd->bind_param("iis", $skor_akhir, $id_siswa, $nama_game);
                $upd->execute();
            }
        } else {
            $ins = $koneksi->prepare("INSERT INTO skor_game (id_siswa, nama_game, skor) VALUES (?, ?, ?)");
            $ins->bind_param("isi", $id_siswa, $nama_game, $skor_akhir);
            $ins->execute();
        }
    
        // Reset session setelah simpan skor
        unset($_SESSION['score'], $_SESSION['lives'], $_SESSION['start_time']);
    
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
                text: 'Skor Anda: $skor_akhir',
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
    }
    
    // Refresh halaman untuk soal baru
    echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
    exit;
}

$nama = $nama_siswa;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Math Puzzle</title>
    <?php include '../inc/css.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .wrapper {
            padding: 20px;
        }
        .game-card {
            max-width: 600px;
            margin: 0 auto;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .card-header {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(to right, #4a6bff, #6a11cb);
            text-align: center;
            padding: 15px;
            border-bottom: none;
        }
        .card-body {
            background: white;
            padding: 30px;
        }
        .player-name {
            font-size: 1.2rem;
            color: #555;
            margin-bottom: 15px;
        }
        .status-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
        }
        .status-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .status-label {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 5px;
        }
        .status-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #333;
        }
        .lives {
            display: flex;
            gap: 5px;
        }
        .heart {
            color: #ff4757;
            font-size: 1.5rem;
        }
        .heart.empty {
            color: #ddd;
        }
        .question {
            text-align: center;
            margin: 30px 0;
        }
        .question-text {
            font-size: 3rem;
            font-weight: 700;
            color: #4a6bff;
            margin: 20px 0;
        }
        .answer-input {
            width: 100%;
            padding: 15px;
            font-size: 1.8rem;
            text-align: center;
            border: 3px solid #e0e0e0;
            border-radius: 12px;
            transition: all 0.3s;
            margin-bottom: 15px;
        }
        .answer-input:focus {
            border-color: #4a6bff;
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
            outline: none;
        }
        .btn-submit {
            background: linear-gradient(to right, #4a6bff, #6a11cb);
            border: none;
            padding: 15px;
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
            border-radius: 12px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(74, 107, 255, 0.4);
        }
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 107, 255, 0.6);
        }
        .btn-submit:active {
            transform: translateY(1px);
        }
        .feedback {
            text-align: center;
            margin: 20px 0;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .feedback-correct {
            color: #2ed573;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .feedback-wrong {
            color: #ff4757;
            font-size: 1.3rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #timer {
            color: #ff4757;
            font-weight: 900;
            font-size: 1.5rem;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .btn-finish {
            background: #ffa502;
            border: none;
            padding: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            border-radius: 12px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 20px;
            box-shadow: 0 3px 10px rgba(255, 165, 2, 0.3);
        }
        .btn-finish:hover {
            background: #e67e22;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 165, 2, 0.4);
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="main">
        <main class="content">
            <div class="container-fluid p-0">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-12">
                        <div class="card game-card">
                            <div class="card-header">
                                Math Puzzle - Level <?= $level ?>
                            </div>
                            <div class="card-body">
                                <div class="player-name">
                                    <i class="fas fa-user"></i> <?= htmlspecialchars($nama) ?>
                                </div>
                                
                                <div class="status-bar">
                                    <div class="status-item">
                                        <div class="status-label">Skor</div>
                                        <div class="status-value"><?= $_SESSION['score'] ?></div>
                                    </div>
                                    <div class="status-item">
                                        <div class="status-label">Nyawa</div>
                                        <div class="lives">
                                            <?php for ($i = 1; $i <= 3; $i++): ?>
                                                <i class="fas fa-heart heart <?= $i > $_SESSION['lives'] ? 'empty' : '' ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="status-item">
                                        <div class="status-label">Waktu</div>
                                        <div class="status-value"><span id="timer">10</span>s</div>
                                    </div>
                                </div>

                                <form method="POST" id="formSoal" autocomplete="off">
                                    <div class="question">
                                        <div class="question-text"><?= "$a $op $b = ?" ?></div>
                                        <input type="number" name="jawaban" class="answer-input" required autofocus>
                                        <input type="hidden" name="jawaban_benar" value="<?= $hasil ?>">
                                        <button type="submit" class="btn-submit">
                                            <i class="fas fa-paper-plane"></i> Jawab
                                        </button>
                                    </div>
                                </form>

                                <div class="feedback">
                                    <?= $feedback ?>
                                </div>

                                <form method="POST" action="selesai.php">
                                    <button type="submit" class="btn-finish">
                                        <i class="fas fa-flag-checkered"></i> Selesai Main
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include '../inc/js.php'; ?>
<?php include '../inc/check_activity.php'; ?>
<script>
    let time = 10;
    const timerEl = document.getElementById('timer');
    const form = document.getElementById('formSoal');

    const countdown = setInterval(() => {
        time--;
        timerEl.textContent = time;
        if (time <= 0) {
            clearInterval(countdown);
            // Submit jawaban kosong otomatis dianggap salah
            form.querySelector('input[name="jawaban"]').value = '';
            form.submit();
        }
    }, 1000);

    // Reset timer saat form disubmit manual
    form.addEventListener('submit', () => {
        clearInterval(countdown);
    });
</script>
<script>
window.addEventListener('beforeunload', function (e) {
    // Kirim data game over ke server secara sinkron (blocking)
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "game_over.php", false); // false = synchronous request
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("action=game_over&score=" + encodeURIComponent(<?= $_SESSION['score'] ?? 0 ?>));

    // Tidak perlu set returnValue kecuali mau konfirmasi dialog
});
let isSubmitting = false;
const form = document.getElementById('formSoal');
form.addEventListener('submit', () => {
    isSubmitting = true; // tandai submit normal
    clearInterval(countdown); // stop timer
});
window.addEventListener('beforeunload', function (e) {
    if (!isSubmitting) {
        // Hanya kirim game over kalau bukan submit form
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "game_over.php", false);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send("action=game_over");
    }
});
</script>
</body>
</html>