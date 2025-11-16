<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
// Cek jika sudah login
check_login('admin');
include '../inc/dataadmin.php';
$waktu = 100;
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

function ubah($masuk)
{
	$jjj = str_replace('pilihan_','',$masuk);
	return $jjj;
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
	$jawaban_siswa = $da['jawaban_siswa'] ?? ''; 
	$kunci = $data_soal['kunci'] ?? '';
	$analisis = '';
	$kunci_jawaban = '';
	function removeCommasOutsideBrackets($str) 
	{
		$result = '';
		$in_brackets = false;
		for ($i = 0; $i < strlen($str); $i++) 
		{
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
	foreach ($jawaban_array as $item) 
	{
		if (strpos($item, ':') !== false) 
		{
			list($nomer_jawab, $isi_jawab) = explode(':', $item, 2);
			$jawaban_siswa_arr[$nomer_jawab] = $isi_jawab;
		}
	}
	echo "<h3>Kode Soal: $kode_soal</h3>";
	echo "<p>Jumlah Soal: $total_soal</p>";
	$jwb_siswa = '';
	for ($i = 0; $i < $total_soal; $i++) 
	{
		list($nomer_kunci, $isi_kunci) = explode(':', $kunci_array[$i], 2);
		$isi_jawaban = $jawaban_siswa_arr[$nomer_kunci] ?? '';
			//echo $isi_jawaban.'<br />';
			$kk = strtolower(trim($isi_jawaban));
			if(empty($jwb_siswa))
			{
				$jwb_siswa .= $kk;
			}
			else
			{
				$jwb_siswa .= '|'.$kk;
				//$jwb_siswa .= $kk;
			}
			$kunci_jawabane = strtolower(trim($isi_kunci));
			if(empty($kunci_jawaban))
			{
				$kunci_jawaban .= $kunci_jawabane;
			}
			else
			{
				$kunci_jawaban .= '|'.$kunci_jawabane;
				//$jwb_siswa .= $kk;
			}
			if (strtolower(trim($isi_kunci)) === strtolower(trim($isi_jawaban))) 
			{
				$skor = $nilai_per_soal;
				$status = "✅ Benar"; $benar++;
				$analisis .= '1';
			} 
			else 
			{
				$skor = 0;
				$status = "❌ Salah"; $salah++;
				$analisis .= '0';				
			}
			$detail_skor = "Skor: " . round($skor, 2);
			$jawaban_ditulis = $isi_jawaban ?: '-';
		

	}

	$nilai_akhir = $nilai;
	//echo '<p>'.$jwb_siswa.'</p>';
	echo "<p>Benar: $benar | Salah: $salah | Kurang Lengkap: $kurang_lengkap</p>";
	echo "<p>analisis: $analisis</p>";
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


