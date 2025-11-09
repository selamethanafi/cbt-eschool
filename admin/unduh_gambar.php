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
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'url_bank_soal'");
$da = mysqli_fetch_assoc($ta);
$url_bank_soal = $da['konfigurasi_isi'];
$url = $url_bank_soal.'/tukardata/nama_file_gambar_json.php?app_key='.$key;
//echo $url;
$json = via_curl($url);
$cacah_soal = 0;
$pesan ='';
if($json)
{
      	foreach($json as $dm)
	{
		$pesan = $dm['pesan'];
		$nama_file = $dm['nama'];
	}
}
else
{
	die('tidak terhubung dengan bank soal');
}
if($pesan == 'ada')
{
// Lokasi folder gambar
$folder = __DIR__ . "/../gambar";
if (!is_dir($folder)) {
    die("Folder gambar tidak ditemukan.");
}

// URL file tar.gz yang akan diunduh
$url = $url_bank_soal."/backup/".$nama_file; // GANTI SESUAI NAMA FILE

echo 'url '.$url.'<br />';

// Nama file lokal
$localFile = $folder . "/" . basename($url);

// 1. Unduh file
if (!file_put_contents($localFile, fopen($url, 'r'))) {
    die("Gagal mengunduh file.");
}

// 2. Ekstrak file di dalam folder gambar
// -x  = extract
// -z  = gzip
// -f  = file
// -C  = target folder
$cmd = "tar -xzf " . escapeshellarg($localFile) . " -C " . escapeshellarg($folder);
exec($cmd, $output, $result);

if ($result !== 0) {
    die("Gagal mengekstrak file.");
}

// 3. (Opsional) Hapus file arsip setelah ekstrak
unlink($localFile);

echo "Restore berhasil dilakukan ke folder gambar.";
$lanjut = 'penyiapan.php';
                            ?>
	        				<script>setTimeout(function () {
			    			   window.location.href= '<?php echo $lanjut;?>';
				            	},1000);
        					</script>
        					<?php
}
else
{
	die('nama file gambar tidak ditemukan');
}
