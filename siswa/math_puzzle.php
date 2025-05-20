<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';
// Cek login siswa
if (!isset($_SESSION['siswa_id'])) {
    header('Location: ../login.php');
    exit;
}
$id_siswa = $_SESSION['siswa_id'];

// Konfigurasi game
$max_lives = 3;
$max_time = 10; // 10 detik per soal
$nama_game = 'math_puzzle';

// Set session game kalau belum ada
if (!isset($_SESSION['score'])) $_SESSION['score'] = 0;
if (!isset($_SESSION['lives'])) $_SESSION['lives'] = $max_lives;
if (!isset($_SESSION['current_question'])) $_SESSION['current_question'] = 1;
if (!isset($_SESSION['start_time']) || !is_numeric($_SESSION['start_time'])) $_SESSION['start_time'] = time();
if (!isset($_SESSION['question_data'])) {
    $_SESSION['question_data'] = [];
}

// Fungsi generate soal sesuai level (semakin tinggi level, semakin sulit)
// Fungsi generate soal sesuai skor (semakin tinggi skor, semakin sulit)
function generate_question($score) {
    $level = floor($score / 100) + 1;
    $max_num = 10 + ($level - 1) * 5;
    
    // Pilih operator dengan probabilitas lebih seimbang
    $ops = ['+', '+', '-', '-', '*']; // Tambah peluang + dan -
    $op = $ops[array_rand($ops)];

    $num1 = rand(1, $max_num);
    $num2 = rand(1, $max_num);

    if ($op == '-' && $num1 < $num2) {
        list($num1, $num2) = [$num2, $num1];
    }

    switch ($op) {
        case '+': $answer = $num1 + $num2; break;
        case '-': $answer = $num1 - $num2; break;
        case '*': $answer = $num1 * $num2; break;
    }

    return [
        'question' => "$num1 $op $num2",
        'answer' => $answer
    ];
}

// Ambil soal sekarang
$current_question = $_SESSION['current_question'];

if (!isset($_SESSION['question_data'][$current_question])) {
    $_SESSION['question_data'][$current_question] = generate_question($_SESSION['score']);
}

$question = $_SESSION['question_data'][$current_question]['question'];
$correct_answer = $_SESSION['question_data'][$current_question]['answer'];

// Hitung sisa waktu
$elapsed = time() - $_SESSION['start_time'];
$remaining_time = $max_time - $elapsed;

// Kalau waktu habis
if ($remaining_time <= 0) {
    $_SESSION['lives']--;
    if ($_SESSION['lives'] <= 0) {
        // Game over, simpan skor dan redirect ke selesai.php
        header("Location: selesai.php");
        exit;
    } else {
        // Kurangi nyawa, reset timer dan soal baru
        $_SESSION['start_time'] = time();
        $_SESSION['current_question']++;
        header("Location: math_puzzle.php");
        exit;
    }
}

// Proses submit jawaban
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['jawab'])) {
    $user_answer = trim($_POST['jawaban']);
    if ($user_answer === '') {
        echo "<script src='../assets/js/sweetalert.js'></script>
        <script>
        Swal.fire('Oops','Jawaban tidak boleh kosong','warning');
        </script>";
    } elseif (!is_numeric($user_answer)) {
        echo "<script src='../assets/js/sweetalert.js'></script>
        <script>
        Swal.fire('Oops','Jawaban harus berupa angka','warning');
        </script>";
    } else {
        if (intval($user_answer) === $correct_answer) {
            // Jawaban benar, skor bertambah dan soal naik level
            $_SESSION['score'] += 10;
            $_SESSION['current_question']++;
            $_SESSION['start_time'] = time();
            header("Location: math_puzzle.php");
            exit;
        } else {
            // Jawaban salah, nyawa berkurang
            $_SESSION['lives']--;
            if ($_SESSION['lives'] <= 0) {
                header("Location: selesai.php");
                exit;
            } else {
                $_SESSION['start_time'] = time();
                $_SESSION['current_question']++;
                header("Location: math_puzzle.php");
                exit;
            }
        }
    }
}

