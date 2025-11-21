<?php
if(isset($_GET['app_key']))
{
	$app_key = $_GET['app_key'];
}
else
{
	$app_key = '';
}
if(isset($_GET['jenis']))
{
	$jenis = $_GET['jenis'];
}
else
{
	$jenis = '';
}
if(isset($_GET['id']))
{
	$ke = $_GET['id'];
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
$jenis  = strtoupper(anti_injection($jenis)).'_';
$response = array();
if($app_key == $key)
{
    	$ajaran = cari_thnajaran();
    	$semester = cari_semester();
	$response = array();
	if(($jenis == 'PAS_') or ($jenis == 'PAT_') or ($jenis == 'PHT_') or ($jenis == 'UM_'))
	{
		$tb = mysqli_query($koneksi, "SELECT * FROM `soal` WHERE `tahun`= '$tahun' and `semester` = '$semester' and `kode_soal` like '$jenis%' limit $ke,1");   
		$db = mysqli_fetch_assoc($tb);
		$h['pesan'] = 'ada';
		$h['ke'] = $ke;
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
