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

// Ambil info soal (header)
$query_info = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal='$kode_soal' LIMIT 1");
$info_soal = mysqli_fetch_assoc($query_info);

// Hitung jumlah soal per tipe soal
$tipe_soal_count = [];
$query_tipe_soal = mysqli_query($koneksi, "SELECT tipe_soal, COUNT(*) as jumlah FROM butir_soal WHERE kode_soal='$kode_soal' GROUP BY tipe_soal");
while ($row = mysqli_fetch_assoc($query_tipe_soal)) {
    $tipe_soal_count[$row['tipe_soal']] = $row['jumlah'];
}

// Hitung total jumlah soal
$query_jumlah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM butir_soal WHERE kode_soal='$kode_soal'");
$jumlah_soal = mysqli_fetch_assoc($query_jumlah)['total'];

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

// Cek apakah ada jawaban yang sudah disimpan
$jawaban_siswa = [];
$query_jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban_siswa WHERE id_siswa='$id_siswa' AND kode_soal='$kode_soal'");
while ($row = mysqli_fetch_assoc($query_jawaban)) {
    $jawaban_siswa[$row['nomer_soal']] = $row['jawaban_siswa'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Siswa</title>
    <?php include '../inc/css.php'; ?>
    <?php include '../inc/cssujian.php'; ?>

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
    <div style="height: 35px;"></div>
    <div class="wrapper">
        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg fixed-top" style="border-bottom:5px solid <?php echo htmlspecialchars($warna_tema); ?> !important;">
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
                                        <h4 class="mb-0 text-white"><b style="background-color:#1c1c1c;padding:5px;border-radius:10px 0 0 10px;">No.</b><b style="background-color:#ffffff;padding:5px;border-radius:0 10px 10px 0;color:black;"><?= str_pad($all_soal[$current_question-1]['nomer_soal'], 2, '0', STR_PAD_LEFT) ?></b></h4>
                                    </div>
                                    <div class="card-body tampilan" style="padding-bottom:0px !important;">
                                    <div id="autoSaveStatus"></div>
                                        <form id="examForm" method="post" action="simpan_jawaban.php">
                                            <input type="hidden" name="kode_soal" value="<?= $kode_soal ?>">
                                            <input type="hidden" name="id_siswa" value="<?= $id_siswa ?>">
                                            <input type="hidden" name="nomer_soal" value="<?= $all_soal[$current_question-1]['nomer_soal'] ?>">
                                            <input type="hidden" name="tipe_soal" value="<?= $all_soal[$current_question-1]['tipe_soal'] ?>">
                                            <div class="card tempatsoal">
                                                <div class="card-body">
                                                    <p class='text-dark gbrsoal'><?= $all_soal[$current_question - 1]['pertanyaan'] ?></p>

                                                    
                                                    <?php 
                                                    $current_soal = $all_soal[$current_question-1];
                                                    $nomer_soal = $current_soal['nomer_soal'];
                                                    $saved_answer = $jawaban_siswa[$nomer_soal] ?? null;
                                                    
                                                    if ($current_soal['tipe_soal'] === 'Pilihan Ganda') : 
                                                        $options = [
                                                            'A' => $current_soal['pilihan_1'],
                                                            'B' => $current_soal['pilihan_2'],
                                                            'C' => $current_soal['pilihan_3'],
                                                            'D' => $current_soal['pilihan_4']
                                                        ];
                                                        ?>
                                                        <?php foreach ($options as $key => $value): ?>
                                                            <div class="form-check radio-wrapper">
                                                                <input class="form-check-input" type="radio" name="jawaban" 
                                                                       value="<?= $key ?>" id="option<?= $key ?>"
                                                                       <?= ($saved_answer === $key) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="option<?= $key ?>">
                                                                    <?= $key ?>. <?= $value ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        
                                                    <?php elseif ($current_soal['tipe_soal'] === 'Pilihan Ganda Kompleks') : 
                                                        $options = [
                                                            'A' => $current_soal['pilihan_1'],
                                                            'B' => $current_soal['pilihan_2'],
                                                            'C' => $current_soal['pilihan_3'],
                                                            'D' => $current_soal['pilihan_4']
                                                        ];
                                                        $saved_answers = $saved_answer ? json_decode($saved_answer, true) : [];
                                                        ?>
                                                        <?php foreach ($options as $key => $value): ?>
                                                            <div class="form-check radio-wrapper">
                                                                <input class="form-check-input" type="checkbox" 
                                                                       name="jawaban[]" value="<?= $key ?>" 
                                                                       id="option<?= $key ?>"
                                                                       <?= (in_array($key, (array)$saved_answers)) ? 'checked' : '' ?>>
                                                                <label class="form-check-label" for="option<?= $key ?>">
                                                                    <?= $key ?>. <?= htmlspecialchars($value) ?>
                                                                </label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                        
                                                    <?php elseif ($current_soal['tipe_soal'] === 'Menjodohkan') : 
                                                        $pasangan = explode('|', $current_soal['jawaban_benar']);
                                                        $saved_answers = $saved_answer ? json_decode($saved_answer, true) : [];
                                                        ?>
                                                        <table class='table table-bordered'>
                                                            <thead>
                                                                <tr>
                                                                    <th>Pilihan</th>
                                                                    <th>Pasangan</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($pasangan as $index => $pair): ?>
                                                                <?php if (strpos($pair, ':') !== false): ?>
                                                                    <?php 
                                                                    [$kiri, $kanan] = explode(':', $pair, 2);
                                                                    $kiri = trim($kiri);
                                                                    $kanan = trim($kanan);
                                                                    ?>
                                                                    <tr>
                                                                        <td><?= $kiri ?></td>
                                                                        <td>
                                                                            <select class='form-select' name='jawaban[<?= $index ?>]'>
                                                                                <option value=''>Pilih Jawaban</option>
                                                                                <?php foreach ($pasangan as $opt_index => $option): ?>
                                                                                    <?php if (strpos($option, ':') !== false): ?>
                                                                                        <?php 
                                                                                        [$opt_kiri, $opt_kanan] = explode(':', $option, 2);
                                                                                        $opt_kanan = trim($opt_kanan);
                                                                                        ?>
                                                                                        <option value='<?= $opt_kanan ?>' 
                                                                                            <?= (isset($saved_answers[$index]) && $saved_answers[$index] === $opt_kanan) ? 'selected' : '' ?>>
                                                                                            <?= $opt_kanan ?>
                                                                                        </option>
                                                                                    <?php endif; ?>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                        
                                                    <?php elseif ($current_soal['tipe_soal'] === 'Benar/Salah') : 
                                                        $opsi = [];
                                                        for ($i = 1; $i <= 4; $i++) {
                                                            $opsi_nama = 'pilihan_' . $i;
                                                            $nilai = $current_soal[$opsi_nama];
                                                            if (!empty($nilai)) {
                                                                $opsi[] = $nilai;
                                                            }
                                                        }
                                                        $saved_answers = $saved_answer ? json_decode($saved_answer, true) : [];
                                                        ?>
                                                        <table class='table table-bordered tabelgambar'>
                                                            <thead>
                                                                <tr>
                                                                    <th>Pernyataan</th>
                                                                    <th style='width:100px;text-align:center'>Benar</th>
                                                                    <th style='width:100px;text-align:center'>Salah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php foreach ($opsi as $index => $nilai): ?>
                                                                <tr>
                                                                    <td><?= $nilai ?></td>
                                                                    <td class="radio-cell" style='text-align:center'>
                                                                    &emsp;&emsp;<input class='form-check-input' type='radio' 
                                                                               name='jawaban[<?= $index ?>]' value='Benar'
                                                                               <?= (isset($saved_answers[$index]) && $saved_answers[$index] === 'Benar') ? 'checked' : '' ?>>
                                                                    </td>
                                                                    <td class="radio-cell" style='text-align:center'>
                                                                    &emsp;&emsp;<input class='form-check-input' type='radio' 
                                                                               name='jawaban[<?= $index ?>]' value='Salah'
                                                                               <?= (isset($saved_answers[$index]) && $saved_answers[$index] === 'Salah') ? 'checked' : '' ?>>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                        
                                                    <?php elseif ($current_soal['tipe_soal'] === 'Uraian') : ?>
                                                        <textarea class="form-control" name="jawaban" rows="4" 
                                                            placeholder="Tulis jawaban Anda di sini..."><?= $saved_answer ? $saved_answer : '' ?></textarea>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="navigation-buttons">
                                                <?php if ($current_question > 1): ?>
                                                    <a href="?q=<?= $current_question - 1 ?>" class="btn btn-secondary btn-navigate">
                                                        <i class="fas fa-arrow-left"></i> Prev
                                                    </a>
                                                <?php else: ?>
                                                    <span></span>
                                                <?php endif; ?>
                                                
                                                <?php if ($current_question < $total_soal): ?>
                                                    <a href="?q=<?= $current_question + 1 ?>" class="btn btn-primary btn-navigate">
                                                        Next <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <button type="submit" class="btn btn-success" name="selesai">
                                                        Selesai Ujian <i class="fas fa-check"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer" style="padding-top:0px !important;">
                                        <button class="btn btn-outline-secondary" onclick="toggleQuestionNav()">Navigasi Soal <span id="navToggleIcon"><i class="fas fa-chevron-down"></i></span></button>
                                        <div class="card question-nav-container">
                                            <div class="question-nav" id="questionNav">
                                                <?php for ($i = 1; $i <= $total_soal; $i++): ?>
                                                    <a href="?q=<?= $i ?>" class="question-nav-btn btn-navigate <?= $i == $current_question ? 'active' : '' ?> <?= isset($jawaban_siswa[$i]) ? 'answered' : '' ?>">
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
    <footer class="footer mt-auto py-3 bg-dark sticky-bottom">
        <div class="container-fluid">
            <div class="row text-grey">
                <div class="col-6 text-start">
                    <p class="mb-0">
                    <a href="#" id="enc" style="color:grey;"></a>
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="../assets/adminkit/static/js/app.js"></script>
    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <script src="../assets/js/sweetalert.js"></script>
    <script src="../assets/datatables/datatables.js"></script>
    <script>
        // Fungsi untuk toggle navigasi soal
        function toggleQuestionNav() {
            const navContainer = document.querySelector('.question-nav-container');
            const icon = document.getElementById('navToggleIcon');
            
            navContainer.classList.toggle('show');
            icon.innerHTML = navContainer.classList.contains('show') ?
                '<i class="fas fa-chevron-up"></i>' :
                '<i class="fas fa-chevron-down"></i>';
        }

        // Timer function dengan auto-save
        function startTimer(duration, display) {
            var timer = duration, hours, minutes, seconds;
            var lastSaveTime = 0;
            
            var timerInterval = setInterval(function () {
                hours = parseInt(timer / 3600, 10);
                minutes = parseInt((timer % 3600) / 60, 10);
                seconds = parseInt(timer % 60, 10);

                hours = hours < 10 ? "0" + hours : hours;
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = hours + ":" + minutes + ":" + seconds;

                // Auto-save setiap 1 menit (60 detik)
                var currentTime = duration - timer;
                if (currentTime - lastSaveTime >= 60 && currentTime > 0) {
                    lastSaveTime = currentTime;
                    autoSaveJawaban();
                }

                if (--timer < 0) {
                    clearInterval(timerInterval);
                    document.getElementById('examForm').submit();
                }
            }, 1000);
        }

        // Fungsi untuk auto-save jawaban
        function autoSaveJawaban() {
            const formData = new FormData(document.getElementById('examForm'));
            formData.append('auto_save', 'true');
            
            fetch('simpan_jawaban.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    showAutoSaveStatus();
                    updateAnsweredQuestions();
                } else {
                    console.error('Gagal menyimpan jawaban otomatis');
                }
            })
            .catch(error => {
                console.error('Error saat auto-save:', error);
            });
        }

        // Tampilkan notifikasi auto-save
        function showAutoSaveStatus() {
            const statusElement = document.getElementById('autoSaveStatus');
            statusElement.style.display = 'block';
            setTimeout(() => {
                statusElement.style.display = 'none';
            }, 3000);
        }

        // Update tampilan soal yang sudah dijawab
        function updateAnsweredQuestions() {
            const questionButtons = document.querySelectorAll('.question-nav-btn');
            questionButtons.forEach(button => {
                const questionNumber = button.textContent.trim();
                const answerInputs = document.querySelectorAll(`input[name^="jawaban"], textarea[name="jawaban"], select[name^="jawaban"]`);
                let hasAnswer = false;
                
                answerInputs.forEach(input => {
                    if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) {
                        hasAnswer = true;
                    } else if ((input.type === 'text' || input.tagName === 'TEXTAREA' || input.tagName === 'SELECT') && input.value) {
                        hasAnswer = true;
                    }
                });
                
                if (hasAnswer) {
                    button.classList.add('answered');
                } else {
                    button.classList.remove('answered');
                }
            });
        }

        // Start timer when DOM is ready
        window.addEventListener('DOMContentLoaded', function () {
            var duration = 3600; // 1 hour in seconds
            var display = document.querySelector('#time');
            if (display) startTimer(duration, display);
            
            // Update answered questions on load
            updateAnsweredQuestions();
        });

        // Handle navigation
        document.querySelectorAll('.btn-navigate').forEach(button => {
            button.addEventListener('click', function (e) {
                if (this.tagName === 'A') {
                    e.preventDefault();
                    document.getElementById('loadingOverlay').style.display = 'flex';
                    
                    // Langsung navigasi tanpa menyimpan
                    setTimeout(() => {
                        window.location.href = this.getAttribute('href');
                    }, 300);
                }
            });
        });

        // Handle form submit untuk selesai ujian
        document.getElementById('examForm').addEventListener('submit', function(e) {
            const submitButton = e.submitter;
            if (submitButton.name === 'selesai') {
                e.preventDefault();
                
                // Tampilkan konfirmasi sebelum submit akhir
                Swal.fire({
                    title: 'Selesai Ujian?',
                    text: "Anda tidak dapat mengubah jawaban setelah mengirim!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Selesai!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form secara normal
                        this.submit();
                    }
                });
            }
        });

        // Hide loading spinner after full load
        window.addEventListener('load', function () {
            document.getElementById('loadingOverlay').style.display = 'none';
        });

        // Update answered questions when inputs change
        document.querySelectorAll('input, textarea, select').forEach(input => {
            input.addEventListener('change', updateAnsweredQuestions);
        });

