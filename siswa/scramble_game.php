<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

$id_siswa = $_SESSION['siswa_id'] ?? 0;
if ($id_siswa == 0) {
    die("Anda harus login terlebih dahulu.");
}

$pool_kata = [
    // 4 huruf (30 kata)
    "buku", "kota", "lima", "mata", "rumah", "bola", "sapi", "tahu", "cari",
    "kuda", "kaki", "makan", "tangan", "baju", "kamar", "kunci", "padi", "jalan", "api",
    "rasa", "ikan", "sungai", "kata", "kaca", "batu", "hati", "anak", "susu", "awan",

    // 5 huruf (30 kata)
    "sekolah", "belajar", "teman", "tulisan", "bintang", "kertas", "lapang", "pohon", "gelas", "jantung",
    "gitar", "kamar", "malam", "bunga", "ibu", "orang", "tembok", "warna", "sawah", "pulau",
    "duduk", "kerja", "suara", "kerja", "besar", "kecil", "pasar", "pasir", "lulus", "pintu",

    // 6 huruf (30 kata)
    "adalah", "bukit", "hutan", "sejarah", "harian", "kereta", "sembah", "tentang", "kebun", "kerja",
    "belanja", "gembira", "pemain", "anak-anak", "bahasa", "kelas", "musim", "bulan", "suasana", "berita",
    "pesawat", "liburan", "selamat", "temukan", "pergi", "rumput", "gadis", "menang", "sukses", "menulis",

    // 7 huruf (30 kata)
    "pelajar", "pelajaran", "bermain", "mengerti", "sekolah", "bahagia", "duduklah", "makanan", "menulis", "berjalan",
    "bertemu", "mendengar", "berbicara", "memiliki", "membaca", "pergi", "sehatkan", "menolong", "menjadi", "berlari",
    "berkata", "bernyanyi", "bertemu", "mengajar", "melihat", "berkawan", "menonton", "menjawab", "berbagi", "meminta",

    // 8 huruf (30 kata)
    "pendidikan", "kegiatan", "bersekolah", "berteman", "membantu", "mendukung", "berlatih", "berusaha", "berdoa", "berharap",
    "mengajar", "mempelajari", "menyelesaikan", "berbicara", "bertemu", "menonton", "mendengarkan", "berkumpul", "bermain", "mendapatkan",
    "mengikuti", "berolahraga", "menyanyi", "berpikir", "menulis", "berjalan", "bernyanyi", "memahami", "mengerti", "menolong",

    // 9 huruf (10 kata)
    "meningkat", "berkembang", "menyediakan", "berkomunikasi", "menggunakan", "mengembangkan", "mempersiapkan", "menyesuaikan", "berpartisipasi", "membantu",

    // 10 huruf (10 kata)
    "pengalaman", "pembelajaran", "kesehatan", "pengetahuan", "perkembangan", "penyelesaian", "mempersiapkan", "menggunakan", "berkomunikasi", "berpartisipasi"
];

function scrambleWord($word) {
    $letters = mb_str_split($word);
    do {
        shuffle($letters);
        $scrambled = implode("", $letters);
    } while ($scrambled === $word);
    return $scrambled;
}


if (!isset($_SESSION['scramble'])) {
    $_SESSION['scramble'] = [
        'nyawa' => 3,
        'bantuan' => 3,
        'skor' => 0,
        'level' => 1,
        'kata_sisa' => $pool_kata,
        'kata_sekarang' => '',
        'start_time' => time(),
        'time_left' => 60,
        'revealed_letters' => []
    ];
}

$scramble = &$_SESSION['scramble'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_timer') {
    $scramble['time_left'] = intval($_POST['time_left']);
    exit;
}

function nextWord() {
    global $scramble;
    if (empty($scramble['kata_sisa'])) {
        return null;
    }

    $target_length = $scramble['level'] + 3;

    $filtered = array_filter($scramble['kata_sisa'], function ($word) use ($target_length) {
        return mb_strlen($word) === $target_length;
    });

    if (empty($filtered)) {
        $filtered = array_filter($scramble['kata_sisa'], function ($word) use ($target_length) {
            return mb_strlen($word) > $target_length;
        });
        if (empty($filtered)) return null;
    }

    $index = array_rand($filtered);
    $kata = $filtered[$index];

    $key_in_pool = array_search($kata, $scramble['kata_sisa']);
    unset($scramble['kata_sisa'][$key_in_pool]);
    $scramble['kata_sisa'] = array_values($scramble['kata_sisa']);

    $scramble['kata_sekarang'] = $kata;
    $scramble['start_time'] = time();
    $scramble['time_left'] = 60;
    $scramble['revealed_letters'] = [];


    $letters = mb_str_split($kata);
    $random_index = array_rand($letters);
    $scramble['revealed_letters'][$random_index] = $letters[$random_index];

    return $kata;
}

