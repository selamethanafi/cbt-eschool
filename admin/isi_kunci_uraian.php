<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$ta = mysqli_query($koneksi, "SELECT * FROM `butir_soal` WHERE `tipe_soal` = 'Uraian' and `pilihan_1` = ''");
$ada = mysqli_num_rows($ta);
echo 'Masih ada '.$ada;
$ta = mysqli_query($koneksi, "SELECT * FROM `butir_soal` WHERE `tipe_soal` = 'Uraian' and `pilihan_1` = '' limit 0,1");
while ($da = mysqli_fetch_assoc($ta)) 
{
	$id_soal = $da['id_soal'];
	mysqli_query($koneksi, "update `butir_soal` set `pilihan_1` = 'xdcfvgbhn' where `id_soal` = '$id_soal'");
	$lanjut = 'isi_kunci_uraian.php';
	?>
	  				<script>setTimeout(function () {
			    			   window.location.href= '<?php echo $lanjut;?>';
				            	},50);
        					</script>
        					<?php


}
if($ada == 0)
{
	echo '<br />Semua soal sudah diperbarui';
	echo '<br ><a href="dashboard.php">Kembali</a>';
}
