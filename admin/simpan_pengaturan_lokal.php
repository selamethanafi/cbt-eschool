<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$cacah = $_POST['cacah'];
for($i=1;$i<$cacah;$i++)
{
	$isi = $_POST['nilai_'.$i];
	$id =$_POST['id_referensi_'.$i];
	$sql = "UPDATE `cbt_konfigurasi` SET `konfigurasi_isi` = '$isi' where `konfigurasi_id` = '$id'";
	//echo $sql;
	if (mysqli_query($koneksi, $sql)) 
	{
	    $_SESSION['success'] = 'Pengaturan berhasil disimpan!';
	} else {
	    $_SESSION['error'] = 'Gagal menyimpan pengaturan: ' . mysqli_error($koneksi);
	}
}
	header('Location: setting_lokal.php');
	exit;
?>
