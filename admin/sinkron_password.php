<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$user_id = $_SESSION['admin_id'];
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

$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_ruang'");
$da = mysqli_fetch_assoc($ta);
$ruang = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
$da = mysqli_fetch_assoc($ta);
$key = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'cbt_sianis'");
$da = mysqli_fetch_assoc($ta);
$sianis = $da['konfigurasi_isi'];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinkron Password dengan SIM</title>
    <?php include '../inc/css.php'; ?>
    <style>
        td.nilai-col {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .table-wrapper {
            max-height: 70vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'navbar.php'; ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Pemadanan Password dengan SIM</h5>
                        </div>
                        <div class="card-body">
                                          <div class=" table-wrapper">
                  <table id="siswaTable" class="table table-striped nowrap">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Nomor Peserta</th>
                        <th>Password</th>
                        <th>Padan</th>
                    </tr>
                </thead>
                <?php
                $query = "SELECT * from `siswa` where `rombel` = '$ruang' order by `nama_siswa`";
		$result = mysqli_query($koneksi, $query);
    		?>
                <tbody>
	                <?php
$no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
			include '../inc/encrypt.php';
                        $encoded = $row['password'];
                        $decoded = base64_decode($encoded);
                        $iv_length = openssl_cipher_iv_length($method);
                        $iv2 = substr($decoded, 0, $iv_length);
                        $encrypted_data = substr($decoded, $iv_length);
                        $decrypted = openssl_decrypt($encrypted_data, $method, $rahasia, 0, $iv2);
                        $nis = $row['id_siswa'];
			$url = $sianis.'/cbtzya/updatepeserta/'.$key.'/'.$nis;
			$password = 'xxxxx';
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
						}
					}
				}
				else
				{
					
				}
			}
			$padan = '';
			if($password == $decrypted)
			{
				$padan =  'padan';
			}
			else
			{
				$padan = '<a href="sinkron_siswa.php?id='. $nis.'" target="_blank" class="btn btn-sm btn-info">
                                  <i class="fas fa-download"></i> Sinkron Siswa</a>';
                        } 
            // Format Nilai
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_siswa']}</td>
                    <td>{$row['username']}</td>
                    <td>{$decrypted}</td>
                    <td>{$padan}</td>
                  </tr>";
            $no++;
        }
        
        echo '</tbody></table>';
        ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../inc/js.php'; ?>
	

</body>
</html>
