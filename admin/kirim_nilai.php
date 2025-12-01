<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
// Cek jika sudah login
check_login('admin');
include '../inc/dataadmin.php';
$waktu = 10;
if(isset($_GET['ke']))
{
$ke = $_GET['ke'];
}
else
{
$ke = 0;
}
if(isset($_GET['tanggal']))
{
$tanggal = $_GET['tanggal'];
}
else
{
$tanggal = date("Y-m-d");
}
$year = substr($tanggal,0,4);
$month = substr($tanggal,5,2); // February
$day = substr($tanggal,8,2);
if (checkdate($month, $day, $year)) {

} else {
die('tanggal salah');
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
//echo $sianis.'<br />';
$ta = mysqli_query($koneksi, "SELECT * FROM `nilai` WHERE `tanggal_ujian` like '$tanggal%' limit $ke,1");
//die("SELECT * FROM `nilai` WHERE `tanggal_ujian` like '$tanggal%' limit $ke,1");
if(mysqli_num_rows($ta) == 0)
{
	if($ke>0)
	{
	    $query = "UPDATE soal SET status = '', token = NULL";
	    mysqli_query($koneksi, $query);
	?>
		<script>setTimeout(function () {
		 window.location.href= 'reset_credential.php';
			},<?php echo $waktu;?>);
			</script>
		<?php	
	}
	else
	{
		 echo 'Rampung <a href="dashboard.php">Kembali</a>';
	}
 
}

while($da = mysqli_fetch_assoc($ta))
{
	$nilai = $da['nilai'];
	echo $da['nama_siswa'];
	$token = substr(str_shuffle('ABCDEFGHJKLMNPQRSTWXYZ123456789'), 0, 6);
	$kode_soal = $da['kode_soal'];
	$id_siswa = $da['id_siswa'];
	$tb = mysqli_query($koneksi, "SELECT * FROM `siswa` WHERE `id_siswa` = '$id_siswa'");
	$db = mysqli_fetch_assoc($tb);
	$nis = $db['nis'];
	$nomor_peserta = $db['username'];
$q_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal = '$kode_soal'");
$data_soal = mysqli_fetch_assoc($q_soal);
$kode_soal = $data_soal['kode_soal'];
$q_jawaban = mysqli_query($koneksi, "SELECT * FROM nilai WHERE kode_soal = '$kode_soal' AND id_siswa='$id_siswa'");
$data_jawaban = mysqli_fetch_assoc($q_jawaban);
$jawaban_siswa = $data_jawaban['jawaban_siswa'] ?? '';

$kunci = $data_soal['kunci'] ?? '';

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
$kuncifix = str_replace("\n", " ", $kuncifix);
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

echo "<h3>Kode Soal: $kode_soal</h3>";
echo "<p>Jumlah Soal: $total_soal</p>";
/*
echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>No</th><th>Kunci</th><th>Jawaban Siswa</th><th>Skor</th><th>Status</th></tr>";
*/
$jwb_siswa = '';
$analisis = '';
$kunci_jawaban = '';
$skor_per_soal = '';
for ($i = 0; $i < $total_soal; $i++) {
    list($nomer_kunci, $isi_kunci) = explode(':', $kunci_array[$i], 2);
    $isi_jawaban = $jawaban_siswa_arr[$nomer_kunci] ?? '';
			$kunci_jawabane = strtolower(trim($isi_kunci));
			if(empty($kunci_jawaban))
			{
				$kunci_jawaban .= $kunci_jawabane;
			}
			else
			{
				$kunci_jawaban .= '#'.$kunci_jawabane;
				//$jwb_siswa .= $kk;
			}

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
            $analisis .= '1';
        } elseif ($jumlah_benar == 0) {
            $status = "❌ Salah"; $salah++;
            $analisis .= '0';
        } else {
            $status = "⚠️ Kurang Lengkap"; $kurang_lengkap++;
            $analisis .= '0';
        }
        if(strlen($skor_per_soal)== 0)
	{
		$skor_per_soal .= round($skor, 2);
	}
	else
	{
		$skor_per_soal .= '#'.round($skor, 2);
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
            $analisis .= '0';
        } else {
            if ($jumlah_benar == $jumlah_kunci) {
                $skor = $nilai_per_soal;
                $status = "✅ Benar"; $benar++;
                $analisis .= '1';
            } else {
                $nilai_per_opsi = $nilai_per_soal / $jumlah_kunci;
                $skor = $jumlah_benar * $nilai_per_opsi;
                $status = "⚠️ Kurang Lengkap"; $kurang_lengkap++;
                $analisis .= '0';
            }
        }
	if(strlen($skor_per_soal)== 0)
	{
		$skor_per_soal .= round($skor, 2);
	}
	else
	{
		$skor_per_soal .= '#'.round($skor, 2);
	}

        $detail_skor = "Skor: " . round($skor, 2) . "<br>Benar: $jumlah_benar / $jumlah_kunci";

    } else if ($tipe_soal === 'pilihan ganda') 
    {
        if (strtolower(trim($isi_kunci)) === strtolower(trim($isi_jawaban))) {
            $skor = $nilai_per_soal;
            $status = "✅ Benar"; $benar++;
            $analisis .= '1';
            
            
        } else {
            $skor = 0;
            $status = "❌ Salah"; $salah++;
            $analisis .= '0';
        }
       	if(strlen($skor_per_soal)== 0)
	{
		//echo 'x'.$skor_per_soal.'x';
		$skor_per_soal .= round($skor, 2);
	}
	else
	{
		$skor_per_soal .= '#'.round($skor, 2);
	}

        $detail_skor = "Skor: " . round($skor, 2);
        $jawaban_ditulis = $isi_jawaban ?: '-';
    }
    else
    {
        $status = "?";
        $detail_skor = "Uraian";
        $jawaban_ditulis = $isi_jawaban ?: '-';
    }
$kk = strtolower(trim($isi_jawaban));
            if(empty($jwb_siswa))
            {
                
                $jwb_siswa .= $kk;
            }
            else
            {
                $jwb_siswa .= '#'.$kk;
            }
    $nilai_total += $skor;
/*

    echo "<tr>";
    echo "<td>$nomer_kunci</td>";
    echo "<td>$tipe_soal $isi_kunci</td>";
    echo "<td>$jawaban_ditulis</td>";
    echo "<td>$detail_skor</td>";
    echo "<td>$status</td>";
    echo "</tr>";
*/
}

