<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
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

// ----------------------
// Ambil Total Peserta
// ----------------------
$url = $sianis.'/cbtzya/jml_peserta/'.$key.'/semua';
$json = via_curl($url);
$total = 0;

if($json){
    foreach($json as $dm){
        $total = $dm['cacah'];
    }
}
else
{
	die('tidak terhubung ke sistem informasi madrasah, periksa internet');
}
$id=0;
if(isset($_GET['id']))
{
	$id = $_GET['id'];
}
?>
<?php
// Nilai progress (0 - 100)

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Progress Unduh Siswa dari Sistem Informasi Madrasah</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php

echo 'Total = '.$total;
//die('id '.$id.' dari '.$total);
$progress = $id * 100 / $total;
$progress = round($progress);
if($id < $total)
{

    $url = $sianis.'/cbtzya/peserta/'.$key.'/'.$id;
    $json = via_curl($url);
    $pesan = "[Data tidak ditemukan]";
    if($json)
    {
        foreach($json as $dm)
        {
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
	    	    $sql = "update `siswa` set `nama_siswa` = '$nama', `username` = '$username', `password` = '$final', `kelas` = '$kelas', `rombel` = '$rombel', `nis` = '$nis' where `id_siswa` = '$nis'";
	    	    $insert = mysqli_query($koneksi, $sql);	        
	    	}
	    	else 
	    	{
		        // Insert DB
		        $sql = "INSERT INTO siswa (id_siswa, nama_siswa, username, password, kelas, rombel, `nis`) VALUES ($nis, '$nama', '$username', '$final', '$kelas', '$rombel', '$nis')";
		        $insert = mysqli_query($koneksi, $sql);
		    }
	    }
        }
    }
?>
<body class="p-4">

<div class="progress" style="width: 300px;">
  <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
    <?= $progress ?>%
  </div>
</div>
<?php
				$id++;
				$lanjut = 'sinkron_peserta.php?id='.$id;
			?>
					<script>setTimeout(function () {
						   window.location.href= '<?php echo $lanjut;?>';
					},1);
					</script>
					<?php

}
else
{
	echo 'Rampung';
?>
<script>
// Auto redirect setelah 2 detik
setTimeout(function(){
    window.location.href = 'siswa.php';
}, 2000);
</script>
<?php
}
?>
</body>
</html>

