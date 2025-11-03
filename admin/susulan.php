<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

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
$simamad = $da['konfigurasi_isi'];
$url_cek = $simamad.'/cbtzya/jadwalsusulan/'.$key;
echo $url_cek;
echo '<br />';
$tanggal = date("Y-m-d");
$date=date_create($tanggal);
date_sub($date,date_interval_create_from_date_string("2 days"));
$awal = $tanggal.' 07:00:00';
echo '<br />';
$json = via_curl($url_cek);
if($json)
{
	$nomor = 1;
	foreach($json as $dt)
	{
		$pesan = $dt['pesan'];
		if($pesan == 'ada')
		{
			$nis = $dt['tmsiswa_id'];
			echo $nomor.'. '.$dt['namasiswa'].' '.$nis.'<br />';
			$tes_id = $dt['tmujian_id'];
			if(empty($tes_id))
			{
				die('tes id kosong');
			}
			mysqli_query($koneksi, "UPDATE `soal` SET `tanggal`= '$awal' WHERE `kode_soal` = '$tes_id'");
			$nomor++;
		}
	}
}
else
{
	echo 'Gagal tersambung ke simamad';
}
?>
	</div>
</div></div>

