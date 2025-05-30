<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/datasiswa.php';

// Fungsi bantu untuk validasi jawaban Benar/Salah
function allBenarSalah($arr) {
    if (!is_array($arr)) return false;
    foreach ($arr as $v) {
        $v = strtolower(trim($v));
        if (!in_array($v, ['benar', 'salah'])) {
            return false;
        }
    }
    return true;
}

// [2] Dapatkan semua input
$kode_soal = mysqli_real_escape_string($koneksi, $_POST['kode_soal'] ?? '');
$waktu_sisa = (int)($_POST['waktu_sisa'] ?? 0);
$jawaban = $_POST['jawaban'] ?? [];
$soal_kiri = $_POST['soal_kiri'] ?? [];

$q_nilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa = '$id_siswa' AND kode_soal = '$kode_soal'");
if (mysqli_num_rows($q_nilai) > 0) {
    echo json_encode([
        'status' => 'already_done',
        'message' => 'Kamu sudah mengerjakan soal ini.',
        'redirect_url' => 'ujian.php'
    ]);
    exit;
}
// [3] Debugging - Catat semua data input
error_log("Data Diterima:\n" . print_r([
    'kode_soal' => $kode_soal,
    'waktu_sisa' => $waktu_sisa,
    'jawaban' => $jawaban,
    'soal_kiri' => $soal_kiri
], true));

// [4] Proses semua jawaban
$format_jawaban = [];

foreach ($jawaban as $nomor => $nilai) {
    $nomor = (int)$nomor;

    // 4a. Jawaban Menjodohkan (array asosiatif kiri => kanan)
    if (isset($soal_kiri[$nomor]) && is_array($soal_kiri[$nomor]) && is_array($nilai)) {
        $pasangan = [];
        foreach ($nilai as $kiri => $kanan) {
            $kiri = trim($kiri);
            $kanan = trim($kanan);
            if ($kiri !== '' && $kanan !== '') {
                $pasangan[] = "$kiri:$kanan";
            }
        }
        $format_jawaban[] = "[$nomor:" . implode('|', $pasangan) . "]";
        continue;
    }

    // 4b. Jawaban Benar/Salah (array indexed dengan nilai 'Benar' atau 'Salah')
    if (allBenarSalah($nilai)) {
        $format_jawaban[] = "[$nomor:" . implode('|', array_map('trim', $nilai)) . "]";
        continue;
    }

    // 4c. Jawaban Pilihan Ganda Kompleks (array indexed pilihan_1, pilihan_3, dll)
    if (is_array($nilai)) {
        $format_jawaban[] = "[$nomor:" . implode(',', array_map('trim', $nilai)) . "]";
        continue;
    }

    // 4d. Jawaban Pilihan Ganda Tunggal atau Uraian (string)
    $format_jawaban[] = "[$nomor:" . trim($nilai) . "]";
}

// [5] Gabungkan semua jawaban tanpa pemisah koma antar blok []
$final_jawaban = implode('', $format_jawaban);

// [6] Validasi akhir
if (empty($final_jawaban)) {
    error_log("Final Jawaban Kosong. Format Jawaban:\n" . print_r($format_jawaban, true));
    die(json_encode([
        'status' => 'error', 
        'message' => 'Tidak ada jawaban valid',
        'debug' => [
            'input_jawaban' => $jawaban,
            'input_soal_kiri' => $soal_kiri,
            'processed' => $format_jawaban
        ]
    ]));
}

// [7] Simpan ke database
$sql = "INSERT INTO jawaban_siswa (id_siswa, kode_soal, jawaban_siswa, waktu_sisa, nama_siswa) 
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            jawaban_siswa = VALUES(jawaban_siswa),
            waktu_sisa = VALUES(waktu_sisa),
            nama_siswa = VALUES(nama_siswa)";

$stmt = mysqli_prepare($koneksi, $sql);
if (!$stmt) {
    die(json_encode(['status' => 'error', 'message' => 'Prepare gagal: ' . mysqli_error($koneksi)]));
}

mysqli_stmt_bind_param($stmt, "sssis", $id_siswa, $kode_soal, $final_jawaban, $waktu_sisa, $nama_siswa);

if (!mysqli_stmt_execute($stmt)) {
    die(json_encode(['status' => 'error', 'message' => 'Execute gagal: ' . mysqli_error($koneksi)]));
}
$stmt = $koneksi->prepare("UPDATE jawaban_siswa SET status_ujian = 'Aktif' WHERE id_siswa = ? AND kode_soal = ?");
if (!$stmt) {
    die("Prepare gagal: " . $koneksi->error);
}

// Binding parameter dan eksekusi
$stmt->bind_param("ss", $id_siswa, $kode_soal);
if (!$stmt->execute()) {
    die("Eksekusi gagal: " . $stmt->error);
}
$now = date('Y-m-d H:i:s');
$stmt = mysqli_prepare($koneksi, "UPDATE siswa SET last_activity = ? WHERE id_siswa = ?");
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "ss", $now, $id_siswa);
if (!mysqli_stmt_execute($stmt)) {
    die("Execute failed: " . mysqli_stmt_error($stmt));
}

// [8] Berikan response dengan data debug
echo json_encode([
    'status' => 'success',
    'debug' => [
        'format_jawaban' => $format_jawaban,
        'final_jawaban' => $final_jawaban,
        'tipe_soal' => 'semua'
    ]
]);

mysqli_stmt_close($stmt);
?>
