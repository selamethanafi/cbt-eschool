<?php
session_start();
include '../koneksi/koneksi.php';

$id = intval($_POST['id']);
$id_siswa = $_SESSION['siswa_id'];

mysqli_query($koneksi, "UPDATE chat SET deleted = 1 WHERE id = '$id'");
?>