if ($scramble['kata_sekarang'] === '') {
    $kata_baru = nextWord();
    $game_over = $kata_baru === null;
} else {
    $game_over = false;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    if ($scramble['nyawa'] <= 0) {
        echo json_encode(['status'=>'game_over', 'msg'=>'Nyawa habis. Game Over!', 'skor'=>$scramble['skor']]);
        exit;
    }

    $now = time();
    $elapsed = $now - $scramble['start_time'];
    if ($elapsed > 60) {
        $scramble['nyawa']--;
        if ($scramble['nyawa'] <= 0) {
            echo json_encode(['status'=>'game_over', 'msg'=>'Nyawa habis. Game Over!', 'skor'=>$scramble['skor']]);
            exit;
        }
        $kata_baru = nextWord();
        if ($kata_baru === null) {
            echo json_encode(['status'=>'game_finished', 'msg'=>'Semua kata selesai!', 'skor'=>$scramble['skor']]);
            exit;
        }
        echo json_encode(value: [
            'status'=>'time_up',
            'nyawa'=>$scramble['nyawa'],
            'kata_scramble'=>scrambleWord($kata_baru),
            'bantuan'=>$scramble['bantuan'],
            'level'=>$scramble['level'],
            'revealed_letters'=>$scramble['revealed_letters']
        ]);
        exit;
    }

    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'bantuan') {
            if ($scramble['bantuan'] <= 0) {
                echo json_encode(['status'=>'fail', 'msg'=>'Bantuan habis.']);
                exit;
            }
            
            $kata_asli = $scramble['kata_sekarang'];
            $letters = mb_str_split($kata_asli);
            $closed_positions = array_filter(range(0, count($letters)-1), 
                function($i) use ($scramble) { return !isset($scramble['revealed_letters'][$i]); });
            
            if (empty($closed_positions)) {
                echo json_encode(['status'=>'fail', 'msg'=>'Semua huruf sudah terbuka.']);
                exit;
            }
            
            $random_index = $closed_positions[array_rand($closed_positions)];
            $scramble['revealed_letters'][$random_index] = $letters[$random_index];
            $scramble['bantuan']--;
            
            echo json_encode([
                'status'=>'success', 
                'revealed_letters'=>$scramble['revealed_letters'],
                'bantuan'=>$scramble['bantuan']
            ]);
            exit;
        }
    }

    if (isset($_POST['jawaban'])) {
        $jawaban = strtoupper(trim($_POST['jawaban']));
        $kata_asli = strtoupper($scramble['kata_sekarang']);

        if ($jawaban === $kata_asli) {
            $scramble['skor'] += 10;
            if ($scramble['skor'] % 50 === 0) $scramble['level']++;
            
            $kata_baru = nextWord();
            if ($kata_baru === null) {
                echo json_encode(['status'=>'game_finished', 'msg'=>'Semua kata selesai!', 'skor'=>$scramble['skor']]);
                exit;
            }
            echo json_encode([
                'status'=>'correct',
                'skor'=>$scramble['skor'],
                'level'=>$scramble['level'],
                'kata_scramble'=>scrambleWord($kata_baru),
                'nyawa'=>$scramble['nyawa'],
                'bantuan'=>$scramble['bantuan'],
                'revealed_letters'=>$scramble['revealed_letters']
            ]);
            exit;
        } else {
            $scramble['nyawa']--;
            $kata_baru = nextWord();
            
            if ($scramble['nyawa'] <= 0) {
                echo json_encode(['status'=>'game_over', 'msg'=>'Nyawa habis. Game Over!', 'skor'=>$scramble['skor']]);
                exit;
            }
            
            if ($kata_baru === null) {
                echo json_encode(['status'=>'game_finished', 'msg'=>'Semua kata selesai!', 'skor'=>$scramble['skor']]);
                exit;
            }
            
            echo json_encode([
                'status' => 'wrong',
                'nyawa' => $scramble['nyawa'],
                'msg' => 'Jawaban salah, coba lagi.',
                'skor' => $scramble['skor'],
                'level' => $scramble['level'],
                'kata_scramble' => scrambleWord($kata_baru),
                'revealed_letters' => $scramble['revealed_letters'],
                'bantuan' => $scramble['bantuan']
            ]);
            exit;
        }
    }

    echo json_encode(['status'=>'fail', 'msg'=>'Request invalid']);

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
    <title>Game Scramble Text</title>
    <link href="../assets/fontawesome/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/images/icon.png" />
    <script src="../assets/js/sweetalert.js"></script>
    <?php include 'scramble_css.php'; ?>

</head>

