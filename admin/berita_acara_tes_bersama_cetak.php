<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
include '../inc/dataadmin.php';
check_login('admin');

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
$waktu = $_GET['tanggal'] ?? '';
$tanggal = $waktu;
$thnajaran = cari_thnajaran();
$semester = cari_semester();
$tanggal_awal = substr($tanggal,11,5);
$tanggal_akhir = date('Y-m-d H:i',strtotime('+2 hours',strtotime($tanggal)));
$tanggal_akhir  = substr($tanggal_akhir,11,5);
$tanggal = substr($tanggal,0,10);
$nama_hari = tanggal_ke_hari($tanggal);
$ambil_tahun = substr($tanggal,0,4);
$ambil_bulan = substr($tanggal,5,2);
$ambil_tanggal = substr($tanggal,8,2);
$nama_bulan = angka_jadi_bulan($ambil_bulan);
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'nama_tes_bersama'");
$da = mysqli_fetch_assoc($ta);
$nama_ujian = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'sek_nama'");
$da = mysqli_fetch_assoc($ta);
$sek_nama = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'cbt_ruang'");
$da = mysqli_fetch_assoc($ta);
$ruang = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'nama_kamad'");
$da = mysqli_fetch_assoc($ta);
$nama_kamad = $da['konfigurasi_isi'];
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'nip_kamad'");
$da = mysqli_fetch_assoc($ta);
$nip_kamad = $da['konfigurasi_isi'];

$tanggal_tes = mysqli_real_escape_string($koneksi, $_GET['tanggal']);
$ta = mysqli_query($koneksi,"select * from `pengawas` where `waktu` = '$tanggal_tes'");
$da = mysqli_fetch_assoc($ta);
$nama_pengawas = $da['nama_pengawas'];
$catatan = $da['catatan'];
$ta = mysqli_query($koneksi,"select * from `soal` where `tanggal` = '$tanggal_tes'");
$nama_mapel = '';
$nama_kelas = '';
$nama_siswa = '';
$rekap_kode_soal = '';
$no = 1;
$rekap_peserta = '';
$kode_token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
$status = 'Aktif';
$query = "UPDATE soal SET status = '$status', token = '$kode' WHERE id_soal = '$id_soal'";
mysqli_query($koneksi, $query);
while($da = mysqli_fetch_assoc($ta))
{
 	$kode_soal = $da['kode_soal'];
 	$tb = mysqli_query($koneksi,"select * from `jawaban_siswa` where `kode_soal` = '$kode_soal' order by `nama_siswa`");
 	$ada_tb = mysqli_num_rows($tb);
 	if($ada_tb > 0)
 	{
		$rekap_kode_soal .= $da['kode_soal'];
		if(empty($nama_mapel))
		{
			$nama_mapel .= $da['mapel'];
		}
		else
		{
			$nama_mapel .= ', '.$da['mapel'];
		}
		if(empty($nama_kelas))
		{
			$nama_kelas .= $da['kelas'];
		}
		else
		{
			$nama_kelas .= ', '.$da['kelas'];
		}
	}
 }
$jml_hadir = 0;
$jml_tidak_hadir=0;
$siswa_absen = '';
$siswa_belum = '';
$tsisru = mysqli_query($koneksi,"SELECT * FROM `siswa` where `rombel`='$ruang'");
while($ds = mysqli_fetch_assoc($tsisru))
{
	$id_siswa = $ds['id_siswa'];
	$kelas = $ds['kelas'];
	$nama_siswa = $ds['nama_siswa'];
	$ta = mysqli_query($koneksi,"select * from `soal` where `tanggal` = '$tanggal_tes' and `kelas` = '$kelas'");
	if(mysqli_num_rows($ta) > 0)
	{
		$da = mysqli_fetch_assoc($ta);
		$kode_soal = $da['kode_soal'];
		$tb = mysqli_query($koneksi,"select * from `jawaban_siswa` where `kode_soal` = '$kode_soal' and `id_siswa` = '$id_siswa'");
		$ada_tb = mysqli_num_rows($tb);
	 	if($ada_tb > 0)
	 	{
		 	$jml_hadir++;
		 	$tc = mysqli_query($koneksi,"select * from `nilai` where `kode_soal` = '$kode_soal' and `id_siswa` = '$id_siswa'");
			$ada_tc = mysqli_num_rows($tc);
			if($ada_tc == 0)
			{
				$query = "UPDATE soal SET status = 'Aktif', `token` = '$kode_token' where `kode_soal` = '$kode_soal'";
			        mysqli_query($koneksi, $query);
				if(empty($siswa_absen))
				{
					$siswa_belum .= $nama_siswa;
				}
				else
				{
					$siswa_belum .= ', '.$nama_siswa;
				}
			}
		}
		else
		{
			$jml_tidak_hadir++;
			if(empty($siswa_absen))
			{
				$siswa_absen .= $nama_siswa;
			}
			else
			{
				$siswa_absen .= ', '.$nama_siswa;
			}
			
		}
	}
}
$cacah_siswa = mysqli_num_rows($tsisru); 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Tes <?php echo $waktu.' Ruang '.$ruang;?></title>
	<link rel="stylesheet" href="../inc/cetak.css">
</head>

