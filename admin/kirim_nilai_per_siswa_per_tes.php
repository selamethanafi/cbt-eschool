<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

if (!isset($_GET['kode_soal']) || !isset($_GET['id_siswa'])) {
    echo "Parameter kode_soal dan id_siswa harus ada.";
    exit;
}
function postcurl($urlsms,$params) 
	{
		$ch = curl_init($urlsms);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
function via_curl($url_ard_unduh)
{
	$file = $url_ard_unduh;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $file);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xmldata = curl_exec($ch);
	curl_close($ch);
	$json = json_decode($xmldata, true);
	return $json;	
}

$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$da = mysqli_fetch_assoc($ta);
$key = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_sianis'");
$da = mysqli_fetch_assoc($ta);
$sianis = $da['konfigurasi_isi'];

$kode_soal = $_GET['kode_soal'];
$id_siswa = $_GET['id_siswa'];

// Ambil data nilai, nama siswa dan tanggal ujian dari tabel nilai
$query_nilai = mysqli_query($koneksi, "SELECT * FROM nilai WHERE kode_soal='$kode_soal' AND id_siswa='$id_siswa' LIMIT 1");
if (!$query_nilai || mysqli_num_rows($query_nilai) == 0) {
    echo "Data nilai siswa tidak ditemukan.";
    exit;
}
$row_nilai = mysqli_fetch_assoc($query_nilai);
$jawaban_siswa_raw = $row_nilai['jawaban_siswa'];
$nilai_otomatis = $row_nilai['nilai'] ?? '-';
$nilai_uraian = $row_nilai['nilai_uraian'] ?? '-';
$nilai_siswa = $nilai_otomatis+$nilai_uraian;
$nama_siswa = $row_nilai['nama_siswa'] ?? '-';
$tanggal_ujian = $row_nilai['tanggal_ujian'] ?? '-';
// PARSE DETAIL URAIAN
$detail_uraian = $row_nilai['detail_uraian'] ?? '';
preg_match_all('/\[(\d+):([\d.]+)\]/', $detail_uraian, $matches);
$skor_uraian = array_combine($matches[1], $matches[2]);

function parseJawabanSiswa($str) {
    $pattern = '/\[(\d+):([^\]]*)\]/';
    preg_match_all($pattern, $str, $matches, PREG_SET_ORDER);
    $hasil = [];
    foreach ($matches as $m) {
        $no = (int)$m[1];
        $jawab = trim($m[2]);
        $hasil[$no] = $jawab;
    }
    return $hasil;
}

$jawaban_siswa = parseJawabanSiswa($jawaban_siswa_raw);

// Get answer key and calculate scores per question
$query_kunci = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal='$kode_soal'");
$data_kunci = mysqli_fetch_assoc($query_kunci);
$kunci_jawaban = $data_kunci['kunci'] ?? '';


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

