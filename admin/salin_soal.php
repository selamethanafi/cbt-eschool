<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$tahun = cari_thnajaran();
$semester = cari_semester();
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
	$kode_soal = $_POST['kode_soal'];
	$dari_kode_soal = $_POST['dari_kode_soal'];	
	$sqlc = "SELECT * FROM `butir_soal` WHERE kode_soal='$kode_soal'";
	//echo $sqlc;
	$tc = mysqli_query($koneksi, $sqlc);
	$adatc = mysqli_num_rows($tc);
	$sqld = "SELECT * FROM `butir_soal` WHERE kode_soal='$dari_kode_soal'";
	$td = mysqli_query($koneksi, $sqld);
	$adatd = mysqli_num_rows($td);
//	echo $sqld;
	if(($adatc == 0) and ($adatd > 0))
	{
		$successCount = 0;
		$duplicateEntries = [];		
		$td = mysqli_query($koneksi, $sqld);
		while($dd = mysqli_fetch_assoc($td))
		{
			$nomer_soal     = $dd['nomer_soal'];
			$pertanyaan     = $dd['pertanyaan'];
			$tipe_soal      = $dd['tipe_soal'];
			$pilihan_1      = $dd['pilihan_1'];
			$pilihan_2      = $dd['pilihan_2'];
			$pilihan_3      = $dd['pilihan_3'];
			$pilihan_4      = $dd['pilihan_4'];
			$pilihan_5      = $dd['pilihan_5'];
			$jawaban_benar  = $dd['jawaban_benar'];
			$status_soal    = $dd['status_soal'];
			$cek = $koneksi->prepare("SELECT COUNT(*) FROM butir_soal WHERE nomer_soal = ? AND kode_soal = ?");
              		$cek->bind_param("is", $nomer_soal, $kode_soal);
			$cek->execute();
			$cek->bind_result($count);
			$cek->fetch();
			$cek->close();
			if ($count > 0) 
			{
			    $duplicateEntries[] = "No. $nomer_soal (Kode: $kode_soal)";
			    continue;
			}
			$stmt = $koneksi->prepare("INSERT INTO butir_soal 
    (nomer_soal, kode_soal, pertanyaan, tipe_soal, pilihan_1, pilihan_2, pilihan_3, pilihan_4, pilihan_5, jawaban_benar, status_soal)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("issssssssss",$nomer_soal, $kode_soal, $pertanyaan, $tipe_soal, $pilihan_1, $pilihan_2, $pilihan_3, $pilihan_4, $pilihan_5, $jawaban_benar, $status_soal);
			if ($stmt->execute()) 
			{
				    $successCount++;
			}
			$stmt->close();
		}
		$_SESSION['salin_result'] = [
			'successCount' => $successCount,
			'failCount' => count($duplicateEntries),
			'duplicates' => $duplicateEntries
		            ];

	        header("Location: daftar_butir_soal.php?kode_soal=".$kode_soal);
		exit();	
	}
	else
	{
		$teks = '';
		if($adatc == 0)
		{
			$teks .= 'Daftar soal tujuan masih kosong';
		}
		else
		{
			$teks .= 'Daftar soal tujuan sudah berisi soal - soal';
		}
		$_SESSION['salin_error'] = $teks." , cacah soal sumber ".$adatd;
		header('Location: daftar_butir_soal.php?kode_soal='.$kode_soal);
		exit;
	}		
}
