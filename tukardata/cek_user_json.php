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
if(isset($_GET['username']))
{
	$username = $_GET['username'];
}
else
{
	$username = '';
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
$username  = anti_injection($username);
$response = array();
if($app_key == $key)
{
	$ta = mysqli_query($koneksi,"select * from `admins` where `username` = '$username'");
	if(mysqli_num_rows($ta)>0)
	{
		$h['pesan'] = 'ada';
		array_push($response, $h);
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	else
	{
		$h["pesan"]="tidak ada";
		array_push($response, $h);
		echo json_encode($response);
	}
}
else
{
	$h["pesan"]="akses ilegal";
			array_push($response, $h);
	echo json_encode($response);
}
?>
