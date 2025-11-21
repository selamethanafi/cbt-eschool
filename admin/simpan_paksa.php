<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';

// Cek otorisasi admin
check_login('admin');

// Ambil parameter dari URL
$kode_soal = mysqli_real_escape_string($koneksi, $_GET['kode_soal'] ?? '');
$id_siswa = mysqli_real_escape_string($koneksi, $_GET['id_siswa'] ?? '');

// Validasi parameter
if (empty($kode_soal) || empty($id_siswa)) {
    $_SESSION['error_message'] = 'Parameter tidak valid!';
    header('Location: monitor.php');
    exit;
}

// Ambil data jawaban siswa dari database
$sql = "SELECT * FROM jawaban_siswa WHERE id_siswa = '$id_siswa' AND kode_soal = '$kode_soal'";
$result = mysqli_query($koneksi, $sql);
$data_jawaban = mysqli_fetch_assoc($result);

if (!$data_jawaban) {
    $_SESSION['error_message'] = 'Data jawaban siswa tidak ditemukan!';
    header('Location: monitor.php');
    exit;
}

// Ambil nama siswa
$sql_siswa = "SELECT nama_siswa FROM siswa WHERE id_siswa = '$id_siswa'";
$result_siswa = mysqli_query($koneksi, $sql_siswa);
$data_siswa = mysqli_fetch_assoc($result_siswa);
$nama_siswa = $data_siswa['nama_siswa'] ?? '';

// Ekstrak data yang diperlukan
$final_jawaban = $data_jawaban['jawaban_siswa'];
$waktu_sisa = $data_jawaban['waktu_sisa']; // dalam detik
$waktu_sisa_menit = round($waktu_sisa / 60); // konversi ke menit

// Fungsi untuk validasi jawaban
function allBenarSalah($arr) {
    if (!is_array($arr)) return false;
    foreach ($arr as $v) {
        $v = strtolower(trim($v));
        if (!in_array($v, ['benar', 'salah'])) return false;
    }
    return true;
}

// Ambil kunci jawaban soal
$q_soal = mysqli_query($koneksi, "SELECT kunci FROM soal WHERE kode_soal='$kode_soal'");
$data_soal = mysqli_fetch_assoc($q_soal);
$kunci_jawaban = $data_soal['kunci'] ?? '';

if (empty($kunci_jawaban)) {
    $_SESSION['error_message'] = 'Kunci jawaban tidak ditemukan!';
    header('Location: monitor.php');
    exit;
}

// Fungsi hapus koma di luar tanda []
function removeCommasOutsideBrackets($str) {
    $result = '';
    $in_brackets = false;
    for ($i = 0; $i < strlen($str); $i++) {
        $char = $str[$i];
        if ($char === '[') $in_brackets = true;
        if ($char === ']') $in_brackets = false;
        if ($char === ',' && !$in_brackets) continue;
        $result .= $char;
    }
    return $result;
}
$kuncifix = removeCommasOutsideBrackets($kunci_jawaban);

// Parsing kunci dan jawaban siswa
preg_match_all('/\[(.*?)\]/', $kuncifix, $kunci_matches);
preg_match_all('/\[(.*?)\]/', $final_jawaban, $jawaban_matches);

$kunci_array = $kunci_matches[1];
$jawaban_array = $jawaban_matches[1];

$jawaban_siswa_arr = [];
foreach ($jawaban_array as $item) {
    if (strpos($item, ':') !== false) {
        list($nomer_jawab, $isi_jawab) = explode(':', $item, 2);
        $jawaban_siswa_arr[$nomer_jawab] = $isi_jawab;
    }
}

$total_soal = count($kunci_array);
$benar = 0;
$salah = 0;
$kurang_lengkap = 0;
$nilai_total = 0;
$nilai_per_soal = $total_soal > 0 ? 100 / $total_soal : 0;

$ada_uraian = false;

