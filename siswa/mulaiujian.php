<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_siswa = $_POST['id_siswa'] ?? '';
    $kode_soal = $_POST['kode_soal'] ?? '';
    $token_input = trim($_POST['token'] ?? '');
    $konfirmasi_token = $_POST['konfirmasi_token'] ?? '';

    if (!$id_siswa || !$kode_soal || !$token_input) {
        $_SESSION['alert'] = true;
        $_SESSION['warning_message'] = 'Data tidak lengkap.';
        header('Location: ujian.php');
        exit;
    }

    if (!isset($konfirmasi_token)) {
        $_SESSION['alert'] = true;
        $_SESSION['warning_message'] = 'Akses tidak valid. Silakan mulai dari halaman konfirmasi.';
        header('Location: ujian.php');
        exit;
    }

    $q_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal = '$kode_soal'");
    $data_soal = mysqli_fetch_assoc($q_soal);

    if (!$data_soal) {
        $_SESSION['alert'] = true;
        $_SESSION['warning_message'] = 'Soal tidak ditemukan.';
        header('Location: ujian.php');
        exit;
    }

    $token_soal = trim($data_soal['token']);
    if ($token_soal !== $token_input) {
        $_SESSION['alert'] = true;
        $_SESSION['warning_message'] = 'Token tidak valid.';
        header("Location: konfirmasi_ujian.php?kode_soal=$kode_soal");
        exit;
    }

    $_SESSION['ujian_aktif'] = true;
    $_SESSION['id_siswa'] = $id_siswa;
    $_SESSION['kode_soal'] = $kode_soal;
}

$id_siswa = $_SESSION['id_siswa'] ?? '';
$kode_soal = $_SESSION['kode_soal'] ?? '';

if (!$id_siswa || !$kode_soal || !isset($_SESSION['ujian_aktif'])) {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Akses tidak valid.';
    header('Location: ujian.php');
    exit;
}

$q_soal = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE kode_soal = '$kode_soal' ORDER BY nomer_soal ASC");
$all_soal = [];
while ($soal = mysqli_fetch_assoc($q_soal)) {
    $all_soal[] = $soal;
}
$total_soal = count($all_soal);

$current_question = isset($_GET['q']) ? (int)$_GET['q'] : 1;
if ($current_question < 1) $current_question = 1;
if ($current_question > $total_soal) $current_question = $total_soal;

$q_tema = mysqli_query($koneksi, "SELECT warna_tema FROM pengaturan WHERE id = 1 LIMIT 1");
$data_tema = mysqli_fetch_assoc($q_tema);
$warna_tema = $data_tema['warna_tema'] ?? '#0d6efd';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Siswa</title>
    <?php include '../inc/css.php'; ?>
    <style>
        .tampilan img {
            max-width: 400px !important;
            max-height: 300px !important;
            height: 100%;
            width: 100%;
            object-fit: contain;
            display: block;
            margin: 10px 0;
        }
        .question-nav-container {
            margin-top: 20px;
        }
        .question-nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .question-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            max-height: 200px;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            margin-bottom: 10px;
        }
        .question-nav.collapsed {
            max-height: 0;
        }
        .question-nav-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .question-nav-btn:hover {
            background-color: #f0f0f0;
        }
        .question-nav-btn.active {
            background-color: <?= $warna_tema ?>;
            color: white;
            border-color: <?= $warna_tema ?>;
        }
        .question-nav-btn.answered {
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .tempatsoal {
            min-height:450px;
            height:100%;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            max-height: 200px;
            overflow-y: auto;
            transition: max-height 0.3s ease-out;
        }
        .dropdown-wide {
            min-width: 220px;
        }
        .navbar-bg.sticky-top {
            position: sticky;
            top: 0;
            z-index: 1030;
            background-color: var(--adminkit-body-bg) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }
        .wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        main.content {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 20px;
            margin-bottom: 70px;
        }
        
        /* Loading Spinner Styles */
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255,255,255,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }
        .spinner-container {
            text-align: center;
        }
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>
<body>
    <!-- Loading Spinner Overlay -->
    <div id="loadingOverlay">
        <div class="spinner-container">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat soal...</p>
        </div>
    </div>

    <div style="height: 80px;"></div>
    <div class="wrapper">
        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg fixed-top shadow-sm">
                <a class="navbar-brand ms-3" href="#">
                    <img src="../assets/images/cbticon.png" alt="Logo" style="height: 36px;">
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align ms-auto">
                        <li class="nav-item me-3">
                            <div class="timer" style="font-weight: bold; font-size: 1.2rem;">
                                <i class="fas fa-clock me-1"></i>
                                <span id="time">00:00:00</span>
                            </div>
                        </li>
                        <li class="nav-item me-3">
                            <div class="dropdown-toggle" href="#" style="font-weight: bold; font-size: 1.2rem;" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-wide">
                                <li><span class="dropdown-item-text"><strong><?php echo $nama_siswa; ?></strong></span></li>
                                <li><span class="dropdown-item-text"><?php echo $kelas_siswa . $rombel_siswa; ?></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card shadow">
                                    <div class="card-header text-white" style="background-color:<?= htmlspecialchars($warna_tema) ?>">
                                        <h4 class="mb-0 text-white"><i class="fas fa-clipboard-check"></i> Ujian - Soal <?= $kode_soal ?></h4>
                                    </div>
                                    <div class="card-body tampilan">
                                        <form id="examForm" method="post" action="simpan_jawaban.php">
                                            <input type="hidden" name="kode_soal" value="<?= $kode_soal ?>">
                                            <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
                                            <input type="hidden" name="nomer_soal" value="<?= $all_soal[$current_question-1]['nomer_soal'] ?>">
                                            
                                            <div class="card mb-3 tempatsoal">
                                                <div class="card-header">
                                                    Soal Nomor <?= $all_soal[$current_question-1]['nomer_soal'] ?>
                                                </div>
                                                <div class="card-body">
                                                    <p><?= nl2br($all_soal[$current_question-1]['pertanyaan']) ?></p>

                                                    <?php if ($all_soal[$current_question-1]['tipe_soal'] === 'pg') : ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="jawaban" value="A" id="optionA">
                                                            <label class="form-check-label" for="optionA">A. <?= $all_soal[$current_question-1]['pilihan_1'] ?></label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="jawaban" value="B" id="optionB">
                                                            <label class="form-check-label" for="optionB">B. <?= $all_soal[$current_question-1]['pilihan_2'] ?></label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="jawaban" value="C" id="optionC">
                                                            <label class="form-check-label" for="optionC">C. <?= $all_soal[$current_question-1]['pilihan_3'] ?></label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="jawaban" value="D" id="optionD">
                                                            <label class="form-check-label" for="optionD">D. <?= $all_soal[$current_question-1]['pilihan_4'] ?></label>
                                                        </div>
                                                    <?php elseif ($all_soal[$current_question-1]['tipe_soal'] === 'isian') : ?>
                                                        <input type="text" class="form-control" name="jawaban" placeholder="Jawaban Anda">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="navigation-buttons">
                                                <?php if ($current_question > 1): ?>
                                                    <a href="?q=<?= $current_question - 1 ?>" class="btn btn-secondary btn-navigate">
                                                        <i class="fas fa-arrow-left"></i> Sebelumnya
                                                    </a>
                                                <?php else: ?>
                                                    <span></span>
                                                <?php endif; ?>
                                                
                                                <?php if ($current_question < $total_soal): ?>
                                                    <a href="?q=<?= $current_question + 1 ?>" class="btn btn-primary btn-navigate">
                                                        Selanjutnya <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <button type="submit" class="btn btn-success" name="selesai">
                                                        Selesai Ujian <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="card-footer">
                                         <button class="btn btn-outline-secondary" onclick="toggleQuestionNav()">Navigasi Soal <span id="navToggleIcon"><i class="fas fa-chevron-down"></i></span></button>
                                        <div class="card question-nav-container">
                                                <div class="question-nav" id="questionNav">
                                                    <?php for ($i = 1; $i <= $total_soal; $i++): ?>
                                                        <a href="?q=<?= $i ?>" class="question-nav-btn btn-navigate <?= $i == $current_question ? 'active' : '' ?>">
                                                            <?= $i ?>
                                                        </a>
                                                    <?php endfor; ?>
                                                </div>
                                        </div>
                                    </div>
                                </div>       
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php include '../inc/js.php'; ?>
    
    <script>
      // Timer function
