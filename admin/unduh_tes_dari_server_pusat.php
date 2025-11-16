<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

// dibuat oleh Selamet Hanafi
// selamet.hanafi@gmail.com
// www.sianis.web.id
?>
<?php
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

if(isset($_GET['kode_soal']))
{
	$kode_soal = $_GET['kode_soal'];
}
else
{
	$_SESSION['error'] = 'Gagal mengunduh soal, kode soal kosong';
	header('Location: soal.php');
	exit;
}
if(isset($_GET['ke']))
{
	$ke = $_GET['ke'];
}
else
{
	$ke = '';
}
function anti_injection($data){
	global $koneksi;
  $filter = mysqli_real_escape_string($koneksi,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$da = mysqli_fetch_assoc($ta);
$key = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'url_bank_soal'");
$da = mysqli_fetch_assoc($ta);
$url_bank_soal = $da['konfigurasi_isi'];
//echo $key.' '.$url_bank_soal;
//echo $key.' '.$url_bank_soal;
if((!empty($key)) and (!empty($url_bank_soal)))
{
	$url = $url_bank_soal.'/tukardata/ambil_data_cbt_json.php?app_key='.$key.'&kode_soal='.$kode_soal;
	//echo $url.'<br />';
	$json = via_curl($url);
	if($json)
	{
		foreach($json as $dm)
		{
			$pesan = $dm['pesan'];
			if($pesan == 'ada')
			{
				$id_soal= $dm['id_soal'];
				$kode_soal= $dm['kode_soal'];
				$nama_soal= $dm['nama_soal'];
				$mapel= $dm['mapel'];
				$kelas= $dm['kelas'];
				$waktu_ujian= $dm['waktu_ujian'];
				$tanggal= $dm['tanggal'];
				$status= $dm['status'];
				$tampilan_soal= $dm['tampilan_soal'];
				$kunci= $dm['kunci'];
				$token = $dm['token'];
				$user_id= $dm['user_id'];
				$exambrowser= $dm['exambrowser'];
				$tahun= $dm['tahun'];
				$semester= $dm['semester'];
				$sql = "INSERT INTO `soal` (`kode_soal`, `nama_soal`, `mapel`, `kelas`, `waktu_ujian`, `tanggal`, `status`, `tampilan_soal`, `kunci`, `token`, `user_id`, `exambrowser`, `tahun`, `semester`) VALUES ('$kode_soal', '$nama_soal', '$mapel', '$kelas', '$waktu_ujian', '$tanggal', '$status', '$tampilan_soal', '$kunci', '$token', '$user_id', '$exambrowser', '$tahun', '$semester')";
				echo $sql;
				if(mysqli_query($koneksi, $sql))
				{
					echo 'ditambahkan';
				}
				else
				{
					die('error');
				}
			}
		}
		$_SESSION['success'] = 'Berhasil mengunduh tes server pusat';
		header('Location: ../admin/soal.php');
		exit;
	}
	else
	{
		$_SESSION['error'] = 'Gagal terhubung server pusat';
		header('Location: soal.php');
		exit;
	}
}
?>

