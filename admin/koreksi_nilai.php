<?php
include '../koneksi/koneksi.php';

$id_soal = 1; // Uji coba tetap pada id_soal = 1
$id_siswa = 3;
$kode_soal = '';

// Ambil kode_soal
$q_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE id_soal = '$id_soal'");
$data_soal = mysqli_fetch_assoc($q_soal);
$kode_soal = $data_soal['kode_soal'];

// Ambil satu jawaban siswa
$q_jawaban = mysqli_query($koneksi, "SELECT * FROM jawaban_siswa WHERE kode_soal = '$kode_soal' AND id_siswa='$id_siswa ' ");
$data_jawaban = mysqli_fetch_assoc($q_jawaban);
$jawaban_siswa = $data_jawaban['jawaban_siswa'] ?? '';

// Ambil kunci jawaban
$q_kunci = mysqli_query($koneksi, "SELECT kunci FROM soal WHERE id_soal = '$id_soal'");
$data_kunci = mysqli_fetch_assoc($q_kunci);
$kunci = $data_kunci['kunci'] ?? '';

// Ekstrak semua blok [no:jawaban...]
preg_match_all('/\[(.*?)\]/', $kunci, $kunci_matches);
preg_match_all('/\[(.*?)\]/', $jawaban_siswa, $jawaban_matches);

$kunci_array = $kunci_matches[1];
$jawaban_array = $jawaban_matches[1];

$total_soal = count($kunci_array);
$benar = 0;
$salah = 0;
$nilai_total = 0;
$nilai_per_soal = $total_soal > 0 ? 100 / $total_soal : 0;
$kurang_tepat = [];

echo "<h3>Judul Soal: $kode_soal</h3>";
echo "<p>Jumlah Soal: $total_soal</p>";
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>No</th><th>Kunci</th><th>Jawaban Siswa</th><th>Skor</th></tr>";

for ($i = 0; $i < $total_soal; $i++) {
    list($nomer_kunci, $isi_kunci) = explode(':', $kunci_array[$i], 2);

    $isi_jawaban = '';
    if (isset($jawaban_array[$i])) {
        list($nomer_jawab, $isi_jawaban) = explode(':', $jawaban_array[$i], 2);
    }

    // Ambil tipe soal dari butir_soal
    $q_tipe = mysqli_query($koneksi, "SELECT tipe_soal FROM butir_soal WHERE kode_soal = '$kode_soal' AND nomer_soal = '$nomer_kunci'");
    $data_tipe = mysqli_fetch_assoc($q_tipe);
    $tipe_soal = strtolower($data_tipe['tipe_soal'] ?? '');

    $skor = 0;

    // Proses berdasarkan tipe soal
    if ($tipe_soal === 'benar/salah' || $tipe_soal === 'menjodohkan') {
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
        if ($skor > 0 && $skor < $nilai_per_soal) {
            $kurang_tepat[] = $nomer_kunci;
        }

    } elseif ($tipe_soal === 'pilihan ganda kompleks') {
        // Normalisasi separator
        $isi_kunci = str_replace('|', ',', $isi_kunci);
        $isi_jawaban = str_replace('|', ',', $isi_jawaban);

        $kunci_opsi = array_map('strtolower', array_map('trim', explode(',', $isi_kunci)));
        $jawaban_opsi = array_map('strtolower', array_map('trim', explode(',', $isi_jawaban)));

        // Cek validitas jawaban
        $valid = true;
        foreach ($jawaban_opsi as $jawaban) {
            if (!in_array($jawaban, $kunci_opsi)) {
                $valid = false;
                break;
            }
        }

        if (!$valid) {
            $skor = 0;
        } else {
            $jumlah_kunci = count($kunci_opsi);
            $nilai_per_opsi = $nilai_per_soal / $jumlah_kunci;
            $jumlah_benar = 0;

            foreach ($kunci_opsi as $opsi) {
                if (in_array($opsi, $jawaban_opsi)) {
                    $jumlah_benar++;
                }
            }

            $skor = $jumlah_benar * $nilai_per_opsi;
            if ($skor > 0 && $skor < $nilai_per_soal) {
                $kurang_tepat[] = $nomer_kunci;
            }
        }
    } else {
        // Tipe biasa
        if (strtolower(trim($isi_kunci)) === strtolower(trim($isi_jawaban))) {
            $skor = $nilai_per_soal;
        }
    }

    if ($skor > 0) {
        $benar++;
    } else {
        $salah++;
    }

    $nilai_total += $skor;

    echo "<tr>
        <td>$nomer_kunci</td>
        <td>$isi_kunci</td>
        <td>" . ($isi_jawaban ?: '-') . "</td>
        <td>" . round($skor, 2) . "</td>
    </tr>";
}

$nilai_akhir = round($nilai_total, 2);
$jumlah_kurang_tepat = count($kurang_tepat);

echo "</table>";
echo "<p>Jawaban Benar: $benar</p>";
echo "<p>Jawaban Salah: $salah</p>";
echo "<p>Jawaban Kurang Lengkap: $jumlah_kurang_tepat</p>";
echo "<p><strong>Nilai Akhir: $nilai_akhir%</strong></p>";
?>
