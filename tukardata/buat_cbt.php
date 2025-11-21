<?php
// dibuat oleh Selamet Hanafi
// selamet.hanafi@gmail.com
// www.sianis.web.id
?>
<?php
/*
					'app_key'=>$app_key_kenaikan,
					'nama' => $nama_cbt,
					'waktu' => $waktu,
					'jenis' => 'acak',
					'tgl_mulai' => $tgl_mulai,
					'token' => $token,
					'kodeguru' => $kodeguru,
					'tahun' => substr($dm->thnajaran,0,4),
					'semester' => $dm->semester,

*/
if(isset($_POST['app_key']))
{
	$app_key = $_POST['app_key'];
}
else
{
	$app_key = '';
}
if(isset($_POST['kodeguru']))
{
	$username = $_POST['kodeguru'];
}
else
{
	$username = '';
}
if(isset($_POST['tahun']))
{
	$tahun = $_POST['tahun'];
}
else
{
	$tahun = '';
}
if(isset($_POST['semester']))
{
	$semester = $_POST['semester'];
}
else
{
	$semester = '';
}
if(isset($_POST['token']))
{
	$kode_soal = $_POST['token'];
}
else
{
	$kode_soal = '';
}

if(isset($_POST['nama']))
{
	$nama = $_POST['nama'];
}
else
{
	$nama = '';
}
if(isset($_POST['tanggal']))
{
	$tanggal = $_POST['tanggal'];
}
else
{
	$tanggal = '';
}
if(isset($_POST['waktu']))
{
	$waktu = $_POST['waktu'];
}
else
{
	$waktu = '';
}
if(isset($_POST['mapel']))
{
	$mapel = $_POST['mapel'];
}
else
{
	$mapel = '';
}
if(isset($_POST['kelas']))
{
	$kelas = $_POST['kelas'];
}
else
{
	$kelas = '';
}
//echo 'key '.$app_key.' nama '.$nama.' waktu '.$waktu.' tanggal '.$tanggal.' token '.$kode_soal.' kode guru '.$username.' tahun '.$tahun.' semester '.$semester;

include '../koneksi/koneksi.php';
function anti_injection($data){
	global $koneksi;
  $filter = mysqli_real_escape_string($koneksi,stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$key = '';
while($da = mysqli_fetch_assoc($ta))
{
    $key = $da['konfigurasi_isi'];
}
//echo $key;
$response = array();
$app_key = anti_injection($app_key);
$username  = anti_injection($username);
$response = array();
if($app_key == $key)
{
	$ta = mysqli_query($koneksi,"select * from `admins` where `username` = '$username'");
	if(mysqli_num_rows($ta)>0)
	{
	    $da = mysqli_fetch_assoc($ta);
	    $user_id = $da['id'];
		$h['pesan'] = 'ada guru';
		if(mysqli_num_rows($ta)>0)
    	{
    	    $tb = mysqli_query($koneksi,"SELECT * FROM `soal` WHERE `kode_soal` = '$kode_soal' and `user_id` = '$user_id' and `tahun` = '$tahun' and `semester` = '$semester'");
    	    if(mysqli_num_rows($tb)>0)
        	{
        	    $h['pesan2'] = 'sudah ada tes';
        	    $h['pesan3'] = 'tidak perlu tambah tes';        	    
        	}
        	else
        	{
        	    $h['pesan2'] = 'tidak ada tes';
        	     $query = "INSERT INTO soal (kode_soal, nama_soal, mapel, kelas, waktu_ujian, tampilan_soal, tanggal, user_id, exambrowser, tahun, semester)
              VALUES ('$kode_soal', '$nama', '$mapel', '$kelas', '$waktu', 'Acak', '$tanggal', $user_id, '1', '$tahun', '$semester')";
                if (mysqli_query($koneksi, $query)) {
                $h['pesan3'] = 'Tes berhasil ditambahkan.';
                } else 
                {
                    $h['pesan3'] = 'Gagal menambahkan soal: ' . mysqli_error($koneksi);
                }
        	}
        	
    	    
    	}
		array_push($response, $h);
		echo json_encode($response, JSON_PRETTY_PRINT);
	}
	else
	{
		$h["pesan"]="tidak ada guru";
		$h["pesan2"]="tidak ada tes";		
		array_push($response, $h);
		echo json_encode($response);
	}
}
else
{
	$h["pesan"]="akses ilegal";
			array_push($response, $h);
	echo json_encode($response);
}
?>
