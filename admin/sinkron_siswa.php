<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
include '../inc/encrypt.php';
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
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$da = mysqli_fetch_assoc($ta);
$key = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_sianis'");
$da = mysqli_fetch_assoc($ta);
$sianis = $da['konfigurasi_isi'];
if(isset($_GET['id']))
{
    $id = $_GET['id'];
    $ta = mysqli_query($koneksi, "SELECT * FROM siswa where `id_siswa` = '$id'");
    $da = mysqli_fetch_assoc($ta);
    $nis = $da['nis'];
	$url = $sianis.'/cbtzya/updatepeserta/'.$key.'/'.$nis;
	if((!empty($key)) and (!empty($sianis)))
	{
		$json = via_curl($url);    
		if($json)
		{
		       	foreach($json as $dm)
			{
				$pesan = $dm['pesan'];
				if($pesan == 'ada')
				{
					$username = $dm['nopes'];
					$password = $dm['password'];
					$nama_siswa = $dm['nama'];
					$kelas = $dm['nama_kelas'];
					$ruang = $dm['ruang'];
				        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
				        $encrypted = openssl_encrypt($password, $method, $rahasia, 0, $iv);
				        $final = base64_encode($iv . $encrypted);
					mysqli_query($koneksi, "UPDATE `siswa` SET `nama_siswa`= '$nama_siswa',`password`='$final',`username`= '$username', `kelas`= '$kelas',`rombel`='$ruang' WHERE `id_siswa` = '$id'");
					$_SESSION['success'] = 'berhasil memperbarui data '.$nama_siswa;
					header('Location: siswa.php');
					exit;		
				}
			}
		}
		else
		{
			$_SESSION['error'] = 'gagal tersambung dengan sistem informasi madrasah';
			header('Location: siswa.php');
			exit;		
		}
			
	} 
	else
	{
	    $_SESSION['error'] = 'periksa parameter sambungan ke sistem informasi madrasah';
	header('Location: siswa.php');
	exit;
	}   
}
else
{
    $_SESSION['error'] = 'id siswa kosong';
	header('Location: siswa.php');
	exit;
}