function startTimer(duration, display) {
    var timer = duration, hours, minutes, seconds;
    setInterval(function () {
        hours = parseInt(timer / 3600, 10);
        minutes = parseInt((timer % 3600) / 60, 10);
        seconds = parseInt(timer % 60, 10);

        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = hours + ":" + minutes + ":" + seconds;

        if (--timer < 0) {
            document.getElementById('examForm').submit();
        }
    }, 1000);
}

// Initialize timer when window loads
window.onload = function () {
    var duration = 3600;
    var display = document.querySelector('#time');
    startTimer(duration, display);
};

// Toggle question navigation
function toggleQuestionNav() {
    const nav = document.getElementById('questionNav');
    const icon = document.getElementById('navToggleIcon');
    
    nav.classList.toggle('collapsed');
    
    if (nav.classList.contains('collapsed')) {
        icon.innerHTML = '<i class="fas fa-chevron-up"></i>';
    } else {
        icon.innerHTML = '<i class="fas fa-chevron-down"></i>';
    }
    
    localStorage.setItem('navCollapsed', nav.classList.contains('collapsed'));
}

// Set initial state from localStorage
document.addEventListener('DOMContentLoaded', function() {
    // Initialize navigation collapse state
    const nav = document.getElementById('questionNav');
    const icon = document.getElementById('navToggleIcon');
    const isCollapsed = localStorage.getItem('navCollapsed') === 'true';
    
    if (isCollapsed) {
        nav.classList.add('collapsed');
        icon.innerHTML = '<i class="fas fa-chevron-up"></i>';
    } else {
        nav.classList.remove('collapsed');
        icon.innerHTML = '<i class="fas fa-chevron-down"></i>';
    }

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
            navbar.classList.add('bg-white', 'shadow');
        } else {
            navbar.classList.remove('bg-white', 'shadow');
        }
    });
    
    // Handle navigation with loading spinner
    const navButtons = document.querySelectorAll('.btn-navigate, .question-nav-btn');
    navButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loading spinner
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            // Save current answers via AJAX
            const formData = new FormData(document.getElementById('examForm'));
            
            // Set minimum display time for spinner (1 second)
            const spinnerStartTime = Date.now();
            const minDisplayTime = 100;
            
            fetch('simpan_jawaban.php', {
                method: 'POST',
                body: formData
            }).then(response => {
                const elapsed = Date.now() - spinnerStartTime;
                const remainingTime = Math.max(0, minDisplayTime - elapsed);
                
                // Wait until minimum display time is reached before navigating
                setTimeout(() => {
                    window.location.href = this.getAttribute('href');
                }, remainingTime);
            }).catch(error => {
                console.error('Error:', error);
                document.getElementById('loadingOverlay').style.display = 'none';
                alert('Gagal menyimpan jawaban. Silakan coba lagi.');
            });
        });
    });
});

// Hide spinner when page is fully loaded
window.addEventListener('load', function() {
    document.getElementById('loadingOverlay').style.display = 'none';
});
    </script>
</body>
</html>