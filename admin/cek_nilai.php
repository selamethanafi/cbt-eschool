<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

$kode_soal = $_POST['kode_soal'] ?? '';
$id_siswa = $_POST['id_siswa'] ?? '';

// Ambil data soal
$q_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal = '$kode_soal'");
$data_soal = mysqli_fetch_assoc($q_soal);
$kode_soal = $data_soal['kode_soal'] ?? '';


// Ambil data siswa
$q_siswa = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'");
if (!$q_siswa) {
    die("Query gagal: " . mysqli_error($koneksi));
}
$data_siswa = mysqli_fetch_assoc($q_siswa);
$nama_siswa = $data_siswa['nama_siswa'] ?? 'Tidak ditemukan';

// Ambil jawaban siswa
$q_jawaban = mysqli_query($koneksi, "SELECT * FROM nilai WHERE kode_soal = '$kode_soal' AND id_siswa='$id_siswa'");
$data_jawaban = mysqli_fetch_assoc($q_jawaban);
$jawaban_siswa = $data_jawaban['jawaban_siswa'] ?? '';
$kunci = $data_soal['kunci'] ?? '';
// Bersihkan kunci
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

$kuncifix = removeCommasOutsideBrackets($kunci);
preg_match_all('/\[(.*?)\]/', $kuncifix, $kunci_matches);
preg_match_all('/\[(.*?)\]/', $jawaban_siswa, $jawaban_matches);

$kunci_array = $kunci_matches[1];
$jawaban_array = $jawaban_matches[1];

$total_soal = count($kunci_array);
$benar = 0;
$salah = 0;
$kurang_lengkap = 0;
$nilai_total = 0;
$nilai_per_soal = $total_soal > 0 ? 100 / $total_soal : 0;

$jawaban_siswa_arr = [];
foreach ($jawaban_array as $item) {
    if (strpos($item, ':') !== false) {
        list($nomer_jawab, $isi_jawab) = explode(':', $item, 2);
        $jawaban_siswa_arr[$nomer_jawab] = $isi_jawab;
    }
}
$nilai_akhir = round($nilai_total, 2);
echo "
<style>
  .header-nilai {
    background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
  }
  .header-nilai p {
    margin: 0.3rem 0;
    font-weight: 500;
  }
  .header-nilai h3 {
    font-weight: 700;
  }
  .header-col {
    border-right: 1px solid rgba(255,255,255,0.3);
  }
  .header-col:last-child {
    border-right: none;
  }
</style>
";

echo "
<div class='row header-nilai'>
  <div class='col-md-4 col-6 header-col'>
    <p><strong>Kode Soal:</strong> $kode_soal</p>
    <p><strong>Nama Siswa:</strong> $nama_siswa</p>
    <p><strong>Jumlah Soal:</strong> $total_soal</p>
  </div>
  <div class='col-md-4 col-6 header-col'>
    <p><strong>Benar:</strong> $benar</p>
    <p><strong>Salah:</strong> $salah</p>
    <p><strong>Kurang Lengkap:</strong> $kurang_lengkap</p>
  </div>
  <div class='col-md-4 text-center'>
    <p><strong>Skor Akhir:</strong></p>
    <h3 style='color:orange;'><strong>$nilai_akhir</strong></h3>
  </div>
</div>
";


echo "<div class='table-wrapper'>";
echo "<table id='tabel_nilai' class='table table-bordered table-striped'>";
echo "<thead>
        <tr>
            <th width='5%'>No Soal</th>
            <th width='30%'>Kunci</th>
            <th width='30%'>Jawaban Siswa</th>
            <th width='25%'>Skor</th>
            <th width='10%'>Status</th>
        </tr>
      </thead>";
echo "<tbody>";
 
