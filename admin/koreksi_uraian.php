<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$id_siswa = $_POST['id_siswa'] ?? '';
$kode_soal = $_POST['kode_soal'] ?? '';

if(empty($id_siswa) || empty($kode_soal)) {
    die("Data tidak valid!");
}

// 1. Hitung total semua soal
$query_total = mysqli_query($koneksi, 
    "SELECT COUNT(*) as total_soal 
    FROM butir_soal 
    WHERE kode_soal = '$kode_soal'");
    
$total_soal = mysqli_fetch_assoc($query_total)['total_soal'] ?? 0;
$nilai_per_soal = $total_soal > 0 ? (100 / $total_soal) : 0;

// 2. Ambil jawaban siswa
$hasil = mysqli_fetch_assoc(mysqli_query($koneksi, 
    "SELECT jawaban_siswa 
    FROM nilai 
    WHERE id_siswa = '$id_siswa' 
    AND kode_soal = '$kode_soal'"));

// Parse jawaban
$jawaban = [];
if(!empty($hasil['jawaban_siswa'])) {
    preg_match_all('/\[(\d+):([^\]]+)\]/', $hasil['jawaban_siswa'], $matches);
    foreach ($matches[1] as $key => $nomer) {
        $jawaban[$nomer] = $matches[2][$key];
    }
}

// 3. Generate form
echo '<div class="table-responsive">';
echo '<table class="table table-bordered">';
echo '<tr style="background:#f8f9fa">
        <th colspan="4">
            Total Soal: '.$total_soal.' | 
            Nilai per Soal: '.number_format($nilai_per_soal, 2).'
        </th>
      </tr>
      <tr>
        <th>No</th>
        <th>Soal</th>
        <th>Jawaban Siswa</th>
        <th>Nilai (Max: '.number_format($nilai_per_soal, 2).')</th>
      </tr>';

$soal = mysqli_query($koneksi, 
    "SELECT nomer_soal, pertanyaan 
    FROM butir_soal 
    WHERE kode_soal = '$kode_soal' 
    AND tipe_soal = 'Uraian'
    ORDER BY nomer_soal");

while ($s = mysqli_fetch_assoc($soal)) {
    $nomer = $s['nomer_soal'];
    $jawaban_siswa = $jawaban[$nomer] ?? '-';
    
    echo "<tr>
            <td>{$nomer}</td>
            <td>{$s['pertanyaan']}</td>
            <td>{$jawaban_siswa}</td>
            <td>
    <input type='number' 
        min='0' 
        max='".number_format($nilai_per_soal, 2, '.', '')."'
        step='0.01' 
        name='nilai[{$nomer}]'  // Perhatikan kurung siku untuk array
        class='form-control nilai-uraian'
        value='0'
        required>
    <small class='text-muted'>Bobot: ".number_format($nilai_per_soal, 2)."</small>
</td>
          </tr>";
}

echo '</table>';
echo '</div>';
echo "<input type='hidden' name='id_siswa' value='{$id_siswa}'>";
echo "<input type='hidden' name='kode_soal' value='{$kode_soal}'>";
?>