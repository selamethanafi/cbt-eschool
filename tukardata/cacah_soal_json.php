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
	$tb = mysqli_query($koneksi, "SELECT * FROM `butir_soal` WHERE `kode_soal`= '$kode_soal'");   
	$db = mysqli_fetch_assoc($tb);
	$h['pesan'] = 'ada';
	$h['cacah'] = mysqli_num_rows($tb);
	array_push($response, $h);
	echo json_encode($response, JSON_PRETTY_PRINT);
}
else
{
	$h['pesan'] = 'ketuk pintu dulu';
	$h['cacah']= '';
	array_push($response, $h);
	echo json_encode($response, JSON_PRETTY_PRINT);
}
