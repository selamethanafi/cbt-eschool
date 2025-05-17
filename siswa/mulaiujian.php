<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

$kode_soal = $_POST['kode_soal'] ?? $_GET['kode_soal'] ?? '';
$token = $_POST['token'];
$id_siswa = $_POST['id_siswa'];

if (empty($kode_soal)) {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Kode soal Tidak Tersedia';
    header('Location: ujian.php');
    exit;
}
// Siapkan query update dengan prepared statement
$stmt = $koneksi->prepare("UPDATE jawaban_siswa SET status_ujian = 'Aktif' WHERE id_siswa = ? AND kode_soal = ?");
if (!$stmt) {
    die("Prepare gagal: " . $koneksi->error);
}

// Binding parameter dan eksekusi
$stmt->bind_param("ss", $id_siswa, $kode_soal);
if (!$stmt->execute()) {
    die("Eksekusi gagal: " . $stmt->error);
}
// Ambil data siswa
$q_siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'");
$data_siswa = mysqli_fetch_assoc($q_siswa);
// Ambil data soal
$q_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal = '$kode_soal'");
$data_soal = mysqli_fetch_assoc($q_soal);

if (!$data_soal) {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Soal tidak ditemukan.';
    header('Location: ujian.php');
    exit;
}

if (strtolower($data_soal['status']) !== 'aktif') {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Soal Tidak Aktif! Silakan hubungi pengawas.';
    header('Location: ujian.php');
    exit;
} 
// Cek jika tanggal hari ini kurang dari tanggal soal (belum dimulai)
$tanggal_soal = $data_soal['tanggal'];
$tanggal_hari_ini = date('Y-m-d');

if (strtotime($tanggal_hari_ini) < strtotime($tanggal_soal)) {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Soal belum bisa dikerjakan. Jadwal ujian belum dimulai.';
    header('Location: ujian.php');
    exit;
}
if ($kelas_siswa !== $data_soal['kelas']) {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Soal ini bukan untuk kelas kamu.';
    header('Location: ujian.php');
    exit;
}
if ($token !== $data_soal['token']) {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Token tidak Valid.';
    header('Location: ujian.php');
    exit;
}


// Cek jika siswa sudah pernah mengerjakan
$q_nilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa = '$id_siswa' AND kode_soal = '$kode_soal'");
if (mysqli_num_rows($q_nilai) > 0) {
    $_SESSION['alert'] = true;
    $_SESSION['warning_message'] = 'Kamu sudah mengerjakan soal ini.';
    header('Location: ujian.php');
    exit;
}

// Get remaining time and answers
$waktu_sisa = 0;
$get_waktu = mysqli_query($koneksi, "SELECT waktu_sisa, jawaban_siswa FROM jawaban_siswa WHERE kode_soal='$kode_soal' AND id_siswa='$id_siswa'");
$jawaban_tersimpan = [];
if ($w = mysqli_fetch_assoc($get_waktu)) {
    $waktu_sisa = (int) $w['waktu_sisa'];
    $string_jawaban = $w['jawaban_siswa'];
    preg_match_all('/\[(\d+):([^\]]+)\]/', $string_jawaban, $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $nomor = (int) $match[1];
        $jawab = $match[2];

        // Handle matching questions
        if (strpos($jawab, '|') !== false && substr_count($jawab, ':') > 1) {
            $pasangan = explode('|', $jawab);
            $hasil = [];
            foreach ($pasangan as $p) {
                if (substr_count($p, ':') === 1) {
                    [$kiri, $kanan] = explode(':', $p, 2);
                    $kiri = trim($kiri);
                    $kanan = trim($kanan);
                    if (!empty($kiri) && !empty($kanan)) {
                        $hasil[$kiri] = $kanan;
                    }
                }
            }
            $jawaban_tersimpan[$nomor] = $hasil;
        }
        // Other question types remain unchanged
        elseif (strpos($jawab, '|') !== false) {
            $jawaban_tersimpan[$nomor] = explode('|', $jawab);
        } elseif (strpos(haystack: $jawab, needle: ',') !== false) {
            $jawaban_tersimpan[$nomor] = explode(separator: ',', string: $jawab);
        } else {
            $jawaban_tersimpan[$nomor] = $jawab;
        }
    }
}