for ($i = 0; $i < $total_soal; $i++) {
    list($nomer_kunci, $isi_kunci) = explode(':', $kunci_array[$i], 2);
    $isi_jawaban = $jawaban_siswa_arr[$nomer_kunci] ?? '';

    $q_tipe = mysqli_query($koneksi, "SELECT tipe_soal FROM butir_soal WHERE kode_soal = '$kode_soal' AND nomer_soal = '$nomer_kunci'");
    $data_tipe = mysqli_fetch_assoc($q_tipe);
    $tipe_soal = strtolower($data_tipe['tipe_soal'] ?? '');

    $skor = 0;
    $status = '';
    $jawaban_ditulis = '-';
    $detail_skor = '';

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
        $jawaban_ditulis = implode(' | ', $jawaban_opsi);

        if ($jumlah_benar == $jumlah_kunci) {
            $status = "✅ Benar"; $benar++;
        } elseif ($jumlah_benar == 0) {
            $status = "❌ Salah"; $salah++;
        } else {
            $status = "⚠️ Kurang Lengkap"; $kurang_lengkap++;
        }

        $detail_skor = "Skor: " . round($skor, 2) . "<br>Nilai/Opsi: " . round($nilai_per_opsi, 2) . "<br>Benar: $jumlah_benar / $jumlah_kunci";

    } elseif ($tipe_soal === 'pilihan ganda kompleks') {
        $kunci_opsi = array_map('strtolower', array_map('trim', explode(',', str_replace('|', ',', $isi_kunci))));
        $jawaban_opsi = array_map('strtolower', array_map('trim', explode(',', str_replace('|', ',', $isi_jawaban))));
        $jawaban_ditulis = implode(', ', $jawaban_opsi);
        $jumlah_kunci = count($kunci_opsi);

        $jumlah_benar = 0;
        $ada_salah = false;

        foreach ($jawaban_opsi as $opsi) {
            if (in_array($opsi, $kunci_opsi)) {
                $jumlah_benar++;
            } else {
                $ada_salah = true;
                break;
            }
        }

        if ($ada_salah) {
            $skor = 0;
            $status = "❌ Salah"; $salah++;
        } else {
            if ($jumlah_benar == $jumlah_kunci) {
                $skor = $nilai_per_soal;
                $status = "✅ Benar"; $benar++;
            } else {
                $nilai_per_opsi = $nilai_per_soal / $jumlah_kunci;
                $skor = $jumlah_benar * $nilai_per_opsi;
                $status = "⚠️ Kurang Lengkap"; $kurang_lengkap++;
            }
        }

        $detail_skor = "Skor: " . round($skor, 2) . "<br>Benar: $jumlah_benar / $jumlah_kunci";

    } else {
        if (strtolower(trim($isi_kunci)) === strtolower(trim($isi_jawaban))) {
            $skor = $nilai_per_soal;
            $status = "✅ Benar"; $benar++;
        } else {
            $skor = 0;
            $status = "❌ Salah"; $salah++;
        }
        $detail_skor = "Skor: " . round($skor, 2);
        $jawaban_ditulis = $isi_jawaban ?: '-';
    }

    $nilai_total += $skor;

    echo "<tr>";
    echo "<td>$nomer_kunci</td>";
    echo "<td>$isi_kunci</td>";
    echo "<td>$jawaban_ditulis</td>";
    echo "<td>$detail_skor</td>";
    echo "<td>$status</td>";
    echo "</tr>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";

$nilai_akhir = round($nilai_total, 2);

// Update skor akhir di header
echo "
<script>
  document.querySelector('.col-md-4.text-center h3').innerHTML = '<strong>$nilai_akhir</strong>';
  document.querySelector('.col-md-4:nth-child(2) p:nth-child(1)').innerHTML = '<strong>Benar:</strong> $benar';
  document.querySelector('.col-md-4:nth-child(2) p:nth-child(2)').innerHTML = '<strong>Salah:</strong> $salah';
  document.querySelector('.col-md-4:nth-child(2) p:nth-child(3)').innerHTML = '<strong>Kurang Lengkap:</strong> $kurang_lengkap';
</script>
";
?>
