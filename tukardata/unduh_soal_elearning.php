<?php
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
include '../koneksi/koneksi.php';
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
$tunjukkan_hasil = '0';
if((!empty($key)) and (!empty($sianis)))
{
	$ta = mysqli_query($koneksi, "SELECT * FROM `soal` where `nama_soal` like 'PHT %'");
	$cacah = mysqli_num_rows($ta);
	if($id <= $cacah )
	{
		//echo "SELECT * FROM `soal` where `nama_soal` like 'PHT %' limit $id,1";
		$ta = mysqli_query($koneksi, "SELECT * FROM `soal` where `nama_soal` like 'PHT %' limit $id,1");
		while($da = mysqli_fetch_assoc($ta))
		{
			$kode_soal = $da['kode_soal'];
			mysqli_query($koneksi, "delete FROM `butir_soal` where `kode_soal` = '$kode_soal'");
			//die("delete FROM `soal` where `kode_soal` = '$kode_soal'");
			$tmujian_id = $da['kode_soal'];
			$url = $sianis.'/cbtzya/soalperujian/'.$key.'/'.$tmujian_id;
			$json = via_curl($url);
			if($json)
			{
				//echo 'dapat jawaban dari elearning';
				if($id == 0)
				{

				}
				$cacahsoal = 0;
				$urutan = 0;
				$url_cbt = '';
		        	foreach($json as $dm)
				{
					$pesan = $dm['pesan'];
					if($pesan == 'ada')
					{
						 //echo 'No '.$urutan.'ada soal<br />';
						$urutan++;
						$soal_tipe = '';
						$nomer_soal     = $urutan;
						$soal_id = $dm['id'];
						$soal = $dm['soal'];
		 				$soal = str_replace("https://elearning.man2semarang.sch.id",$url_cbt,$soal);
		 				$soal = str_replace("https://elearning.man2kabsemarang.sch.id",$url_cbt,$soal);
						$soal = str_replace("http://elearning.man2semarang.sch.id",$url_cbt,$soal);
		 				$soal = str_replace("http://elearning.man2kabsemarang.sch.id",$url_cbt,$soal);
		 				$soal = str_replace("\n", "", $soal);
		 				$soal = str_replace("'", "", $soal);
		 				//echo $soal.'<br /><br />';
//		 				$soal = mysqli_real_escape_string($koneksi,$soal);
				               	$pertanyaan     = $soal;
				                $tipe_soal      = 'Pilihan ganda';
						$opsi = $dm['opsi_a'];
		 				$opsi = str_replace("https://elearning.man2semarang.sch.id",$url_cbt,$opsi);
		 				$opsi = str_replace("https://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2semarang.sch.id",$url_cbt,$opsi);
		 				$opsi = str_replace("http://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("\n", "", $opsi);		 				
						//$opsi = mysqli_real_escape_string($koneksi,$opsi);
						$opsi = str_replace("'", "", $opsi);
				                $pilihan_1      = $opsi;
						$opsi = $dm['opsi_b'];
						$opsi = str_replace("https://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("https://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("\n", "", $opsi);
						//$opsi = mysqli_real_escape_string($koneksi,$opsi);
						$opsi = str_replace("'", "", $opsi);
				                $pilihan_2      = $opsi;
						$opsi = $dm['opsi_c'];
						$opsi = str_replace("https://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("https://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);	
						$opsi = str_replace("\n", "", $opsi);						
						//$opsi = mysqli_real_escape_string($koneksi,$opsi);
						$opsi = str_replace("'", "", $opsi);
				               $pilihan_3      = $opsi;
						$opsi = $dm['opsi_d'];
						$opsi = str_replace("https://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("https://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);	
						$opsi = str_replace("\n", "", $opsi);						
						//$opsi = mysqli_real_escape_string($koneksi,$opsi);
						$opsi = str_replace("'", "", $opsi);
				                $pilihan_4      = $opsi;
						$opsi = $dm['opsi_e'];
						$opsi = str_replace("https://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("https://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2semarang.sch.id",$url_cbt,$opsi);
						$opsi = str_replace("http://elearning.man2kabsemarang.sch.id",$url_cbt,$opsi);	
						$opsi = str_replace("\n", "", $opsi);						
						//$opsi = mysqli_real_escape_string($koneksi,$opsi);
						$opsi = str_replace("'", "", $opsi);
				                $pilihan_5      = $opsi;
						$jawaban = strtoupper($dm['jawaban']);
						if($jawaban == 'A')
						{
					                $jawaban_benar  = 'pilihan_1';
						}
						elseif($jawaban == 'B')
						{
					                $jawaban_benar  = 'pilihan_2';
						}
						elseif($jawaban == 'C')
						{
					                $jawaban_benar  = 'pilihan_3';
						}
						elseif($jawaban == 'D')
						{
					                $jawaban_benar  = 'pilihan_4';
						}
						else
						{
					                $jawaban_benar  = 'pilihan_5';
						}
				                $status_soal    = 'Aktif';
						
						mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 0");
				                $cek = $koneksi->prepare("SELECT COUNT(*) FROM butir_soal WHERE nomer_soal = ? AND kode_soal = ?");
				                $cek->bind_param("is", $nomer_soal, $kode_soal);
				                $cek->execute();
				                $cek->bind_result($count);
				                $cek->fetch();
				                $cek->close();
				                if ($count > 0) 
						{
				                    $duplicateEntries[] = "No. $urutan (Kode: $kode_soal)";
				                    continue;
				                }
				                $stmt = $koneksi->prepare("INSERT INTO butir_soal 
                    (nomer_soal, kode_soal, pertanyaan, tipe_soal, pilihan_1, pilihan_2, pilihan_3, pilihan_4, pilihan_5, jawaban_benar, status_soal)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

					            //echo "$nomer_soal . $kode_soal . $pertanyaan . $tipe_soal . $pilihan_1 . $pilihan_2 $pilihan_3 $pilihan_4 $pilihan_5 $jawaban_benar, $status_soal";
				                $stmt->bind_param("issssssssss",$nomer_soal, $kode_soal, $pertanyaan, $tipe_soal, $pilihan_1, $pilihan_2, $pilihan_3, $pilihan_4, $pilihan_5, $jawaban_benar, $status_soal);
				                if ($stmt->execute()) {
				                    //$successCount++;
				                }
				                else
				                {
				                	echo $soal;
				                	}
				                $stmt->close();
					}
					//redirect('manager/sinkron/pesertates/'.$id);				
				}
						mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
					$id++;
					echo 'Terproses '.$id.' dari '.$cacah.' ujian';
					$lanjut = 'unduh_soal.php?id='.$id;
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
				echo 'gagal';
			}
		}
	}
}
