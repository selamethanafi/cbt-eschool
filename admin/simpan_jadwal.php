<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$cacah = $_POST['cacah'];
for($i=0;$i<$cacah;$i++)
{
	$tanggal = $_POST['nilai_'.$i];
	$id =$_POST['id_soal_'.$i];
	$sql = "UPDATE `soal` SET `tanggal` = '$tanggal' where `id_soal` = '$id'";
	//echo $sql;
	if (mysqli_query($koneksi, $sql)) 
	{
	   
	} else {
	die('Gagal menyimpan pengaturan: ' . mysqli_error($koneksi));
	}
}
	header('Location: soal_hari_ini.php');
	exit;
?>
