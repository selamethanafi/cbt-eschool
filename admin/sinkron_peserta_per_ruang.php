<?php 
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
ignore_user_abort(true);
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
if(isset($_GET['id']))
{
	$id = $_GET['id'];
}
else
{
	$id = 0;
}
function via_curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $out = curl_exec($ch);
    curl_close($ch);
    return json_decode($out, true);
}
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$da = mysqli_fetch_assoc($ta);
$key = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_sianis'");
$da = mysqli_fetch_assoc($ta);
$sianis = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_ruang'");
$da = mysqli_fetch_assoc($ta);
$ruang = $da['konfigurasi_isi'];

// ----------------------
// Ambil Total Peserta
// ----------------------
$url = $sianis.'/cbtzya/jml_peserta/'.$key.'/'.$ruang;
$json = via_curl($url);
$total = 0;

if($json){
	if($id == 0)
	{
		mysqli_query($koneksi,"SET FOREIGN_KEY_CHECKS = 0");
		mysqli_query($koneksi,"truncate `siswa`");
		mysqli_query($koneksi,"SET FOREIGN_KEY_CHECKS = 1");
	}
    foreach($json as $dm){
        $total = $dm['cacah'];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Unduh Peserta Tes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php
if($id <= $total)
{

    $url = $sianis.'/cbtzya/peserta/'.$key.'/'.$id.'/'.$ruang;
    $json = via_curl($url);
    $pesan = "[Data tidak ditemukan]";

    if($json){
        foreach($json as $dm){
            $pesan = $dm['pesan']; // <-- bisa diganti $dm['nama'] jika tersedia
            $nama = $dm['nama'];
	    $nis = mysqli_real_escape_string($koneksi,$dm['nisn']);
	    $username = mysqli_real_escape_string($koneksi,$dm['username']);
	    $password = mysqli_real_escape_string($koneksi,$dm['password']);
	    $nama = mysqli_real_escape_string($koneksi,$dm['nama']);
	    $kelas = mysqli_real_escape_string($koneksi,$dm['nama_kelas']);
	    $rombel = mysqli_real_escape_string($koneksi,$dm['ruang']);
	    $agen = mysqli_real_escape_string($koneksi,$dm['agen']);
	    $versi = mysqli_real_escape_string($koneksi,$dm['versi']);
	    // Enkripsi password
	    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
	    $encrypted = openssl_encrypt($password, $method, $rahasia, 0, $iv);
	    $final = base64_encode($iv . $encrypted);
	    if(empty($password))
	    {
	    	die($nama.' password masih kosong, buat dulu');
	    }
	    if ($nama && $username && $password && $kelas && $rombel)
	    {
	    	// Cek duplikat
	    	$cek = mysqli_query($koneksi, "SELECT * FROM siswa WHERE `id_siswa` = '$nis'");
	    	if (mysqli_num_rows($cek) > 0) 
	    	{
	    	    $sql = "update `siswa` set `nama_siswa` = '$nama', `username` = '$username', `password` = '$final', `kelas` = '$kelas', `rombel` = '$rombel' where `id_siswa` = '$nis'";
	    	    $insert = mysqli_query($koneksi, $sql);	        
	    	}
	    	else 
	    	{
		        // Insert DB
		        $sql = "INSERT INTO siswa (id_siswa, nama_siswa, username, password, kelas, rombel, `nis`) VALUES ('$nis', '$nama', '$username', '$final', '$kelas', '$rombel', '$nis')";
		        $insert = mysqli_query($koneksi, $sql);
		    }
	    }
        }
    }

    // Hitung progress
    $progress = ($total > 0) ? round(($id / $total) * 100) : 0;

?>
<div class="container-fluid">
<div class="progress" style="width: 300px;">
  <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="<?= $total;?>">
    <?= $progress ?>%
  </div>
</div>
</div>
<?php
$id++;
						$lanjut = 'sinkron_peserta_per_ruang.php?id='.$id;
							?>
							<script>setTimeout(function () {
						   window.location.href= '<?php echo $lanjut;?>';
							},10);
							</script>
							<?php
}
else
{
$lanjut = 'siswa.php';
							?>
							<script>setTimeout(function () {
						   window.location.href= '<?php echo $lanjut;?>';
							},10);
							</script>
							<?php
							}
?>
</body>
</html>

