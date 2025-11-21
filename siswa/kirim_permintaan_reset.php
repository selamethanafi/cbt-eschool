<?php
session_start();
include '../koneksi/koneksi.php';
if(isset($_GET['nopes']))
{
	$nopes= $_GET['nopes'];
}
else
{
	$nopes = '';
}
if(isset($_GET['kode']))
{
	$kode= $_GET['kode'];
}
else
{
	$kode = '';
}
if(isset($_GET['reset']))
{
	$reset= $_GET['reset'];
}
else
{
	$reset = '';
}
if((!empty($nopes)) and (!empty($kode)) and (!empty($reset)))
{
	$query = mysqli_query($koneksi, "SELECT * from siswa WHERE username = '$nopes'");
	if(mysqli_num_rows($query) == 0)
	{
		$_SESSION['warning_message'] = 'Gagal mengirim permintaan reset, data murid tidak ditemukan';
	    header('Location: gagal.php?nopes='.$nopes.'&kode='.$kode);
	    exit();
	}
	$dq = mysqli_fetch_assoc($query);
	$nama = $dq['nama_siswa'];
	$id_siswa = $dq['id_siswa'];
	$ta = mysqli_query($koneksi, "SELECT * FROM `reset` WHERE `id_siswa` = '$id_siswa' and `macam` = '$reset'");
	if(mysqli_num_rows($ta) == 0)
	{
		mysqli_query($koneksi, "insert into reset (`id_siswa`, `macam`, `nama`) values ('$id_siswa', 'perangkat', '$nama')");
	}
	$_SESSION['warning_message'] = 'Permintaan reset sudah dikirimkan';
    header('Location: login.php?nopes='.$nopes.'&kode='.$kode);
    exit();
}
	$_SESSION['warning_message'] = 'Gagal mengirim permintaan reset';
    header('Location: gagal.php?nopes='.$nopes.'&kode='.$kode);
    exit();
