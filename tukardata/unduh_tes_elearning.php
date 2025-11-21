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
if(isset($_GET['kategori']))
{
	$kategori = $_GET['kategori'];
}
else
{
	$kategori = '';
}
/*
if(isset($_GET['password']))
{
	$password = $_GET['password'];
}
else
{
	$password = '';
}
if(isset($_GET['nama_pengguna']))
{
	$nama_pengguna = $_GET['nama_pengguna'];
}
else
{
	$nama_pengguna = '';
}
*/

function anti_injection($data){
	global $koneksi;
  $filter = mysqli_real_escape_string($koneksi,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_key_elearning'");
$key = '';
while($da = mysqli_fetch_assoc($ta))
{
	$key = $da['konfigurasi_isi'];
}
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_elearning'");
$sianis = '';
while($da = mysqli_fetch_assoc($ta))
{
	$sianis = $da['konfigurasi_isi'];
}
//echo $key.' '.$sianis;
if(empty($id))
{
	$id = 0;
}
if(empty($kategori))
{
	echo 'Silakan memilih <a href="unduh_tes.php?kategori=2&id=0">PAT/PAS</a>  <a href="unduh_tes.php?kategori=pht&id=0">PHT</a>  <a href="unduh_tes.php?kategori=6&id=0">Asesmen Madrasah</a>';
	die();
}
$tunjukkan_hasil = '0';
if((!empty($key)) and (!empty($sianis)))
{
	$url = $sianis.'/cbtzya/jml_ujian/'.$key.'/'.$kategori;
	$json = via_curl($url);
	$cacah = 0;
	if($json)
	{
	       	foreach($json as $dm)
		{
			$cacah = $dm['cacah'];
		}
	}
//	echo 'Cacah Tes '.$cacah;
	if($cacah > 0)
	{
		if($id <= $cacah )
		{
			$url = $sianis.'/cbtzya/ujian/'.$key.'/'.$kategori.'/'.$id;
			$json = via_curl($url);
			if($id == 0)
			{
				mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");
			}
			if($json)
			{
				//echo 'oke';
				foreach($json as $dm)
				{
					$pesan = $dm['pesan'];
					if($pesan == 'ada')
					{
						//echo 'ada';
						$nama = mysqli_real_escape_string($koneksi, $dm['nama']);
						$token = mysqli_real_escape_string($koneksi, $dm['token']);
						$kode_soal = mysqli_real_escape_string($koneksi, $dm['id']);
						$tmguru_id = mysqli_real_escape_string($koneksi, $dm['tmguru_id']);
						$nama_kelas = mysqli_real_escape_string($koneksi, $dm['nama_kelas']);
						$mapel = mysqli_real_escape_string($koneksi, $dm['mapel']);
						$nama .= ' '.$nama_kelas;
						$tc = mysqli_query($koneksi, "SELECT * FROM `siswa` WHERE `kelas` = '$nama_kelas'");
						if(mysqli_num_rows($tc) > 0)
						{
							//echo 'kelas ada';
							$td = mysqli_query($koneksi, "delete FROM `soal` WHERE `kode_soal` = '$kode_soal'");
							$tanggal = mysqli_real_escape_string($koneksi, $dm['tgl_mulai']);
							$terlambat = $dm['terlambat'];
							$waktu = $dm['waktu'];
							$sql = "INSERT INTO `soal` (`id_soal`, `kode_soal`, `nama_soal`, `mapel`, `kelas`, `waktu_ujian`, `tanggal`, `status`, `tampilan_soal`, `user_id`) VALUES ('$kode_soal', '$kode_soal', '$nama', '$mapel', '$nama_kelas', '$waktu', '$tanggal', 'Nonaktif', 'Acak', '3')";
							//echo $sql;
							mysqli_query($koneksi, $sql);
						}
						else
						{
							die('Kelas '.$nama_kelas.' tidak ditemukan');
						}
					}
				}
					$id++;
        					echo 'Terproses '.$id.' dari '.$cacah.' tes';
    					    $lanjut = 'unduh_tes.php?kategori='.$kategori.'&id='.$id;
        //					die($lanjut);
                            ?>
	        				<script>setTimeout(function () {
			    			   window.location.href= '<?php echo $lanjut;?>';
				            	},1);
        					</script>
        					<?php


			}
			else
			{
				echo $url;
				die('gagal tersambung ke elearning');
			}

		}
		else
		{
			echo 'Rampung';
			mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
		}
	}
}