// Proses penilaian
for ($i = 0; $i < $total_soal; $i++) {
    list($nomer_kunci, $isi_kunci) = explode(':', $kunci_array[$i], 2);
    $isi_jawaban = $jawaban_siswa_arr[$nomer_kunci] ?? '';

    // Ambil tipe soal
    $q_tipe = mysqli_query($koneksi, "SELECT tipe_soal FROM butir_soal WHERE kode_soal = '$kode_soal' AND nomer_soal = '$nomer_kunci'");
    $data_tipe = mysqli_fetch_assoc($q_tipe);
    $tipe_soal = strtolower($data_tipe['tipe_soal'] ?? '');

    $skor = 0;

    if (in_array($tipe_soal, ['benar/salah', 'menjodohkan'])) {
        $kunci_opsi = array_map('strtolower', array_map('trim', explode('|', $isi_kunci)));
        $jawaban_opsi = array_map('strtolower', array_map('trim', explode('|', $isi_jawaban)));
        $jumlah_kunci = count($kunci_opsi);
        $nilai_per_opsi = $nilai_per_soal / $jumlah_kunci;
        $jumlah_benar = 0;

        for ($j = 0; $j < $jumlah_kunci; $j++) {
            if (isset($jawaban_opsi[$j]) && $kunci_opsi[$j] === $jawaban_opsi[$j]) {
                $jumlah_benar++;
            }
        }

        $skor = $jumlah_benar * $nilai_per_opsi;

        if ($jumlah_benar == $jumlah_kunci) $benar++;
        elseif ($jumlah_benar == 0) $salah++;
        else $kurang_lengkap++;

    } elseif ($tipe_soal === 'pilihan ganda kompleks') {
        $kunci_opsi = array_map('strtolower', array_map('trim', explode(',', str_replace('|', ',', $isi_kunci))));
        $jawaban_opsi = array_map('strtolower', array_map('trim', explode(',', str_replace('|', ',', $isi_jawaban))));

        $jumlah_kunci = count($kunci_opsi);
        $jumlah_benar = 0;

        foreach ($jawaban_opsi as $jawab) {
            if (!in_array($jawab, $kunci_opsi)) {
                $skor = 0;
                $salah++;
                goto selesai_pilgan_kompleks;
            }
        }

        foreach ($jawaban_opsi as $jawab) {
            if (in_array($jawab, $kunci_opsi)) $jumlah_benar++;
        }

        if ($jumlah_benar === $jumlah_kunci) {
            $skor = $nilai_per_soal;
            $benar++;
        } else {
            $skor = ($jumlah_benar / $jumlah_kunci) * $nilai_per_soal;
            $kurang_lengkap++;
        }

        selesai_pilgan_kompleks:
        ;

    } else {
        // PG tunggal atau uraian
        if ($tipe_soal === 'uraian') {
            $skor = 0;
            $kurang_lengkap++;
            $ada_uraian = true;
        } elseif (strtolower(trim($isi_kunci)) === strtolower(trim($isi_jawaban))) {
            $skor = $nilai_per_soal;
            $benar++;
        } elseif (empty($isi_jawaban)) {
            $kurang_lengkap++;
        } else {
            $salah++;
        }
    }

    $nilai_total += $skor;
}

$status_penilaian = $ada_uraian ? 'perlu_dinilai' : 'selesai';
$tanggal_ujian = date('Y-m-d H:i:s');

// Simpan hasil penilaian
$final_jawaban_esc = mysqli_real_escape_string($koneksi, $final_jawaban);
$kunci_esc = mysqli_real_escape_string($koneksi, $kunci_jawaban);
$nama_siswa_esc = mysqli_real_escape_string($koneksi, $nama_siswa);

$q_nilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE id_siswa='$id_siswa' AND kode_soal='$kode_soal'");

if (mysqli_num_rows($q_nilai) > 0) {
    $sql_upd = "UPDATE nilai SET 
                nama_siswa = '$nama_siswa_esc',
                total_soal = '$total_soal',
                jawaban_benar = '$benar',
                jawaban_salah = '$salah',
                jawaban_kurang = '$kurang_lengkap',
                jawaban_siswa = '$final_jawaban_esc',
                kunci = '$kunci_esc',
                nilai = '$nilai_total',
                tanggal_ujian = '$tanggal_ujian',
                status_penilaian = '$status_penilaian'
                WHERE id_siswa = '$id_siswa' AND kode_soal = '$kode_soal'";
    mysqli_query($koneksi, $sql_upd);
} else {
    $sql_ins = "INSERT INTO nilai 
                (id_siswa, nama_siswa, kode_soal, total_soal, jawaban_benar, jawaban_salah, jawaban_kurang, jawaban_siswa, kunci, nilai, tanggal_ujian, status_penilaian)
                VALUES
                ('$id_siswa', '$nama_siswa_esc', '$kode_soal', '$total_soal', '$benar', '$salah', '$kurang_lengkap', '$final_jawaban_esc', '$kunci_esc', '$nilai_total', '$tanggal_ujian', '$status_penilaian')";
    mysqli_query($koneksi, $sql_ins);
}

// Update status ujian
mysqli_query($koneksi, "UPDATE jawaban_siswa SET status_ujian = 'Selesai' WHERE id_siswa='$id_siswa' AND kode_soal='$kode_soal'");

// Set pesan sukses
$_SESSION['success_message'] = "Ujian berhasil disimpan paksa untuk siswa: $nama_siswa";
header('Location: monitor.php');
exit;
?>