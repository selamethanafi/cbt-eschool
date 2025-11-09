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

if(isset($_GET['id']))
{
	$id = $_GET['id'];
}
else
{
	$id = '';
}
if(isset($_GET['jenis']))
{
	$jenis = $_GET['jenis'];
}
else
{
	$jenis = '';
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Unduh Tes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php
if(empty($id))
{
	$id = 0;
}
if(empty($jenis))
{
	echo 'Silakan memilih <h1><a href="unduh_tes.php?jenis=pas&id=0">PAS</a> <a href="unduh_tes.php?jenis=pat&id=0">PAT</a> <a href="unduh_tes.php?jenis=pht&id=0">PHT</a>  <a href="unduh_tes.php?jenis=um&id=0">Asesmen Madrasah</a></h1>';
	die();
}
//echo $key.' '.$url_bank_soal;
if((!empty($key)) and (!empty($url_bank_soal)))
{
	$url = $url_bank_soal.'/tukardata/cacah_ujian.php?app_key='.$key.'&jenis='.$jenis;
	//echo '<a href="'.$url.'">cek</a>';
	$json = via_curl($url);
	$cacah = 0;
	if($json)
	{
	       	foreach($json as $dm)
		{
			$cacah = $dm['cacah'];
		}
	}
	else
	{
		die('tidak tersambung ke bank soal');
	}
//	echo 'Cacah Tes '.$cacah;
	if($cacah > 0)
	{
		if($id < $cacah )
		{
			$url = $url_bank_soal.'/tukardata/ujian_json.php?app_key='.$key.'&jenis='.$jenis.'&id='.$id;
			//echo $url.'<br />';
//			die();
			$json = via_curl($url);
			
			if($json)
			{
				if($id == 0)
				{
					mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");
					mysqli_query($koneksi,"truncate `soal`");
					mysqli_query($koneksi,"SET FOREIGN_KEY_CHECKS = 1");
				}
				//echo 'oke';
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
						$sqlhapus = "delete from `soal` where `id_soal` = '$id_soal'";
						mysqli_query($koneksi, $sqlhapus);
						$sql = "INSERT INTO `soal` (`id_soal`, `kode_soal`, `nama_soal`, `mapel`, `kelas`, `waktu_ujian`, `tanggal`, `status`, `tampilan_soal`, `kunci`, `token`, `user_id`, `exambrowser`, `tahun`, `semester`) VALUES ('$id_soal', '$kode_soal', '$nama_soal', '$mapel', '$kelas', '$waktu_ujian', '$tanggal', '$status', '$tampilan_soal', '$kunci', '$token', '$user_id', '$exambrowser', '$tahun', '$semester')";
							//echo $sql;
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
				    // Hitung progress
    $progress = ($cacah > 0) ? round(($id / $cacah) * 100) : 0;
?>
<div class="container-fluid">
<div class="progress" style="width: 300px;">
  <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="<?= $cacah;?>">
    <?= $progress ?>%
  </div>
</div>
</div>
<?php
					$id++;
        					echo 'Terproses '.$id.' dari '.$cacah.' tes';
    					    $lanjut = 'unduh_tes.php?jenis='.$jenis.'&id='.$id;
        //					die($lanjut);
                            ?>
	        				<script>setTimeout(function () {
			    			   window.location.href= '<?php echo $lanjut;?>';
				            	},50);
        					</script>
        					<?php


			}
			else
			{
				echo $url;
				die('gagal tersambung ke bank soal');
			}

		}
		else
		{
			//echo 'Rampung';
			mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
			header('Location: ../admin/soal.php');
		exit;
		}
	}
}
?>
</body>
</html>
