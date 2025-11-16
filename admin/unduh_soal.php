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
$tahun = cari_thnajaran();
$semester = cari_semester();

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

if(isset($_GET['id']))
{
	$id = $_GET['id'];
}
else
{
	$id = '';
}
if(isset($_GET['ke']))
{
	$ke = $_GET['ke'];
}
else
{
	$ke = '0';
}
if(isset($_GET['jenis']))
{
	$jenis = $_GET['jenis'];
}
else
{
	$jenis = '';
}

include '../koneksi/koneksi.php';
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
//echo $key.' '.$sianis;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Unduh Soal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php
if(empty($id))
{
	$id = 0;
}
if(empty($ke))
{
	$ke = 0;
}
if(empty($jenis))
{
	echo 'Silakan memilih <h1><a href="unduh_soal.php?jenis=pas&id=0">PAS</a> <a href="unduh_soal.php?jenis=pat&id=0">PAT</a> <a href="unduh_soal.php?jenis=pht&id=0">PHT</a>  <a href="unduh_soal.php?jenis=um&id=0">Asesmen Madrasah</a></h1>';
	die();
}
$tunjukkan_hasil = '0';
if((!empty($key)) and (!empty($url_bank_soal)))
{
	$ta = mysqli_query($koneksi, "SELECT * FROM `soal` where `tahun` = '$tahun' and `semester` = '$semester' and `kode_soal` like '$jenis%'");
	$cacah = mysqli_num_rows($ta);
	if($id < $cacah )
	{
		$ta = mysqli_query($koneksi, "SELECT * FROM `soal` where `tahun` = '$tahun' and `semester` = '$semester' and `kode_soal` like '$jenis%' limit $id,1");
		mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");
		
		while($da = mysqli_fetch_assoc($ta))
		{
			$kode_soal = $da['kode_soal'];
			//ambil cacah_soal
			$url = $url_bank_soal.'/tukardata/cacah_soal_json.php?app_key='.$key.'&kode_soal='.$kode_soal;
			//echo $url;
			$json = via_curl($url);
			$cacah_soal = 0;
			if($json)
			{
			       	foreach($json as $dm)
				{
					$cacah_soal = $dm['cacah'];
				}
			}
			else
			{
				echo 'gagal terhubung dengan bank soal '.$url;
				die();
			}
			echo 'Terproses '.$id.' tes dari '.$cacah.' tes<br />';
			echo 'Nama Tes '.$da['nama_soal'].'<br />';
			echo 'cacah_soal '.$cacah_soal.'<br />';
			echo 'soal terproses '.$ke.'<br />';
			if($ke < $cacah_soal)
			{
				if($ke == 0)
				{
				mysqli_query($koneksi, "delete FROM `butir_soal` where `kode_soal` = '$kode_soal'");
				}	
				$url2 = $url_bank_soal.'/tukardata/soal_json.php?app_key='.$key.'&kode_soal='.$kode_soal.'&ke='.$ke;
				$json2 = via_curl($url2);
				if(!$json2)
				{
					die('tidak dapat mengunduh soal '.$url2);
				}
		        	foreach($json2 as $dms)
				{
					$pesan = $dms['pesan'];
					if($pesan == 'ada')
					{
						// Ambil dari array dms
						$id_soal        = $dms['id_soal'];
						$nomer_soal     = $dms['nomer_soal'];
						$kode_soal      = $dms['kode_soal'];
						$pertanyaan     = $dms['pertanyaan'];
						$tipe_soal      = $dms['tipe_soal'];
						$pilihan_1      = $dms['pilihan_1'];
						$pilihan_2      = $dms['pilihan_2'];
						$pilihan_3      = $dms['pilihan_3'];
						$pilihan_4      = $dms['pilihan_4'];
						$pilihan_5      = $dms['pilihan_5'];
						$jawaban_benar  = $dms['jawaban_benar'];
						$status_soal    = $dms['status_soal'];
						$created_at     = $dms['created_at']; // bisa s&j default juga
						
						$sql = "INSERT INTO butir_soal 
(id_soal, nomer_soal, kode_soal, pertanyaan, tipe_soal, pilihan_1, pilihan_2, pilihan_3, pilihan_4, pilihan_5, jawaban_benar, status_soal, created_at)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
						
						$stmt = $koneksi->prepare($sql);
						$stmt->bind_param(
    "iisssssssssss",
    $id_soal,
    $nomer_soal,
    $kode_soal,
    $pertanyaan,
    $tipe_soal,
    $pilihan_1,
    $pilihan_2,
    $pilihan_3,
    $pilihan_4,
    $pilihan_5,
    $jawaban_benar,
    $status_soal,
    $created_at
);

						if ($stmt->execute()) {
						    echo "Insert sukses";
						} else {
						    echo "Error: " . $stmt->error;
						    die();
						}
						$stmt->close();
						$ke++;
						$lanjut = 'unduh_soal.php?jenis='.$jenis.'&id='.$id.'&ke='.$ke;
							?>
							<script>setTimeout(function () {
						   window.location.href= '<?php echo $lanjut;?>';
							},10);
							</script>
							<?php
					}
				}
		
			}
			else
			{
				mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
				$id++;
				$lanjut = 'unduh_soal.php?jenis='.$jenis.'&id='.$id.'&ke=0';
			?>
					<script>setTimeout(function () {
						   window.location.href= '<?php echo $lanjut;?>';
					},1000);
					</script>
					<?php
			}
		}
	}
	else
	{
	echo 'Rampung';
	?>
					<script>setTimeout(function () {
						   window.location.href= '../admin/soal.php';
					},2000);
					</script>
					<?php
	}
}
?>
</body>
</html>
