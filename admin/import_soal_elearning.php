<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
include '../inc/encrypt.php';
check_login('admin');
include '../inc/dataadmin.php';
require 'autoload.php';

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


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kode_soal_elearning']) && isset($_POST['kode_soal'])) {
 
    // Ambil kode_soal dari input form
    $form_kode_soal = isset($_POST['kode_soal']) ? trim($_POST['kode_soal']) : '';
    $kode_soal_elearning =$_POST['kode_soal_elearning'];
	$key = 'RSj4dSorRAu2CUBQaOxP';
	$sianis = 'https://tim.man2semarang.sch.id';
	echo $key.' '.$sianis.' '.$form_kode_soal;
	if((!empty($key)) and (!empty($sianis)))
	{
    		$url = $sianis.'/cbtzya/soalperujian/'.$key.'/'.$kode_soal_elearning;
    		//echo $url;
    		$json = via_curl($url);
    		if($json)
		{
			mysqli_query($koneksi, "delete FROM `butir_soal` WHERE `kode_soal` = '$form_kode_soal'");
			//echo 'dapat jawaban dari elearning';
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
			                    $duplicateEntries[] = "No. $urutan (Kode: $form_kode_soal)";
			                    continue;
			                }
			                $stmt = $koneksi->prepare("INSERT INTO butir_soal (nomer_soal, kode_soal, pertanyaan, tipe_soal, pilihan_1, pilihan_2, pilihan_3, pilihan_4, pilihan_5, jawaban_benar, status_soal)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			                $stmt->bind_param("issssssssss",$nomer_soal, $form_kode_soal, $pertanyaan, $tipe_soal, $pilihan_1, $pilihan_2, $pilihan_3, $pilihan_4, $pilihan_5, $jawaban_benar, $status_soal);
			                if ($stmt->execute()) {
			                    //$successCount++;
			                }
			                else
			                {
			                	echo $soal;
		                	}
			                $stmt->close();
				}
				mysqli_query($koneksi, "SET FOREIGN_KEY_CHECKS = 1");
			} // end foreach
			header("Location: daftar_butir_soal.php?kode_soal=" . urlencode($form_kode_soal));
		}
		else
		{
			echo 'Gagal terhubung ke elearning';
		}
	}
}
else
{
 echo 'kode elearning '.$_POST['kode_soal_elearning'].' kode soal '.$_POST['kode_soal'];
}
