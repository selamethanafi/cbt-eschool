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
    foreach($json as $dm){
        $total = $dm['cacah'];
    }
}

// Agar output real-time tampil
@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', false);
while (ob_get_level()) { ob_end_flush(); }
ob_implicit_flush(true);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Progress Sinkron Data</title>
<style>
    .progress-container {
        width: 100%;
        max-width: 450px;
        background: #eee;
        border-radius: 20px;
        overflow: hidden;
        margin: 20px 0;
        height: 30px;
    }
    .progress-bar {
        height: 30px;
        width: 0%;
        background: green;
        color: #fff;
        text-align: center;
        line-height: 30px;
        transition: width 0.2s;
        font-size: 14px;
    }
    #log {
        font-family: monospace;
        margin-top: 10px;
        padding: 10px;
        width: 670px;
        background: #fafafa;
        border: 1px solid #ddd;
        height: 150px;
        overflow-y: auto;
        white-space: pre-line;
    }
</style>
</head>
<body>

<h3 id="status">Menyiapkan proses...</h3>

<div class="progress-container">
    <div class="progress-bar" id="progressBar">0%</div>
</div>

<div id="log">Menunggu proses dimulai...</div>

<?php
echo "<script>document.getElementById('status').innerHTML = 'Memproses data...'; document.getElementById('log').textContent='';</script>";
flush();

// ----------------------
// PROSES PER-PESERTA
// ----------------------
$id = 0;
echo 'Total = '.$total;
while($id <= $total){

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

    // Warna progres dinamis
    if($progress < 50){
        $color = "red";
    } elseif($progress < 80){
        $color = "gold";
    } else {
        $color = "green";
    }

    // Kirim update progres + nama peserta ke browser
    echo "
    <script>
        var bar = document.getElementById('progressBar');
        bar.style.width = '$progress%';
        bar.style.background = '$color';
        bar.textContent = '$progress%';

        var logBox = document.getElementById('log');
        logBox.textContent += 'Memproses: $nama $kelas\\n';
        logBox.scrollTop = logBox.scrollHeight;
    </script>
    ";

    flush();
    //usleep(120000); // 0.12 detik supaya progres halus (boleh dihapus)

    $id++;
}
?>

<script>
document.getElementById('status').innerHTML = '✅ Selesai Memproses Semua Data! <a href="siswa.php">Kembali</a>';
document.getElementById('log').textContent += '\n--- PROSES SELESAI ---\n';
// Auto redirect setelah 2 detik
setTimeout(function(){
    window.location.href = 'siswa.php';
}, 2000);
</script>

</body>
</html>