$skor_per_soal = [];
$kunci_jawaban = str_replace("\n", " ", $kunci_jawaban);
if (!empty($kunci_jawaban)) {
    $kuncifix = removeCommasOutsideBrackets($kunci_jawaban);
    preg_match_all('/\[(.*?)\]/', $kuncifix, $kunci_matches);
    $kunci_array = $kunci_matches[1];
    
    $total_soal = count($kunci_array);
    $nilai_per_soal = $total_soal > 0 ? 100 / $total_soal : 0;
    
    foreach ($kunci_array as $i => $item) {
        list($nomer_kunci, $isi_kunci) = explode(':', $item, 2);
        $nomer_kunci = (int)$nomer_kunci;
        $isi_jawaban = $jawaban_siswa[$nomer_kunci] ?? '';
        
        $q_tipe = mysqli_query($koneksi, "SELECT tipe_soal FROM butir_soal WHERE kode_soal = '$kode_soal' AND nomer_soal = '$nomer_kunci'");
        $data_tipe = mysqli_fetch_assoc($q_tipe);
        $tipe_soal = strtolower($data_tipe['tipe_soal'] ?? '');
        
        if($tipe_soal === 'uraian') {
            $skor_per_soal[$nomer_kunci] = (float)($skor_uraian[$nomer_kunci] ?? 0);
            
            continue;
        }
        
        $skor = 0;
        
        if ($tipe_soal === 'benar/salah') {
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
            
        } else if ($tipe_soal === 'menjodohkan') {
            $kunci_opsi = array_map('strtolower', array_map('trim', explode('|', $isi_kunci)));
            $jawaban_opsi = array_map('strtolower', array_map('trim', explode('|', $isi_jawaban)));
            $jumlah_kunci = count($kunci_opsi);
            //echo '<br />jumlah kunci '.$jumlah_kunci;
            $nilai_per_opsi = $nilai_per_soal / $jumlah_kunci;
            $jumlah_benar = 0;
            
            for ($j = 0; $j < $jumlah_kunci; $j++) {
            //echo '<br />'.$jawaban_opsi[$j].'<br />'.$kunci_opsi[$j];
                if (isset($jawaban_opsi[$j]) && $kunci_opsi[$j] === $jawaban_opsi[$j]) {
                    $jumlah_benar++;
                }
            }
            //echo $jumlah_benar.' '.$nilai_per_opsi;
            $skor = $jumlah_benar * $nilai_per_opsi;
            
        } else if ($tipe_soal === 'pilihan ganda kompleks') {
            $kunci_opsi = array_map('strtolower', array_map('trim', explode(',', str_replace('|', ',', $isi_kunci))));
            $jawaban_opsi = array_map('strtolower', array_map('trim', explode(',', str_replace('|', ',', $isi_jawaban))));
            
            $jumlah_kunci = count($kunci_opsi);
            $jumlah_benar = 0;
            
            foreach ($jawaban_opsi as $jawab) {
                if (!in_array($jawab, $kunci_opsi)) {
                    $skor = 0;
                    goto selesai_pilgan_kompleks;
                }
            }
            
            foreach ($jawaban_opsi as $jawab) {
                if (in_array($jawab, $kunci_opsi)) $jumlah_benar++;
            }
            
            if ($jumlah_benar === $jumlah_kunci) {
                $skor = $nilai_per_soal;
            } else {
                $skor = ($jumlah_benar / $jumlah_kunci) * $nilai_per_soal_pgk;
            }
            
            selesai_pilgan_kompleks:
            ;
            
        } else {
            // PG tunggal atau uraian
            if (strtolower(trim($isi_kunci)) === strtolower(trim($isi_jawaban))) {
                $skor = $nilai_per_soal;
            }
        }
        
        $skor_per_soal[$nomer_kunci] = $skor;
       // echo $nomer_kunci.' '.$skor_per_soal[$nomer_kunci].'<br />';
    }
}
$rincian_skor_per_soal = 0;
$jwb_siswa = '';
$analisis = '';
$query_soal = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE kode_soal='$kode_soal' ORDER BY nomer_soal ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Jawaban Siswa</title>
    <?php include '../inc/css.php'; ?>
    <style>
    /* style tambahan untuk header 2 kolom */
    .header-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 10px;
    }

    .header-left,
    .header-right {
        width: 48%;
        font-weight: bold;
        font-size: 16px;
        line-height: 1.5;
    }

    .header-right {
        border: 1px solid #aaa;
        padding: 10px;
        height: 72px;
        /* kira-kira 3 baris dengan line-height 1.5 * 16px font-size */
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        background-color: #f7f7f7;
    }

    .card img {
        height: auto;
        width: auto;
        object-fit: contain;
        max-width: 450px !important;
        max-height: 300px !important;
        display: block;
    }

    @media (max-width: 768px) {
        .card img {
            width: 100% !important;

        }
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid #aaa;
        padding: 6px;
    }

    .pembahasan {
        background-color: rgb(213, 213, 213);
        background-image: radial-gradient(rgb(255, 255, 255) 1px, transparent 1px);
        background-size: 20px 20px;
        padding: 10px;
        margin-top: 10px;
        border-radius: 5px;
        color: rgb(0, 0, 0);
        font-style: italic;
        white-space: pre-wrap;
    }

    .skor-soal {
        background-color: #e8f4f8;
        padding: 8px;
        margin-top: 5px;
        border-radius: 5px;
        font-weight: bold;
    }

    ul {
        list-style-type: none;
        padding-left: 0;
    }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="main">
            <main class="content">
                <div class="container-fluid p-0">
                    <h1>Mengirim Jawaban Siswa</h1>
                    
                    <div class="col-lg-9">
                        <div class="col-12 card-utama" id="canvas_div_pdf">
                            <!-- HEADER 2 KOLOM -->
                            <div class="row mb-4"
                                style="max-height:300px;background-color: #444; color: white; border-radius: 10px; padding: 20px;">
                                <div class="col-md-9 col-6">
                                    <p><strong>Nama Siswa:</strong> <?= htmlspecialchars($nama_siswa) ?></p>
                                    <p><strong>Kode Soal:</strong> <?= htmlspecialchars($kode_soal) ?></p>
                                    <p><strong>Tanggal Ujian:</strong> <?= htmlspecialchars($tanggal_ujian) ?></p>
                                </div>
                                <div
                                    class="col-md-3 col-6 text-center d-flex align-items-center justify-content-center">
                                    <div
                                        style="background-color: white; color: black; padding: 20px; border-radius: 15px; width: 100%; height: 100%;">
                                        <h4 class="mb-0">Nilai</h4>
                                        <h1 style="font-size: 30px;"><?= $nilai_siswa ?></h1>
                                    </div>
                                </div>
                            </div>

                            <?php while ($soal = mysqli_fetch_assoc($query_soal)): 
                    $no = (int)$soal['nomer_soal'];
                   // echo $no.'<br />';
                    $jawab = isset($jawaban_siswa[$no]) ? $jawaban_siswa[$no] : '';
                   	if(empty($jwb_siswa))
            		{
             			$jwb_siswa .= $jawab;
            		}
            		else
            		{
            			$jwb_siswa .= '#'.$jawab;
            		}
                    $tipe = $soal['tipe_soal'];
                    $opsi_huruf = ['A', 'B', 'C', 'D', 'E'];
                    /*
                            <div class="row">
                               <div class="card mb-4">
                                    <div class="card-body">
                                        <h5>No. <?= $no ?> (<?= $tipe ?>)</h5>
                                        <p><?= $soal['pertanyaan'] ?></p>
                                        <?php if (!empty($soal['gambar'])): ?>
                                        <img src="../assets/img/butir_soal/<?= $soal['gambar'] ?>" alt="Gambar Soal" />
                                        <?php endif; ?>

                                        <h6>Jawaban Siswa:</h6>
                                        <?php
                                        */
                        switch ($tipe) {
                            case 'Pilihan Ganda':
                                //echo "<ul>";
                                for ($i=1; $i<=5; $i++) {
                                    $huruf = $opsi_huruf[$i-1];
                                    $checked = ($jawab == "pilihan_$i") ? "✓" : "";
                                    //echo "<li>$huruf. " . $soal["pilihan_$i"] . " $checked</li>";
                                }
//                                echo "</ul>";
                                $benar_num = (int)str_replace("pilihan_", "", $soal['jawaban_benar']);
                                $benar_huruf = $opsi_huruf[$benar_num - 1];
//                                echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>Jawaban benar: ' . $benar_huruf . '</div>';
                                break;

                            case 'Pilihan Ganda Kompleks':
                                $jawaban_arr = array_map('trim', explode(',', $jawab));
                               // echo "<ul>";
                                for ($i=1; $i<=5; $i++) {
                                    $huruf = $opsi_huruf[$i-1];
                                    $checked = in_array("pilihan_$i", $jawaban_arr) ? "✓" : "";
                                 //   echo "<li>$huruf. " . $soal["pilihan_$i"] . " $checked</li>";
                                }
                                //echo "</ul>";
                                $kunci_arr = array_map('trim', explode(',', $soal['jawaban_benar']));
                                $huruf_benar = [];
                                foreach ($kunci_arr as $k) {
                                    $num = (int)str_replace("pilihan_", "", $k);
                                    $huruf_benar[] = $opsi_huruf[$num - 1];
                                }
                                //echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>Jawaban benar: ' . implode(', ', $huruf_benar) . '</div>';
                                break;

                            case 'Benar/Salah':
                                $pernyataan = [];
                                for ($i=1; $i<=5; $i++) {
                                    if (!empty($soal["pilihan_$i"])) {
                                        $pernyataan[] = $soal["pilihan_$i"];
                                    }
                                }
                                $jawab_arr = explode('|', $jawab);
                               // echo "<table><thead><tr><th>#</th><th>Pernyataan</th><th>Benar</th><th>Salah</th></tr></thead><tbody>";
                                foreach ($pernyataan as $i => $text) {
                                    $val = isset($jawab_arr[$i]) ? $jawab_arr[$i] : '';
                                    //echo "<tr><td>" . ($i+1) . "</td><td>" . $text . "</td><td>" . ($val == "Benar" ? "✓" : "") . "</td><td>" . ($val == "Salah" ? "✓" : "") . "</td></tr>";
                                }
                                //echo "</tbody></table>";

                               $kunci_arr = explode('|', $soal['jawaban_benar']);
                                //echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>';
                                foreach ($pernyataan as $i => $text) {
                                    $nilai = $kunci_arr[$i] ?? '-';
                                    //echo "Pernyataan " . ($i + 1) . ": " . htmlspecialchars($nilai) . "<br>";
                                }
                               // echo '</div>';
                                break;

                            case 'Menjodohkan':
                                // Tampilkan jawaban siswa dalam tabel
                                $pairs = explode('|', $jawab);
                               // echo "<table border='1' cellpadding='5' cellspacing='0'><thead><tr><th>#</th><th>Pilihan </th><th>Pasangan</th></tr></thead><tbody>";
                                foreach ($pairs as $i => $pair) {
                                    list($a, $b) = explode(':', $pair) + [null, null];
                                 //   echo "<tr><td>" . ($i + 1) . "</td><td>" . htmlspecialchars($a) . "</td><td>" . htmlspecialchars($b) . "</td></tr>";
                                }
                                //echo "</tbody></table>";

                                // Tampilkan pembahasan (kunci jawaban) juga dalam tabel
                                $kunci_pairs = explode('|', $soal['jawaban_benar']);
//                                echo '<div class="pembahasan"><strong>Pembahasan:</strong>';
  //                              echo "<table border='1' cellpadding='5' cellspacing='0' style='margin-top:10px;'>";
    //                            echo "<thead><tr><th>#</th><th>Pilihan</th><th>Pasangan</th></tr></thead><tbody>";
                                foreach ($kunci_pairs as $i => $pair) {
                                    list($a, $b) = explode(':', $pair) + [null, null];
      //                              echo "<tr><td>" . ($i + 1) . "</td><td>" . htmlspecialchars($a) . "</td><td>" . htmlspecialchars($b) . "</td></tr>";
                                }
                                //echo "</tbody></table>";
                               // echo '</div>';
                                break;


                            case 'Uraian':
                                //echo "<div class='border p-2 mb-2'>" . nl2br(htmlspecialchars($jawab)) . "</div>";
                               /// echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>' . nl2br(htmlspecialchars($soal['jawaban_benar'])) . '</div>';
                                break;

                            default:
                                echo '<div>Jawaban tidak tersedia untuk tipe soal ini.</div>';
                                break;
                        }
                        if(strlen($rincian_skor_per_soal)== 0)
            			{
                            $rincian_skor_per_soal .= number_format($skor_per_soal[$no] ?? 0, 2);
            			}
            			else
            			{
            				$rincian_skor_per_soal .= '#'.number_format($skor_per_soal[$no] ?? 0, 2);
            			}
            			if($skor_per_soal[$no] > 0 )
            			{
                            $analisis .= '1';            			    
            			}
            			else
            			{
                            $analisis .= '0'; 
            			}
            			
                        ?>
                                    
                            <?php endwhile; ?>
                            <?php
                $nilai_akhir = $nilai_siswa;
                $skor_per_soal = $rincian_skor_per_soal;
                /*
                echo 'rincian = '.$rincian_skor_per_soal;
                
                echo 'key '.$key.'<br />';
                echo 'kode soal '.$kode_soal.'<br />';
                echo 'sianis '.$sianis.'<br />';
                echo 'nis '.$id_siswa.'<br />';
                echo 'jawaban '.$jwb_siswa.'<br />';
                echo 'nilai '.$nilai_akhir.'<br />';
                echo 'hasil analisis '.$analisis.'<br />';
                echo 'kunci jawaban '.$kunci_jawaban.'<br />';
                echo 'skor_per_soal '.$skor_per_soal.'<br />';
                */
                $url = $sianis.'/tukardata/terimajawabanubk';
	$params=[
			'app_key'=>$key,
			'tmujian_id' => $kode_soal,
			'nis' => $id_siswa,
			'jawaban_pg' => $jwb_siswa,
			'nilai' => $nilai_akhir,
			'hasil_analisis' => $analisis,
			'kunci_jawaban' => $kunci_jawaban,
			'skor_per_soal' => $skor_per_soal,
			];
			$hasil = postcurl($url,$params);
//print_r($hasil);

	if($hasil)
	{
		$json = json_decode($hasil, true);
		if($json)
		{
			foreach($json as $dt)
			{
				echo 'Berhasil';
				$pesan = $dt['pesan'];
				if($pesan == 'oke')
				{
					echo ' terkirim';
				}
				else
				{
					echo $dt['keterangan'];
					die();
				}
			}
		}
		else
		{
			echo 'Tidak terkirim2';
			die();
		}
	}
	else
	{
		echo 'Gagal terhubung ke simamad, gagal mengirim nilai';
		die();
	}
                ?>
                            <p class="text-center" id="encr" style="font-size:11px;color:grey;"></p>
                            
                        </div>
                    </div>
                    
                </div>
                
            </main>
        </div>
    </div>
    <?php include '../inc/js.php'; ?>
</body>

</html>
