<?php
// dibuat oleh Selamet Hanafi
// selamet.hanafi@gmail.com
// www.sianis.web.id
?>
<?php
if(isset($_GET['app_key']))
{
	$app_key = $_GET['app_key'];
}
else
{
	$app_key = '';
}
if(isset($_GET['kode_soal']))
{
	$kode_soal = $_GET['kode_soal'];
}
else
{
	$kode_soal = '';
}
include '../koneksi/koneksi.php';
function anti_injection($data){
	global $koneksi;
  $filter = mysqli_real_escape_string($koneksi,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$key = '';
while($da = mysqli_fetch_assoc($ta))
{
$key = $da['konfigurasi_isi'];
}
//echo $key;
$response = array();
$app_key = anti_injection($app_key);
$kode_soal  = anti_injection($kode_soal);
$response = array();
if($app_key == $key)
{
	$tb = mysqli_query($koneksi,"SELECT * FROM `soal` WHERE `kode_soal` = '$kode_soal'");
	if(mysqli_num_rows($tb)>0)
	{
		$h['pesan'] = 'ada';
		$db = mysqli_fetch_assoc($tb);
		$h['pesan'] = 'ada';
		$h['id_soal'] = $db['id_soal'];
		$h['kode_soal'] = $db['kode_soal'];
		$h['nama_soal'] = $db['nama_soal'];
		$h['mapel'] = $db['mapel'];
		$h['kelas'] = $db['kelas'];
		$h['waktu_ujian'] = $db['waktu_ujian'];
		$h['tanggal'] = $db['tanggal'];
		$h['status'] = $db['status'];
		$h['tampilan_soal'] = $db['tampilan_soal'];
		$h['kunci'] = $db['kunci'];
		$h['token'] = $db['token'];
		$h['user_id'] = $db['user_id'];
		$h['exambrowser'] = $db['exambrowser'];
		$h['tahun'] = $db['tahun'];
		$h['semester'] = $db['semester'];
	}
	else
	{
		$h['pesan'] = 'tidak ada tes';
	}
	array_push($response, $h);
	echo json_encode($response, JSON_PRETTY_PRINT);
}
else
{
	$h["pesan"]="akses ilegal";
			array_push($response, $h);
	echo json_encode($response);
}
?>