// Tombol selesai ditekan
if (isset($_POST['selesai'])) {
    header("Location: selesai.php");
    exit;
}
$q = mysqli_query($koneksi, "SELECT * FROM pengaturan WHERE id = 1");
$data = mysqli_fetch_assoc($q);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Math Puzzle Game</title>
<link rel="stylesheet" href="../assets/css/style.css" />
<link rel="icon" type="image/png" href="../assets/images/icon.png" />
<link href="../assets/fontawesome/css/all.min.css" rel="stylesheet">
<script src="../assets/js/sweetalert.js"></script>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        margin: 0; padding: 0;
        display: flex; justify-content: center; align-items: center; min-height: 100vh;
    }
    .game-container {
        background: #2c2f48;
        padding: 25px 30px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.5);
        width: 400px;
        text-align: center;
        position: relative;
    }
    .score-lives {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        font-weight: bold;
        font-size: 18px;
    }
    .question-box {
        background: #3b3f68;
        padding: 20px;
        border-radius: 12px;
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 20px;
        letter-spacing: 2px;
    }
    .timer {
        font-size: 24px;
        margin-bottom: 20px;
        color: #ffd700;
        font-weight: 600;
    }
    form input[type="number"] {
        width: 100%;
        font-size: 24px;
        padding: 10px 15px;
        border-radius: 10px;
        border: none;
        outline: none;
        margin-bottom: 20px;
        box-sizing: border-box;
    }
    form .btn-group {
        display: flex;
        justify-content: space-between;
        gap: 15px;
    }
    form button {
        flex: 1;
        font-size: 18px;
        font-weight: 700;
        padding: 12px 0;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        user-select: none;
    }
    form button#jawab-btn {
        background-color: #22c55e;
        color: white;
    }
    form button#jawab-btn:hover {
        background-color: #16a34a;
    }
    form button#selesai-btn {
        background-color: #ef4444;
        color: white;
    }
    form button#selesai-btn:hover {
        background-color: #b91c1c;
    }
    /* Nyawa hearts */
    .lives {
        font-size: 24px;
        color: #ff4d4d;
        user-select: none;
    }
    .player-name {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #a5b4fc;
    text-align: center;
    user-select: none;
}
</style>

<script>
// Timer countdown
let remainingTime = <?= $remaining_time ?>;
function updateTimer() {
    const timerEl = document.getElementById('timer');
    if (remainingTime <= 0) {
        // Submit form kosong supaya backend bisa proses waktu habis
        document.getElementById('jawaban').value = '';
        document.getElementById('quiz-form').submit();
        return;
    }
    timerEl.textContent = remainingTime + ' detik';
    remainingTime--;
    setTimeout(updateTimer, 1000);
}

window.onload = () => {
    updateTimer();
    document.getElementById('jawaban').focus();
};
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
    var base64Text = "<?php echo $encryptedText; ?>";
    var versiSaya = "<?= $data['versi_aplikasi'] ?? '1.0.0' ?>"; // ambil dari database

    if (base64Text) {
        var decodedText = atob(base64Text);
        document.getElementById("enc").innerHTML = decodedText + " v." + versiSaya;
    } else {
        document.getElementById("enc").innerHTML = "v." + versiSaya;
    }
});
</script>
</head>
<body>
<div class="game-container" role="main" aria-live="polite">

    <div class="player-name" aria-label="Nama pemain">
    <i class="fa fa-user-circle" aria-hidden="true"></i> <?= htmlspecialchars($nama_siswa) ?>
    </div>

    <div class="score-lives">
        <div>Score: <?= $_SESSION['score'] ?></div>
        <div>Level: <?= floor($_SESSION['score'] / 100) + 1 ?></div>
        <div class="lives" aria-label="Nyawa tersisa">
            <?= str_repeat('❤️', $_SESSION['lives']) ?>
        </div>
    </div>

    <div class="question-box" aria-label="Soal matematika">
        <?= htmlspecialchars($question) ?>
    </div>

    <div class="timer" id="timer" aria-live="assertive" aria-atomic="true"></div>

    <form method="POST" id="quiz-form" autocomplete="off" novalidate>
        <input type="number" id="jawaban" name="jawaban" aria-label="Masukkan jawaban" required />
        <div class="btn-group">
            <button type="submit" id="jawab-btn" name="jawab" aria-label="Jawab soal">Jawab</button>
            <button type="submit" id="selesai-btn" name="selesai" aria-label="Selesai bermain">Selesai</button>
        </div>
    </form>
    <div class="col-6 text-start">
                                                                <p class="mb-0">
                                                                    <a href="#" id="enc" style="color:grey;text-decoration:none;"></a>
                                                                </p>
                                                            </div>
</div>
<?php include '../inc/check_activity.php'; ?>
</body>
</html>