// Get all questions
$q = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE kode_soal='$kode_soal' ORDER BY nomer_soal ASC");
$soal = [];
while ($s = mysqli_fetch_assoc($q)) {
    $soal[] = $s;
}

$q_tema = mysqli_query($koneksi, "SELECT * FROM pengaturan WHERE id = 1 LIMIT 1");
$data_tema = mysqli_fetch_assoc($q_tema);
$warna_tema = $data_tema['warna_tema'] ?? '#0d6efd';
$interval_ms = ((int)$data_tema['waktu_sinkronisasi']) * 1000;
$query = "SELECT jawaban_siswa FROM jawaban_siswa 
          WHERE id_siswa = '$id_siswa' AND kode_soal = '$kode_soal'";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

// Parse format [1:...][2:...]
$jawaban = $row['jawaban_siswa'] ?? '';
preg_match_all('/\[(\d+):([^\]]*)\]/', $jawaban, $matches, PREG_SET_ORDER);

// Buat array status
$status_soal = [];
foreach ($matches as $match) {
    $nomor = $match[1];
    $isi = trim($match[2]);
    $status_soal[$nomor] = !empty($isi); // true jika terisi, false jika kosong
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
    <style>
        .question-container {
            display: none;
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .question-container.active {
            display: block;
            min-height: 300px;
        }

        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .question-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 15px;
        }

        .question-nav button {
            min-width: 40px;
        }

        #loadingOverlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            color: white;
        }

        #autoSaveStatus {
            display: none;
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            text-align: center;
        }

        .timer {
            font-weight: bold;
            font-size: 1.2rem;
            color: #dc3545;
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px 10px;
            border-radius: 4px;
        }

        .question-text {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .answer-option {
            margin-bottom: 8px;
        }

        .matching-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .matching-table td {
            padding: 8px;
            vertical-align: middle;
        }

        .essay-textarea {
            width: 100%;
            min-height: 150px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .submit-btn {
            margin-top: 20px;
        }

        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.3s ease;
        }

        .spinner-container {
            text-align: center;
            color: white;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        * Animasi smooth */ .question-nav-container {
            transition: all 0.3s ease;
        }

        /* Scrollbar custom */
        .question-nav-container::-webkit-scrollbar {
            width: 8px;
        }

        .question-nav-container::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .question-nav-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .question-nav-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .nav-btn {
            width: 40px;
            height: 40px;
            border: 2px solid #0d6efd;
            /* Warna outline biru */
            color: #0d6efd;
            background: transparent;
            border-radius: 50%;
            margin: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        /* Tombol sudah diisi */
        .nav-btn[data-answered="true"] {
            background-color: #198754;
            /* Warna hijau */
            border-color: #198754;
            color: white;
        }

        /* Indicator dot untuk soal terjawab */
        .nav-btn[data-answered="true"]::after {
            content: '';
            position: absolute;
            top: -3px;
            right: -3px;
            width: 10px;
            height: 10px;
            background: #ffc107;
            /* Warna kuning */
            border-radius: 50%;
            border: 1px solid white;
        }

        /* Hover Effect */
        .nav-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
        }
    </style>
    <script>
        const syncInterval = <?= $interval_ms ?>;
    </script>
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
    <div style="height: 75px;"></div>
    <div class="wrapper">
        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg fixed-top"
                style="border-bottom:5px solid <?php echo htmlspecialchars($warna_tema); ?> !important;">
                <a class="navbar-brand ms-3" href="#">
                    <img src="../assets/images/<?php echo $data_tema['logo_sekolah']; ?>" alt="Logo" style="height: 36px;">
                </a>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align ms-auto">
                        <!-- Timer Display -->
                        <li class="nav-item me-3">
                        </li>
                        <li class="nav-item me-3">
                            <div class="dropdown-toggle" href="#" style="font-weight: bold; font-size: 1.2rem;"
                                id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                            </div>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-wide">
                                <li><span class="dropdown-item-text"><strong><?php echo $nama_siswa; ?></strong></span>
                                </li>
                                <li><span class="dropdown-item-text"><?php echo $kelas_siswa . $rombel_siswa; ?></span>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow">
                                <div class="card-header text-white"
                                    style="background-color:<?= htmlspecialchars($warna_tema) ?>">
                                    <h4 class="mb-0 text-white">
                                        <b style="background-color:#ffffff;padding:5px;border-radius:20px;color:black;"
                                            id="currentQuestionNumber">Nomor</b>
                                        <b style="background-color:#ffffff;padding:5px;border-radius:20px;color:red;"
                                            id="currentQuestionNumber"><i class="fas fa-clock me-1"></i>
                                            <span id="timer">00:00</span></b>
                                    </h4>
                                </div>
                                <div class="card-body wadah">
                                    <div id="autoSaveStatus"></div>
                                    <form id="formUjian" method="post" action="simpan_jawaban.php">
                                        <input type="hidden" name="waktu_sisa" id="waktu_sisa">
                                        <input type="hidden" name="kode_soal"
                                            value="<?= htmlspecialchars($kode_soal) ?>">

                                        <?php foreach ($soal as $index => $s):
                                            $no = $s['nomer_soal'];
                                            $tipe = $s['tipe_soal'];
                                            $pertanyaan = $s['pertanyaan'];
                                            $jawaban = $jawaban_tersimpan[$no] ?? '';
                                            ?>
                                            <div class="question-container" id="soal-<?= $index ?>">
                                                <div class="question-text">Soal <?= $no ?>: <?= $pertanyaan ?></div>

                                                <?php if ($tipe == 'Pilihan Ganda'): ?>
                                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                                        <div class="answer-option">
                                                            <label class="form-check-label">
                                                                <input type="radio" class="form-check-input"
                                                                    name="jawaban[<?= $no ?>]" value="pilihan_<?= $i ?>"
                                                                    <?= $jawaban == 'pilihan_' . $i ? 'checked' : '' ?>>
                                                                <?= $s['pilihan_' . $i] ?>
                                                            </label>
                                                        </div>
                                                    <?php endfor; ?>

                                                <?php elseif ($tipe == 'Pilihan Ganda Kompleks'):
                                                        if (!is_array($jawaban)) {
                                                            $jawaban = [$jawaban];
                                                        }
                                                    ?>
                                                    <?php for ($i = 1; $i <= 4; $i++): ?>
                                                        <div class="answer-option">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" class="form-check-input"
                                                                    name="jawaban[<?= $no ?>][]" value="pilihan_<?= $i ?>"
                                                                    <?= in_array('pilihan_' . $i, $jawaban) ? 'checked' : '' ?>>
                                                                <?= $s['pilihan_' . $i] ?>
                                                            </label>
                                                        </div>
                                                    <?php endfor; ?>

                                                <?php elseif ($tipe == 'Benar/Salah'): ?>
                                                    <table class="matching-table">
                                                        <?php for ($i = 1; $i <= 4; $i++):
                                                            $pernyataan = $s['pilihan_' . $i];
                                                            if (trim($pernyataan) == '')
                                                                continue;
                                                            $jawab = $jawaban[$i - 1] ?? '';
                                                            ?>
                                                            <tr>
                                                                <td><?= $pernyataan ?></td>
                                                                <td>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="jawaban[<?= $no ?>][<?= $i ?>]" value="Benar"
                                                                            <?= $jawab == 'Benar' ? 'checked' : '' ?>>
                                                                        <label class="form-check-label">Benar</label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            name="jawaban[<?= $no ?>][<?= $i ?>]" value="Salah"
                                                                            <?= $jawab == 'Salah' ? 'checked' : '' ?>>
                                                                        <label class="form-check-label">Salah</label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endfor; ?>
                                                    </table>

                                                <?php elseif ($tipe == 'Menjodohkan'): ?>
    <?php
    // Ambil string jawaban_benar dari database, misal: "[10:saya jua:asdasd]"
    $raw = trim($s['jawaban_benar'], "[]"); // hilangkan []

    // Hilangkan nomor soal di depan, misal "10:" di "[10:saya jua:asdasd]"
    $raw = preg_replace('/^\d+\s*:/', '', $raw);

    // Pecah berdasarkan |
    $pasangan_list = explode('|', $raw);

    $opsi = [];
    foreach ($pasangan_list as $item) {
        // Pastikan ':' ada dan hanya 2 bagian
        if (strpos($item, ':') !== false) {
            list($kiri, $kanan) = explode(':', $item, 2);
            $kiri = trim($kiri);
            $kanan = trim($kanan);
            if ($kiri !== '' && $kanan !== '') {
                $opsi[] = ['kiri' => $kiri, 'kanan' => $kanan];
            }
        }
    }

    // Jangan batasi jumlah opsi dengan array_slice lagi, tampilkan semua

    // Jawaban yang sudah tersimpan, jika array, jika tidak set array kosong
    $jawaban_soal = (is_array($jawaban)) ? $jawaban : [];

    // Daftar pilihan kanan yang unik dan diacak
    $daftar_kanan = array_values(array_unique(array_column($opsi, 'kanan')));
    shuffle($daftar_kanan);
    ?>

    <?php if (count($opsi) === 0): ?>
        <div class="alert alert-warning">Soal menjodohkan belum memiliki pasangan yang valid.</div>
    <?php else: ?>
        <?php foreach ($opsi as $p): ?>
            <input type="hidden" name="soal_kiri[<?= $no ?>][]" value="<?= htmlspecialchars($p['kiri']) ?>">
        <?php endforeach; ?>

        <table class="matching-table">
            <?php foreach ($opsi as $p):
                $kiri = $p['kiri'];
                $selected = $jawaban_soal[$kiri] ?? '';
            ?>
                <tr>
                    <td><?= htmlspecialchars($kiri) ?></td>
                    <td>
                        <select name="jawaban[<?= $no ?>][<?= htmlspecialchars($kiri) ?>]" class="form-select">
                            <option value="">-- Pilih --</option>
                            <?php foreach ($daftar_kanan as $dk): ?>
                                <option value="<?= htmlspecialchars($dk) ?>" <?= ($selected === $dk) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($dk) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>




                                                <?php elseif ($tipe == 'Uraian'): ?>
                                                    <textarea name="jawaban[<?= $no ?>]"
                                                        class="essay-textarea"><?= htmlspecialchars($jawaban) ?></textarea>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>

                                        <!-- Di bagian HTML (ganti bagian navigation-buttons) -->
                                        <div class="navigation-buttons">
                                            <div style="flex: 1;">
                                                <!-- Tombol Sebelumnya (kiri) -->
                                                <button type="button" class="btn btn-primary" id="prevBtn"
                                                    onclick="prevSoal()" style="display: none; float: left;">
                                                    <i class="fas fa-arrow-left me-1"></i> Sebelumnya
                                                </button>
                                            </div>
                                            <div style="flex: 1; text-align: right;">
                                                <!-- Tombol Berikutnya/Selesai (kanan) -->
                                                <button type="button" class="btn btn-primary" id="nextBtn"
                                                    onclick="nextSoal()" style="float: right;">
                                                    Berikutnya <i class="fas fa-arrow-right ms-1"></i>
                                                </button>
                                                <button type="submit" class="btn btn-success" id="submitBtn"
                                                    style="display: none; float: right;">
                                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <button id="navToggle" class="btn btn-primary rounded-circle"
                                        style="border:none;position: fixed; bottom: 80px; right: 20px; z-index: 1000; width: 50px; height: 50px;background-color:<?php echo htmlspecialchars($warna_tema); ?>;">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <!-- Modifikasi question-nav -->
                                    <div class="question-nav-container"
                                        style="position: fixed; bottom: 100px; right: 20px; z-index: 1100; max-height: 60vh;max-width: 59vh; overflow-y: auto; display: none;">
                                        <div class="card shadow">
                                            <div class="card-header text-white"
                                                style="background-color:<?php echo htmlspecialchars($warna_tema); ?>;">
                                                Daftar Soal
                                                <button type="button" class="close text-black" aria-label="Close"
                                                    style="float: right;">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="card-body p-2">
                                                <div class="question-nav d-flex flex-wrap" style="gap: 5px;">
                                                    <?php
                                                    // Parse jawaban yang tersimpan
                                                    if (isset($jawaban_siswa)) {
                                                        preg_match_all('/\[(\d+):([^\]]*)\]/', $jawaban_siswa, $matches, PREG_SET_ORDER);
                                                    }
                                                    $jawaban_status = [];
                                                    foreach ($matches as $match) {
                                                        $nomor = $match[1];
                                                        $jawaban_status[$nomor] = !empty(trim($match[2]));
                                                    }

                                                    foreach ($soal as $index => $s):
                                                        $no = $s['nomer_soal'];
                                                        $is_answered = $jawaban_status[$no] ?? false;
                                                        ?>
                                                        <button type="button"
                                                            class="btn btn-sm <?= $is_answered ? 'btn-primary' : 'btn-secondary' ?>"
                                                            onclick="tampilSoal(<?= $index ?>); hideNav()"
                                                            data-nomor="<?= $no ?>">
                                                            <strong style="font-size:16px;"><?= $no ?></strong>
                                                            <?php if ($is_answered): ?>
                                
                                                            <?php endif; ?>
                                                        </button>
                                                    <?php endforeach; ?>
                                                </div>
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
    <?php include '../inc/check_activity.php'; ?>
    <script>
        // Timer Logic
        let waktu = <?= $waktu_sisa > 0 ? ($waktu_sisa * 60) : 3600 ?>;
        let soalAktif = 0;
        const totalSoal = <?= count($soal) ?>;

        // Tampilkan loading overlay saat pertama kali load
        document.getElementById('loadingOverlay').style.display = 'flex';
        setTimeout(() => {
            document.getElementById('loadingOverlay').style.display = 'none';
        }, 500);

        function updateTimer() {
            let menit = Math.floor(waktu / 60);
            let detik = waktu % 60;
            document.getElementById('timer').innerText = `${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;
            waktu--;

            if (waktu < 0) {
                // Waktu habis, submit form
                document.getElementById('formUjian').submit();
            }
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            // Atur tombol Sebelumnya
            prevBtn.style.display = soalAktif > 0 ? 'block' : 'none';

            // Atur tombol Berikutnya/Selesai
            if (soalAktif < totalSoal - 1) {
                nextBtn.style.display = 'block';
                submitBtn.style.display = 'none';
            } else {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'block';
            }
        }

        function tampilSoal(index) {
            document.querySelectorAll('.question-container').forEach(s => s.classList.remove('active'));
            const soal = document.getElementById('soal-' + index);
            if (soal) {
                soal.classList.add('active');
                soalAktif = index;
                let base = <?= $soal[0]['nomer_soal'] ?>;
                let currentNo = base + index;
                document.getElementById('currentQuestionNumber').textContent = currentNo.toString().padStart(2, '0');

                updateNavigationButtons();

                // Scroll ke atas soal
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }

        function nextSoal() {
            if (soalAktif < totalSoal - 1) {
                tampilSoal(soalAktif + 1);
            }
        }

        function prevSoal() {
            if (soalAktif > 0) {
                tampilSoal(soalAktif - 1);
            }
        }



        // Panggil pertama kali untuk inisialisasi
        updateNavigationButtons();
        // Tampilkan soal pertama saat halaman dimuat
        window.onload = function () {
            tampilSoal(0);
            setInterval(updateTimer, 1000);
            updateTimer(); // Panggil sekali untuk inisialisasi
        };

        /// Auto save setiap 30 detik
        setInterval(() => {
            const form = document.getElementById('formUjian');
            const data = new FormData(form);
            data.append('waktu_sisa', Math.ceil(waktu / 60));

            fetch('autosave_jawaban.php', {
                method: 'POST',
                body: data
            })
                .then(res => res.text())
                .then(txt => console.log('Auto-saved:', txt));
        }, syncInterval);

        document.addEventListener("DOMContentLoaded", function () {
            var base64Text = "<?php echo $encryptedText; ?>";
            if (base64Text) {
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
        // Simpan fungsi asli yang sudah ada
        const originalNextSoal = nextSoal;
        const originalPrevSoal = prevSoal;

        // Override fungsi nextSoal dengan spinner
        nextSoal = function () {
            // Tampilkan spinner
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Jalankan fungsi original setelah 1 detik
            setTimeout(() => {
                originalNextSoal.call(this);
                document.getElementById('loadingOverlay').style.display = 'none';
            }, 300);
        };

        // Override fungsi prevSoal dengan spinner
        prevSoal = function () {
            // Tampilkan spinner
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Jalankan fungsi original setelah 1 detik
            setTimeout(() => {
                originalPrevSoal.call(this);
                document.getElementById('loadingOverlay').style.display = 'none';
            }, 300);
        };

        const originalTampilSoal = tampilSoal;

        // 2. Override fungsi tampilSoal dengan spinner
        tampilSoal = function (index) {
            // Tampilkan spinner
            document.getElementById('loadingOverlay').style.display = 'flex';

            // Jalankan fungsi original setelah 1 detik
            setTimeout(() => {
                originalTampilSoal(index);
                document.getElementById('loadingOverlay').style.display = 'none';
            }, 300);
        };

        // 3. Update event handler untuk semua tombol navigasi
        document.querySelectorAll('.question-nav button').forEach(button => {
            button.addEventListener('click', function () {
                // Tampilkan spinner segera setelah diklik
                document.getElementById('loadingOverlay').style.display = 'flex';
            });
        });

        // Fungsi toggle navigasi
        function toggleNav() {
            const navContainer = document.querySelector('.question-nav-container');
            if (navContainer.style.display === 'none') {
                navContainer.style.display = 'block';
            } else {
                navContainer.style.display = 'none';
            }
        }

        // Fungsi sembunyikan navigasi
        function hideNav() {
            document.querySelector('.question-nav-container').style.display = 'none';
        }

        // Event listeners
        document.getElementById('navToggle').addEventListener('click', toggleNav);
        document.querySelector('.card-header button.close').addEventListener('click', hideNav);

        // Atur ukuran tombol navigasi soal
        window.addEventListener('load', function () {
            const buttons = document.querySelectorAll('.question-nav button');
            buttons.forEach(btn => {
                btn.style.width = '40px';
                btn.style.height = '40px';
                btn.style.display = 'flex';
                btn.style.alignItems = 'center';
                btn.style.justifyContent = 'center';
            });
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
  // Fungsi cek apakah soal nomor no terisi jawaban
  function isSoalTerisi(no) {
    const inputs = document.querySelectorAll(`[name^="jawaban[${no}]"]`);
    for (const input of inputs) {
      if ((input.type === 'radio' || input.type === 'checkbox') && input.checked) {
        return true;
      }
      if ((input.tagName.toLowerCase() === 'textarea' || input.type === 'text' || input.tagName.toLowerCase() === 'select') && input.value.trim() !== '') {
        return true;
      }
    }
    return false;
  }

  // Update warna tombol di sidebar sesuai status isi soal
  function updateTombolStatus() {
    document.querySelectorAll('.question-nav button[data-nomor]').forEach(btn => {
      const no = btn.getAttribute('data-nomor');
      if (isSoalTerisi(no)) {
        btn.classList.add('btn-primary');
        btn.classList.remove('btn-secondary');
      } else {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-secondary');
      }
    });
  }

  // Pasang event listener untuk input jawaban, agar realtime update tombol
  const form = document.getElementById('formUjian');
  if (form) {
    form.querySelectorAll('input[name^="jawaban"], textarea[name^="jawaban"], select[name^="jawaban"]').forEach(input => {
      input.addEventListener('change', updateTombolStatus);
      if (input.tagName.toLowerCase() === 'textarea' || input.type === 'text') {
        input.addEventListener('input', updateTombolStatus);
      }
    });
  }

  // Jalankan update tombol awal saat halaman siap
  updateTombolStatus();
});

 // Update waktu_sisa setiap detik tanpa mengubah fungsi updateTimer
    setInterval(() => {
        document.getElementById('waktu_sisa').value = waktu;
    }, 1000);

    // Tangani klik tombol "Selesai"
    document.getElementById('submitBtn').addEventListener('click', function(e) {
        e.preventDefault(); // Jangan langsung submit

        const sisaDetik = parseInt(waktu) || 0;
        const menit = Math.floor(sisaDetik / 60);
        const detik = sisaDetik % 60;
        const formatWaktu = `${menit.toString().padStart(2, '0')}:${detik.toString().padStart(2, '0')}`;

        Swal.fire({
            title: 'Selesaikan Ujian?',
            html: `Sisa waktu Anda: <strong>${formatWaktu}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formUjian').submit();
            }
        });
    });
</script>

</body>

</html>