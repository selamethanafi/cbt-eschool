<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
$id_siswa = $_GET['id_siswa'] ?? '';
$reset = $_GET['reset'] ?? '';
$kode_soal = $_GET['kode_soal'] ?? '';
$data = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id_siswa'");
if (mysqli_num_rows($data) == 0) 
{
$_SESSION['error_message'] = 'Data siswa tidak ditemukan.';
header('Location: monitor.php');
exit;
}
if($reset == 'perangkat')
{
	if (mysqli_query($koneksi, "update siswa set `session_token` = NULL WHERE id_siswa = '$id_siswa'")) 
	{
		mysqli_query($koneksi, "delete from `reset` where `id_siswa` = '$id_siswa' and `macam` = '$reset'");
		$_SESSION['success_message'] = 'Token perangkat murid berhasil dihapus.';
	} else 
	{
		$_SESSION['error_message'] = 'Gagal menghapus token perangkat murid: ' . mysqli_error($koneksi);
	}
}
if($reset == 'tes')
{
	$query = "UPDATE jawaban_siswa SET status_ujian = 'Non-Aktif' WHERE id_siswa = '$id_siswa' AND kode_soal = '$kode_soal'";
	if (mysqli_query($koneksi, $query)) 
	{
		mysqli_query($koneksi, "delete from `reset` where `id_siswa` = '$id_siswa' and `macam` = '$reset'");	
		$_SESSION['success_message'] = "Tes siswa berhasil direset.";
	} else 
	{
		$_SESSION['error_message'] = "Gagal mereset tes siswa.";
	}
}
header('Location: monitor.php');
exit;
	