$nilai_akhir = round($nilai_total, 2);

//echo "</table>";
echo '<p>jawaban siswa '.$jwb_siswa.'</p>';
echo "<p>Benar: $benar | Salah: $salah | Kurang Lengkap: $kurang_lengkap</p>";
	echo "<p>analisis: $analisis</p>";
	echo "<p>Rincian skor: $skor_per_soal</p>";
	//echo "<p>kunci: $kunci_jawaban</p>";
	echo "<p><strong>Nilai Akhir: $nilai_akhir%</strong></p>";
 // echo 'panjang jawaban '.strlen($jwb_siswa);
$url = $sianis.'/tukardata/terimajawabanubk';
		$params=[
			'app_key'=>$key,
			'tmujian_id' => $kode_soal,
			'nis' => $nis,
			'jawaban_pg' => $jwb_siswa,
			'nilai' => $nilai_akhir,
			'hasil_analisis' => $analisis,
			'kunci_jawaban' => $kunci_jawaban,
			'skor_per_soal' => $skor_per_soal,
			];
			//echo $url.' '.$kode_soal.' '.$jwb_siswa.' '.$key.'<br />';
if($hasil = postcurl($url,$params))
	{
	//echo $hasil;
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
					echo 'Gagal mengirim, <a href="kirim_nilai.php?tanggal='.$tanggal.'&ke='.$ke.'">Ulang</a>';
					die();
				}
			}
		}
		else
		{
			echo 'Tidak terikirim';
			die();
		}
	}
	else
	{
		echo 'Gagal terhubung ke simamad, gagal mengirim nilai <a href="kirim_nilai.php">Ulang</a>';
		die();
	}
	$url_cek_absen = $sianis.'/tukardata/ambilkehadirantes/'.$key.'/'.$token.'/'.$nomor_peserta.'/'.$tanggal;
	$hadir = '';
	$json = via_curl($url_cek_absen);
	if(!$json)
	{
		echo 'Gagal terhubung ke simamad, gagal mengambil data kehadiran, <a href="kirim_nilai.php">Ulang</a>';
		die();
	}
	else
	{
		foreach($json as $dt)
		{
			$pesan = $dt['pesan'];
			if($pesan == 'ada')
			{
				$hadir = $dt['hadir'];
			}
			
		}
	}
	$hadir = 'NN';
	if(($hadir == 'NN') or ($hadir == 'N'))
	{
		//echo 'kurang dari 85%';
		 // Enkripsi password
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
		$encrypted = openssl_encrypt($token, $method, $rahasia, 0, $iv);
		$final = base64_encode($iv . $encrypted);
		//echo $final;
		$sql = "update `siswa` set `password` = '$final' where `nis` = '$nis'";
		$insert = mysqli_query($koneksi, $sql);                                            
	}
	$ke++;
	?>
		<script>setTimeout(function () {
		 window.location.href= 'kirim_nilai.php?tanggal=<?php echo $tanggal;?>&ke=<?php echo $ke;?>';
			},<?php echo $waktu;?>);
			</script>
		<?php
}
?>



