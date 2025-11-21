<?php
if(isset($_GET['app_key']))
{
	$app_key = $_GET['app_key'];
}
else
{
	$app_key = '';
}
include '../koneksi/koneksi.php';
include '../inc/functions.php';
function anti_injection($data){
	global $koneksi;
  $filter = mysqli_real_escape_string($koneksi,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$da = mysqli_fetch_assoc($ta);
$key = $da['konfigurasi_isi'];
$response = array();
$app_key = anti_injection($app_key);
$response = array();
if($app_key == $key)
{
	$response = array();
	$tb = mysqli_query($koneksi, "SELECT * FROM `gambar` order by `created_at` DESC limit 0,1");
	$db = mysqli_fetch_assoc($tb);
	if(mysqli_num_rows($tb) > 0)
	{
		$h['pesan'] = 'ada';
		$h['nama'] = $db['filename'];
		$h['created_at'] = $db['created_at'];
		array_push($response, $h);
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	else
	{
		$h['pesan'] = 'tidak ada';
		$h['nama']= '';
		$h['created_at'] = '';
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
