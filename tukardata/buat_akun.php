<?php
// dibuat oleh Selamet Hanafi
// selamet.hanafi@gmail.com
// www.sianis.web.id
?>
<?php
if(isset($_POST['app_key']))
{
	$app_key = $_POST['app_key'];
}
else
{
	$app_key = '';
}
if(isset($_POST['username']))
{
	$username = $_POST['username'];
}
else
{
	$username = '';
}
if(isset($_POST['password']))
{
	$password = $_POST['password'];
}
else
{
	$password = '';
}
if(isset($_POST['nama_pengguna']))
{
	$nama_pengguna = $_POST['nama_pengguna'];
}
else
{
	$nama_pengguna = '';
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
$nama_pengguna  = anti_injection($nama_pengguna);
$password  = anti_injection($password);
$password = password_hash($password, PASSWORD_BCRYPT);
$response = array();
if($app_key == $key)
{
	$ta = mysqli_query($koneksi,"select * from `admins` where `username` = '$username'");
	if(mysqli_num_rows($ta)>0)
	{
	   $ta = mysqli_query($koneksi,"update admins set `password` = '$password' where `username` = '$username'");
	    
		$h['pesan'] = 'password diperbarui';
		array_push($response, $h);
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	else
	{
	    $waktu = date("Y-m-d H:i:s");
	   $ta = mysqli_query($koneksi,"INSERT INTO admins (`username`, `nama_admin`, `password`, `created_at`) VALUES ('$username', '$nama_pengguna', '$password', '$waktu')");
		$h["pesan"]=" akun berhasil dibuat";
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