document.addEventListener("DOMContentLoaded", function() {
        var base64Text = "<?php echo $encryptedText; ?>"; 
            if(base64Text) {
                var decodedText = atob(base64Text); 
                document.getElementById("enc").innerHTML = decodedText; 
            }
        });

        function checkIfEncDeleted() {
        var encElement = document.getElementById("enc");

            if (!encElement) {
                window.location.href = "../error_page.php";  
            }
        }
        setInterval(checkIfEncDeleted, 500);
</script>
<script>
function getJawabanDariForm() {
    const totalSoal = parseInt(document.getElementById('total_soal').value);
    let jawaban = [];

    for (let i = 1; i <= totalSoal; i++) {
        let elemen = document.querySelectorAll(`[name^="jawaban_${i}"]`);

        if (elemen.length === 1) {
            // Input tunggal: text atau radio
            const el = elemen[0];
            if (el.type === 'radio') {
                const selected = document.querySelector(`[name="jawaban_${i}"]:checked`);
                jawaban.push(selected ? selected.value : '');
            } else {
                jawaban.push(el.value.trim());
            }
        } else if (elemen.length > 1) {
            // Checkbox atau matching
            let nilai = [];
            elemen.forEach(e => {
                if ((e.type === 'checkbox' && e.checked) || e.tagName.toLowerCase() === 'select' || e.type === 'text') {
                    if (e.value.trim() !== '') nilai.push(e.value.trim());
                }
            });
            jawaban.push(nilai.length > 0 ? nilai.join(e.type === 'checkbox' ? ',' : '|') : '');
        } else {
            jawaban.push(''); // default kosong
        }
    }

    return jawaban;
}

function kirimJawaban() {
    const formData = new FormData();
    formData.append('id_siswa', document.getElementById('id_siswa').value);
    formData.append('nama_siswa', document.getElementById('nama_siswa').value);
    formData.append('kode_soal', document.getElementById('kode_soal').value);
    formData.append('total_soal', document.getElementById('total_soal').value);
    formData.append('waktu_sisa', document.getElementById('waktu_sisa').innerText || '00:00:00');

    const jawaban = getJawabanDariForm();
    jawaban.forEach((val, idx) => {
        formData.append(`jawaban[${idx}]`, val);
    });

    fetch('simpan_jawaban.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'sukses') {
            console.log('Jawaban berhasil disimpan otomatis.');
        } else {
            console.warn('Gagal simpan jawaban:', data.error || data.pesan);
        }
    });
}

// Jalankan simpan otomatis setiap 1 menit
setInterval(kirimJawaban, 60000); // 60.000 ms = 1 menit
</script>

</body>
</html>