<body>
    <div class="game-container">
        <h2>🎮 Game Scramble Text 🎮</h2>

        <div id="info">
            <div class="info-item nyawa"><i class="fas fa-heart" style="color:red;"></i> Nyawa: <span
                    id="nyawa-count"></span></div>
            <div class="info-item bantuan" style="display:none;"><i class="fas fa-lightbulb" style="color:purple;"></i> Bantuan: <span
                    id="bantuan-count"></span></div>
            <div class="info-item level"><i class="fas fa-level-up-alt" style="color:green;"></i> Level: <span
                    id="level"></span></div>
            <div class="info-item skor"><i class="fas fa-star" style="color:orange;"></i> Skor: <span id="skor"></span>
            </div>
            <div class="info-item"><i class="fas fa-clock" style="color:#157ae6;"></i> Waktu: <span
                    id="timer">60</span>s</div>
        </div>

        <div id="kata-scramble"></div>

        <div id="input-container"></div>

        <div class="button-group">
            <button id="submit-btn"><i class="fas fa-paper-plane"></i> Jawab</button>
            <button id="bantuan-btn" style="display:none;"><i class="fas fa-question"></i> Bantuan</button>
            <button id="keluar-btn"><i class="fas fa-sign-out"></i> Keluar</button>
        </div>
        <br>
        <div class="col-12 text-center">
               <p class="mb-0 text-center">
                    <center><a href="#" id="enc" style="color:grey;text-decoration:none;"></a></center>
               </p>
          </div>
    </div>

    <script>
    const nyawaCountSpan = document.getElementById('nyawa-count');
    const bantuanCountSpan = document.getElementById('bantuan-count');
    const levelSpan = document.getElementById('level');
    const skorSpan = document.getElementById('skor');
    const kataScrambleDiv = document.getElementById('kata-scramble');
    const inputContainer = document.getElementById('input-container');
    const submitBtn = document.getElementById('submit-btn');
    const bantuanBtn = document.getElementById('bantuan-btn');
    const timerSpan = document.getElementById('timer');

    let timerInterval;
    let waktu = <?php echo $scramble['time_left']; ?>;

    function updateUI(data) {
        if (data.nyawa !== undefined) nyawaCountSpan.textContent = data.nyawa;
        if (data.bantuan !== undefined) bantuanCountSpan.textContent = data.bantuan;
        if (data.level !== undefined) levelSpan.textContent = data.level;
        if (data.skor !== undefined) skorSpan.textContent = data.skor;

        // Update kata scramble
        if (data.kata_scramble !== undefined) {
            kataScrambleDiv.innerHTML = '';
            const letters = data.kata_scramble.split('');
            letters.forEach(letter => {
                const span = document.createElement('span');
                span.className = 'letter-box';
                span.textContent = letter;
                kataScrambleDiv.appendChild(span);
            });
        }

        // Update input container
        if (data.revealed_letters !== undefined || data.kata_scramble !== undefined) {
    inputContainer.innerHTML = '';
    const wordLength = data.kata_scramble ? data.kata_scramble.length : 0;
    const revealedLetters = data.revealed_letters || {};

    for (let i = 0; i < wordLength; i++) {
        const input = document.createElement('input');
        input.type = 'text';
        input.maxLength = 1;
        input.className = 'input-letter';
        input.dataset.index = i;

        if (revealedLetters[i]) {
            input.value = revealedLetters[i];
            input.readOnly = true;
            input.classList.add('revealed');
        }

        input.addEventListener('input', function(e) {
            if (this.value) {
                let next = this.nextElementSibling;
                while (next && next.readOnly) {
                    next = next.nextElementSibling;
                }
                if (next) next.focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value) {
                let prev = this.previousElementSibling;
                while (prev && prev.readOnly) {
                    prev = prev.previousElementSibling;
                }
                if (prev) prev.focus();
            } else if (e.key === 'Enter') {
                submitAnswer(getCurrentAnswer());
            }
        });

        inputContainer.appendChild(input);
    }

    // Fokus ke input pertama yang kosong
    const firstEmptyInput = inputContainer.querySelector('.input-letter:not(.revealed)');
    if (firstEmptyInput) firstEmptyInput.focus();
}

    }

    function getCurrentAnswer() {
        const inputs = document.querySelectorAll('.input-letter');
        let answer = '';
        inputs.forEach(input => {
            answer += input.value || '';
        });
        return answer;
    }

    function startTimer() {
        waktu = <?php echo $scramble['time_left']; ?>;
        timerSpan.textContent = waktu;
        clearInterval(timerInterval);

        timerInterval = setInterval(() => {
            waktu--;
            timerSpan.textContent = waktu;

            // Update timer di server
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=update_timer&time_left=' + waktu
            }).catch(() => {});

            if (waktu <= 0) {
                clearInterval(timerInterval);
                submitAnswer(''); // trigger waktu habis
            }
        }, 1000);
    }

    function stopTimer() {
        clearInterval(timerInterval);
    }

    function submitAnswer(jawaban) {
    const formData = new FormData();
    // Selalu append jawaban walau kosong (untuk submit kosong di kasus time_up)
    formData.append('jawaban', jawaban ?? '');

    fetch('', {
        method: 'POST',
        body: formData,
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'correct') {
            updateUI(data);
            startTimer();
            swalToast('success', 'Jawaban benar! +10 poin');
        } else if (data.status === 'wrong') {
            updateUI(data);
            startTimer(); // reset timer
            swalToast('error', data.msg);
        } else if (data.status === 'game_over') {
            stopTimer();
            showAlert('error', 'Game Over!', data.msg + '\nSkor Anda: ' + data.skor, () => {
                window.location.href = 'game_over_scramble.php';
            });
        } else if (data.status === 'game_finished') {
            stopTimer();
            showAlert('success', 'Selamat!', data.msg + '\nSkor Anda: ' + data.skor, () => {
                window.location.href = 'game_over_scramble.php';
            });
        } else if (data.status === 'time_up') {
            updateUI(data);
            startTimer();
            swalToast('warning', 'Waktu habis, nyawa dikurangi.');

            // Kirim jawaban kosong otomatis
            if (jawaban !== '') {
                // Cuma submit kosong sekali saat time_up
                submitAnswer('');
            }
        } else {
            swalToast('info', data.msg || 'Terjadi kesalahan.');
        }
    })
    .catch(() => {
        swalToast('error', 'Gagal terhubung ke server.');
    });
}


    function requestBantuan() {
        if (bantuanCountSpan.textContent == 0) {
            swalToast('error', 'Bantuan habis.');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'bantuan');
        formData.append('jawaban', getCurrentAnswer());

        fetch('', {
                method: 'POST',
                body: formData,
            }).then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    updateUI(data);
                    swalToast('info', '1 huruf dibuka!');
                } else {
                    swalToast('error', data.msg || 'Gagal membuka huruf.');
                }
            })
            .catch(() => {
                swalToast('error', 'Gagal terhubung ke server.');
            });
    }

    function swalToast(icon, message) {
        Swal.fire({
            icon: icon,
            title: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true
        });
    }

    function showAlert(icon, title, text = '', callback = null) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
        }).then((result) => {
            if (callback) callback();
        });
    }

    // Inisialisasi data awal
    (function init() {
        updateUI({
            nyawa: <?php echo $scramble['nyawa']; ?>,
            bantuan: <?php echo $scramble['bantuan']; ?>,
            level: <?php echo $scramble['level']; ?>,
            skor: <?php echo $scramble['skor']; ?>,
            kata_scramble: "<?php echo $scramble['kata_sekarang'] ? scrambleWord($scramble['kata_sekarang']) : '--'; ?>",
            revealed_letters: <?php echo json_encode($scramble['revealed_letters']); ?>
        });
        startTimer();
    })();

    submitBtn.addEventListener('click', () => {
        const jawaban = getCurrentAnswer();
        if (jawaban.length <
            <?php echo isset($scramble['kata_sekarang']) ? mb_strlen($scramble['kata_sekarang']) : 1; ?>) {
            swalToast('warning', 'Lengkapi semua huruf!');
            return;
        }
        submitAnswer(jawaban.toUpperCase());
    });

    bantuanBtn.addEventListener('click', () => {
        requestBantuan();
    });

    document.getElementById('bantuan-btn').addEventListener('click', function() {
        setTimeout(function() {
            location.reload();
        }, 1000); // Delay 1000ms = 1 detik
    });

    document.getElementById('keluar-btn').addEventListener('click', function() {
        Swal.fire({
            title: 'Keluar dari game?',
            text: "Progress kamu akan hilang!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'keluar',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'game_over_scramble.php';
            }
        });
    });
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
<script>
  const inputs = document.querySelectorAll('.input-letter');

  inputs.forEach((input, index) => {
    input.addEventListener('input', () => {
      let nextIndex = index + 1;
      while (nextIndex < inputs.length && inputs[nextIndex].readOnly) {
        nextIndex++;
      }
      if (nextIndex < inputs.length) {
        inputs[nextIndex].focus();
      }
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'ArrowLeft') {
        let prevIndex = index - 1;
        while (prevIndex >= 0 && inputs[prevIndex].readOnly) {
          prevIndex--;
        }
        if (prevIndex >= 0) {
          inputs[prevIndex].focus();
        }
      } else if (e.key === 'ArrowRight') {
        let nextIndex = index + 1;
        while (nextIndex < inputs.length && inputs[nextIndex].readOnly) {
          nextIndex++;
        }
        if (nextIndex < inputs.length) {
          inputs[nextIndex].focus();
        }
      }
    });
  });
</script>
    <?php include '../inc/check_activity.php'; ?>
</body>

</html>