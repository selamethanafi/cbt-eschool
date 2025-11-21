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
if(empty($kode_soal))
{
	$kode_soal = 'xxxx';
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
	$sql = "SELECT * from `soal` where kode_soal = '$kode_soal'";
	$ta = mysqli_query($koneksi,$sql);
	$da = mysqli_fetch_assoc($ta);
	if(mysqli_num_rows($ta)>0)
	{
		$h['pesan'] = 'ada';
		$h['kode_soal'] = $da['kode_soal'];
		$h['nama_soal'] = $da['nama_soal'];
		$h['mapel'] = $da['mapel'];
		$h['kelas'] = $da['kelas'];
		$h['tampilan_soal'] = $da['tampilan_soal'];
		$h['status'] = $da['status'];
		$h['tanggal'] = $da['tanggal'];
		$h['waktu_ujian'] = $da['waktu_ujian'];
		$sqlb = "SELECT * from `butir_soal` where kode_soal = '$kode_soal' and `tipe_soal` = 'Uraian'";
		$tb = mysqli_query($koneksi,$sqlb);
		$cacah_uraian = mysqli_num_rows($tb);
		$sqlb = "SELECT * from `butir_soal` where kode_soal = '$kode_soal'";
		$tb = mysqli_query($koneksi,$sqlb);
		$cacah_soal = mysqli_num_rows($tb);
		$h['cacah_uraian'] = $cacah_uraian;
		$h['cacah_pg'] = $cacah_soal - $cacah_uraian;
		array_push($response, $h);

	}
	else
	{
		$h["pesan"]="tidak ada";
		$h['kode_soal'] = '';
		$h['nama_soal'] = 
		$h['mapel'] = '';
		$h['kelas'] = '';
		$h['tampilan_soal'] = '';
		$h['status'] = '';
		$h['tanggal'] = '';
		$h['waktu_ujian'] = 0;
		$h['cacah_uraian'] = 0;;
		$h['cacah_pg'] = 0;
		array_push($response, $h);
	}
}
else
{
	$h["pesan"]="akses ilegal";
	array_push($response, $h);

}
		echo json_encode($response, JSON_PRETTY_PRINT);
?>
