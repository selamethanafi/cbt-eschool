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
if(isset($_GET['ke']))
{
	$ke = $_GET['ke'];
	if(empty($ke))
	{
		$ke = 0;
	}
	$ke = $ke * 1;
}
else
{
	$ke = 0;
}
include '../koneksi/koneksi.php';
include '../inc/functions.php';
function anti_injection($data){
	global $koneksi;
  $filter = mysqli_real_escape_string($koneksi,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$tahun = cari_thnajaran();
$semester = cari_semester();
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$da = mysqli_fetch_assoc($ta);
$key = $da['konfigurasi_isi'];
$response = array();
$app_key = anti_injection($app_key);
$kode_soal  = strtoupper(anti_injection($kode_soal));
$response = array();
if($app_key == $key)
{
    	$ajaran = cari_thnajaran();
    	$semester = cari_semester();
	$response = array();
	$tb = mysqli_query($koneksi, "SELECT * FROM `butir_soal` WHERE `kode_soal`= '$kode_soal'  limit $ke,1");   
	$db = mysqli_fetch_assoc($tb);
	if(mysqli_num_rows($tb) > 0)
	{
		$h['pesan'] = 'ada';
		$h['id_soal'] = $db['id_soal'];
		$h['nomer_soal'] = $db['nomer_soal'];
		$h['kode_soal'] = $db['kode_soal'];
		$h['tipe_soal'] = $db['tipe_soal'];
		$h['pertanyaan'] = $db['pertanyaan'];
		$h['pilihan_1'] = $db['pilihan_1'];
		$h['pilihan_2'] = $db['pilihan_2'];
		$h['pilihan_3'] = $db['pilihan_3'];
		$h['pilihan_4'] = $db['pilihan_4'];
		$h['pilihan_5'] = $db['pilihan_5'];
		$h['jawaban_benar'] = $db['jawaban_benar'];
		$h['status_soal'] = $db['status_soal'];
		$h['created_at'] = $db['created_at'];
		array_push($response, $h);
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	else
	{
		$h['pesan'] = 'tidak ada';
		$h['cacah']= '';
		array_push($response, $h);
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
}
else
{
	$h['pesan'] = 'ketuk pintu dulu';
	$h['cacah']= '';
	array_push($response, $h);
	echo json_encode($response, JSON_PRETTY_PRINT);
}