<div class="page">
	<table width="100%">
		<tbody>
			<tr>
				<td width="100"><img src="../assets/images/logo_kiri.png" height="75"></td>
				<td><center>
					<strong class="f12">
									BERITA ACARA PELAKSANAAN<br>
									<?php
									$kegiatan =  $nama_ujian;
									$kegiatan =  preg_replace("/<br\/>/"," ",$kegiatan);
									$kegiatan =  preg_replace("/<br \/>/"," ",$kegiatan);
									echo strtoupper($nama_ujian);?><br />TAHUN AJARAN <?php echo $thnajaran;?></strong>
									</center>
								</td>
									<td width="100"><img src="../assets/images/logo_kanan.png" height="75"></td>
							</tr>
						</tbody>
					</table>
					<br>
					<table class="cetakan">
						<tbody>
							<tr>
								<td style="text-align:justify;">Pada hari ini <?php echo $nama_hari;?> tanggal <?php echo $ambil_tanggal;?> bulan <?php echo $nama_bulan;?> tahun <?php echo $ambil_tahun;?>, <?php echo $sek_nama;?>  telah menyelenggarakan <?php echo $kegiatan;?>, untuk Mata Pelajaran <?= $nama_mapel;?> dari pukul 
								<?php
								echo '<span style="width:60px;">'.$tanggal_awal.'</span>';
								?>
										sampai dengan pukul
										<?php
											echo '<span style="width:60px;">'.$tanggal_akhir.'</span>';
										?> WIB
				</td>
			</tr>
		</tbody></table>
		<table class="cetakan full">
			<tbody>
			<tr>
				<td>Madrasah</td>
				<td>:</td>
				<td><span class="full"><?php echo $sek_nama;?></span></td>
			</tr>
			<tr>
				<td>Ruang</td>
				<td>:</td>
				<td>
					<span class="full"><?php echo $ruang;?></span>
			</td></tr>
			<tr>
				<td>Kelas</td>
				<td>:</td>
				<td><span class="full"><?php echo $nama_kelas;?></span></td>
			</tr>
			<tr>
				<td>Jumlah Peserta Seharusnya</td>
				<td>:</td>
				<td><span class="full"><?php echo $cacah_siswa;?></span></td>
			</tr>
			<tr>
				<td>Jumlah Peserta Hadir (Ikut Ujian)</td>
				<td>:</td>
				<td><span class="full"><?php echo $jml_hadir;?></span></td>
			</tr>
			<tr>
				<td>Jumlah Peserta Tidak Hadir</td>
				<td>:</td>
				<td><span class="full"><?php echo $jml_tidak_hadir;?></span></td>
			</tr>
			<tr>
				<td>Peserta Belum Rampung</td>
				<td>:</td>
				<td><span class="full"><?php echo $siswa_belum;?></span></td>
			</tr>
			<tr>
				<td>Peserta Tidak Hadir</td>
				<td>:</td>
				<td><span class="full"><?php echo $siswa_absen;?></span></td>
			</tr>
		</tbody></table><br />

		<table style="border:solid 1px black" class="cetakan"  width="100%">
		<tbody><tr>
			<td style="border-bottom:1px solid black"><i><b>Catatan:</b></i></td>
		</tr>
		<tr>
			<td><br />
			<?php echo $catatan;?><br /><br />
			</td>
		</tr>
		</tbody></table><br>
		<br>
		<table class="cetakan">
			<tbody><tr height="80px">
				<td colspan="4">Yang membuat berita acara :</td>
			</tr>
			<tr style="font-weight:bold" align="center">
				<td colspan="2" width="200px"></td>
				<td width="200px"></td>
				<td width="200px">TTD</td>
			</tr>
			<tr>
				<td rowspan="2" valign="top">1.</td>
				<td>Pengawas I</td>
				<td valign="bottom"><span style="width:170px"><?php echo $nama_pengawas;?></span></td>
				<td rowspan="2" align="center" valign="bottom">1.<span style="width:170px;float:right">&nbsp;</span></td>
			</tr>
			<tr><td>NIP</td><td valign="bottom"><span style="width:170px"></span></td></tr>
			<tr>
				<td rowspan="2" valign="top">2.</td>
				<td>Pengawas II</td>
				<td valign="bottom"><span style="width:170px"><?php ;?></span></td>
				<td rowspan="2" align="center" valign="bottom">2.<span style="width:170px;float:right">&nbsp;</span></td>
			</tr>
			<tr><td>NIP</td><td valign="bottom"><span style="width:170px"></span></td></tr>

			<tr>
				<td rowspan="2" valign="top">3.</td>
				<td>Kepala <?php echo $sek_nama;?></td>
				<td valign="bottom"><span style="width:170px"><?php echo $nama_kamad;?></span></td>
				<td rowspan="2" align="center" valign="bottom">3.<span style="width:170px;float:right">&nbsp;</span></td>
			</tr>
			<tr><td>NIP</td><td valign="bottom"><span style="width:170px"><?php echo $nip_kamad;?></span></td></tr>
		</tbody></table>
		<br>
		<br>
		<div class="footer">
								<table height="30" width="100%">
									<tbody><tr>
										<td style="border:1px solid black" width="25px"></td>
										<td width="5px">&nbsp;</td>
				<td style="border:1px solid black;font-weight:bold;font-size:14px;text-align:center;"><?php echo $sek_nama;?></td>
						<td width="5px">&nbsp;</td>
										<td style="border:1px solid black" width="25px"></td>
										</tr>
									</tbody>
								</table>
							</div>		
		</div>
</body></html>
