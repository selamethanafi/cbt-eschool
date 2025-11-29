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
$nama_ujian = 'Asesmen Akhir Semester';
$thnajaran = cari_thnajaran();
$semester = cari_semester();
$tanggal = $waktu;
$tanggal_awal = substr($tanggal,11,5);
$tanggal_akhir = date('Y-m-d H:i',strtotime('+2 hours',strtotime($tanggal)));
$tanggal_akhir  = substr($tanggal_akhir,11,5);
$tanggal = substr($tanggal,0,10);
$nama_hari = tanggal_ke_hari($tanggal);
$ambil_tahun = substr($tanggal,0,4);
$ambil_bulan = substr($tanggal,5,2);
$ambil_tanggal = substr($tanggal,8,2);
$nama_bulan = angka_jadi_bulan($ambil_bulan);
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

$rekap_peserta = '';
$ta = mysqli_query($koneksi,"SELECT * FROM `siswa` where `rombel`='$ruang' order by `kelas`, `nama_siswa`"); 
$cacah_siswa = mysqli_num_rows($ta); 
$siswa_absen = '';
$jml_hadir = 0;
$belum = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<title>DAFTAR HADIR PESERTA <?php echo strtoupper($nama_ujian);?> <?php echo $waktu.' Ruang '.$ruang;?></title>
<link rel="stylesheet" href="../inc/cetak.css">
</head>
<body>

<?php
$sek_kab = "Kabupaten Semarang";

if(($cacah_siswa < 26) and ($cacah_siswa > 0))
{
?>
						<div class="page">
							<table width="100%">
								<tbody><tr>
									<td width="100"><img src="../assets/images/logo_kiri.png" height="75"></td>
									<td><center><strong class="f12">
									DAFTAR HADIR PESERTA<br>
									<?php 									$kegiatan =  $nama_ujian;
									$kegiatan =  preg_replace("/<br\/>/"," ",$kegiatan);
									$kegiatan =  preg_replace("/<br \/>/"," ",$kegiatan);
									echo strtoupper($kegiatan);?><br />TAHUN AJARAN <?php echo $thnajaran;?></strong>
									</center>
									</td>
									<td width="100"><img src="../assets/images/logo_kanan.png" height="75"></td>
									</tr>
								</tbody>
							</table>
							<table class="detail">
								<tbody>
									<tr>
										<td>NAMA MADRASAH</td><td>:</td><td><span style="width:300px;"><?php echo strtoupper($sek_nama);?></span></td>
										<td>RUANG</td><td>:</td><td><span style="width:200px;"><?php echo $ruang;?></span></td>
									</tr>
									<tr>
										<td>HARI</td><td>:</td>
										<td><span style="width:80px;"><?php echo $nama_hari;?></span>TANGGAL : <span style="width:150px;"><?php echo tanggal($tanggal);?></span></td>
										<td>PUKUL</td><td>:</td><td><span style="width:200px;">
										<?php
											echo $tanggal_awal.' - '.$tanggal_akhir;
										?></span></td>
									</tr>
								</tbody>			
							</table>
							<?php
							$nomor = 1;
							?>
							<table class="it-grid it-cetak" width="100%">
								<tbody>
									<tr style="height:30px">
										<th>No.</th>
										<th>NISM</th>
										<th>Nama Peserta<br> </th><th>Waktu Mengerjakan</th>
										<th>Nilai</th>
									</tr>
									<?php
									while($da = mysqli_fetch_assoc($ta))
									{
										$nis = $da['id_siswa'];
										$namasiswa = $da['nama_siswa'];
										$kelas = $da['kelas'];
										$tb = mysqli_query($koneksi,"SELECT * FROM `soal` where `tanggal`='$waktu' and `kelas` = '$kelas'");
										$kode_soal = '';
										$waktu_siswa_mengerjakan = '';
										$nilai = '';										
										if(mysqli_num_rows($tb) > 0)
										{
											while($db = mysqli_fetch_assoc($tb))
											{
											 	$kode_soal = $db['kode_soal'];
											 	$tc = mysqli_query($koneksi,"select * from `jawaban_siswa` where `kode_soal` = '$kode_soal' and `id_siswa` = '$nis'");						
											 	$dc = mysqli_fetch_assoc($tc);
											 	$waktu_siswa_mengerjakan = $dc['waktu_dijawab'] ?? '';
											 	$td = mysqli_query($koneksi,"select * from `nilai` where `kode_soal` = '$kode_soal' and `id_siswa` = '$nis'");						
											 	$dd = mysqli_fetch_assoc($td);
											 	$nilai = $dd['nilai'] ?? ''; 
											 	if(empty($nilai))
												{
													$belum++;
												}
											}	
										}
										if(!empty($waktu_siswa_mengerjakan))
										{
											$jml_hadir++;
										}
										
										echo '<tr><td align="center" width="15">'.$nomor.'</td>
											<td align="center" width="100">'.$nis.'</td><td>'.$namasiswa.'</td><td align="center"></td><td align="center">'.$nilai.'</td>';

										echo '</tr>';
										$nomor++;

									}
								echo '</tbody></table>';
							?>
<br />
							<table width="100%">
								<tbody>
									<tr>
										<td>
											<table style="border:1px solid black">
												<tbody><tr>
													<td>Jumlah Peserta yang Seharusnya Hadir</td>
													<td>:</td>
													<td><?php echo $cacah_siswa;?> orang</td>
													</tr>
													<tr>
														<td>Jumlah Peserta yang Tidak Hadir</td>
														<td>:</td>
														<td><?php echo $jml_tidak_hadir;?> orang</td>
													</tr>
													<tr style="border-top:1px solid black">
													<td>Jumlah Peserta Hadir</td>
													<td>:</td>
													<td><?php echo $jml_hadir;?> orang</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td align="center" width="200"></td><td align="center" width="175">Pengawas<br><br><br><br><br>(<nip><?php echo $nama_pengawas;?></nip>)<br><br>&nbsp;&nbsp;&nbsp;&nbsp;NIP<nip> </nip></td>
									</tr>
								</tbody>
							</table>
							<?php 
							if($cacah_siswa < 26)
							{?>
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
							<?php
							}?>
						</div>
			<?php

		}
		else
		{
			$ta = mysqli_query($koneksi,"SELECT * FROM `siswa` where `rombel`='$ruang' order by `kelas`, `nama_siswa` limit 0,25"); 		
?>
					<div class="page">
							<table width="100%">
								<tbody><tr>
									<td width="100"><img src="../assets/images/logo_kiri.png" height="75"></td>
									<td><center><strong class="f12">
									DAFTAR HADIR PESERTA<br>
									<?php 									$kegiatan =  $nama_ujian;
									$kegiatan =  preg_replace("/<br\/>/"," ",$kegiatan);
									$kegiatan =  preg_replace("/<br \/>/"," ",$kegiatan);
									echo strtoupper($kegiatan);?><br />TAHUN AJARAN <?php echo $thnajaran;?></strong>
									</center>
									</td>
									<td width="100"><img src="../assets/images/logo_kanan.png" height="75"></td>
									</tr>
								</tbody>
							</table>
							<table class="detail">
								<tbody>
									<tr>
										<td>NAMA MADRASAH</td><td>:</td><td><span style="width:300px;"><?php echo strtoupper($sek_nama);?></span></td>
										<td>RUANG</td><td>:</td><td><span style="width:200px;"><?php echo $ruang;?></span></td>
									</tr>
									<tr>
										<td>HARI</td><td>:</td>
										<td><span style="width:80px;"><?php echo $nama_hari;?></span>TANGGAL : <span style="width:150px;"><?php echo tanggal($tanggal);?></span></td>
										<td>PUKUL</td><td>:</td><td><span style="width:200px;">
										<?php
											echo $tanggal_awal.' - '.$tanggal_akhir;
										?></span></td>
									</tr>
								</tbody>			
							</table>
							<?php
							$nomor = 1;
							?>
							<table class="it-grid it-cetak" width="100%">
								<tbody>
									<tr style="height:30px">
										<th>No.</th>
										<th>NISM</th>
										<th>Nama Peserta<br> </th><th>Waktu Mengerjakan</th>
										<th>Nilai</th>
									</tr>
									<?php
									while($da = mysqli_fetch_assoc($ta))
									{
										$nis = $da['id_siswa'];
										$namasiswa = $da['nama_siswa'];
										$kelas = $da['kelas'];
										$tb = mysqli_query($koneksi,"SELECT * FROM `soal` where `tanggal`='$waktu' and `kelas` = '$kelas'");
										$kode_soal = '';
										$waktu_siswa_mengerjakan = '';
										$nilai = '';										
										if(mysqli_num_rows($tb) > 0)
										{
											while($db = mysqli_fetch_assoc($tb))
											{
											 	$kode_soal = $db['kode_soal'];
											 	$tc = mysqli_query($koneksi,"select * from `jawaban_siswa` where `kode_soal` = '$kode_soal' and `id_siswa` = '$nis'");						
											 	$dc = mysqli_fetch_assoc($tc);
											 	$waktu_siswa_mengerjakan = $dc['waktu_dijawab'] ?? '';
											 	$td = mysqli_query($koneksi,"select * from `nilai` where `kode_soal` = '$kode_soal' and `id_siswa` = '$nis'");						
											 	$dd = mysqli_fetch_assoc($td);
											 	$nilai = $dd['nilai'] ?? ''; 
											 	if(empty($nilai))
												{
													$belum++;
												}
											}	
										}
										if(!empty($waktu_siswa_mengerjakan))
										{
											$jml_hadir++;
										}
										echo '<tr><td align="center" width="15">'.$nomor.'</td>
											<td align="center" width="100">'.$nis.'</td><td>'.$namasiswa.'</td><td align="center">'.$waktu_siswa_mengerjakan.'</td><td align="center">'.$nilai.'</td>';

										echo '</tr>';
										$nomor++;

									}
								echo '</tbody></table>';
							?>
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
				<?php
			$ta = mysqli_query($koneksi,"SELECT * FROM `siswa` where `rombel`='$ruang' order by `kelas`, `nama_siswa` limit 25,$cacah_siswa");
?>
					<div class="page">
							<table width="100%">
								<tbody><tr>
									<td width="100"><img src="../assets/images/logo_kiri.png" height="75"></td>
									<td><center><strong class="f12">
									DAFTAR HADIR PESERTA<br>
									<?php
									$kegiatan =  $nama_ujian;
									$kegiatan =  preg_replace("/<br\/>/"," ",$kegiatan);
									$kegiatan =  preg_replace("/<br \/>/"," ",$kegiatan);
									echo strtoupper($kegiatan);?><br />TAHUN AJARAN <?php echo $thnajaran;?></strong>
									</center>
									</td>
									<td width="100"><img src="../assets/images/logo_kanan.png" height="75"></td>
									</tr>
								</tbody>
							</table>
							<table class="detail">
								<tbody>
									<tr>
										<td>NAMA MADRASAH</td><td>:</td><td><span style="width:300px;"><?php echo strtoupper($sek_nama);?></span></td>
										<td>RUANG</td><td>:</td><td><span style="width:300px;"><?= $ruang;?> </span></td>
									</tr>
									<tr>
										<td>HARI</td><td>:</td>
										<td><span style="width:80px;"><?php echo $nama_hari;?></span>TANGGAL : <span style="width:150px;"><?php echo tanggal($tanggal);?></span></td>
										<td>PUKUL</td><td>:</td><td><span style="width:200px;">
										<?php
											echo $tanggal_awal.' - '.$tanggal_akhir;
										?></span></td>
									</tr>
								</tbody>			
							</table>
							<table class="it-grid it-cetak" width="100%">
								<tbody>
									<tr style="height:30px">
										<th>No.</th>
										<th>NISM</th>
										<th>Nama Peserta<br> </th><th>Waktu Mengerjakan</th>
										<th>Nilai</th>
									</tr>
									<?php
									while($da = mysqli_fetch_assoc($ta))
									{
										$nis = $da['id_siswa'];
										$namasiswa = $da['nama_siswa'];
										$kelas = $da['kelas'];
										$tb = mysqli_query($koneksi,"SELECT * FROM `soal` where `tanggal`='$waktu' and `kelas` = '$kelas'");
										$kode_soal = '';
										$waktu_siswa_mengerjakan = '';
										$nilai = '';
										if(mysqli_num_rows($tb) > 0)
										{
											while($db = mysqli_fetch_assoc($tb))
											{
											 	$kode_soal = $db['kode_soal'];
											 	$tc = mysqli_query($koneksi,"select * from `jawaban_siswa` where `kode_soal` = '$kode_soal' and `id_siswa` = '$nis'");						
											 	$dc = mysqli_fetch_assoc($tc);
											 	$waktu_siswa_mengerjakan = $dc['waktu_dijawab'] ?? '';
$td = mysqli_query($koneksi,"select * from `nilai` where `kode_soal` = '$kode_soal' and `id_siswa` = '$nis'");						
											 	$dd = mysqli_fetch_assoc($td);
											 	$nilai = $dd['nilai'] ?? '';
												if(empty($nilai))
												{
													$belum++;
												}										
											}	
										}
										if(!empty($waktu_siswa_mengerjakan))
										{
											$jml_hadir++;
										}
										echo '<tr><td align="center" width="15">'.$nomor.'</td>
											<td align="center" width="100">'.$nis.'</td><td>'.$namasiswa.'</td><td align="center">'.$waktu_siswa_mengerjakan.'</td><td align="center">'.$nilai.'</td>';

										echo '</tr>';
										$nomor++;

									}
								echo '</tbody></table>';
								
$jml_tidak_hadir = $cacah_siswa - $jml_hadir;
$belum = $belum - $jml_tidak_hadir;
							?>
<br />
							<table width="100%">
								<tbody>
									<tr>
										<td>
											<table style="border:1px solid black">
												<tbody><tr>
													<td>Jumlah Peserta yang Seharusnya Hadir</td>
													<td>:</td>
													<td><?php echo $cacah_siswa;?> orang</td>
													</tr>
													<tr>
														<td>Jumlah Peserta yang Tidak Hadir</td>
														<td>:</td>
														<td><?php echo $jml_tidak_hadir;?> orang</td>
													</tr>
													<tr style="border-top:1px solid black">
													<td>Jumlah Peserta Hadir</td>
													<td>:</td>
													<td><?php echo $jml_hadir;?> orang</td>
													</tr>
													<tr style="border-top:1px solid black">
													<td>Peserta Belum Rampung</td>
													<td>:</td>
													<td><?php echo $belum;?> orang</td>
													</tr>
												</tbody>
											</table>
										</td>
										<td align="center" width="200"></td><td align="center" width="175">Pengawas<br><br><br><br><br>(<nip><?php echo $nama_pengawas;?></nip>)<br><br>&nbsp;&nbsp;&nbsp;&nbsp;NIP<nip> </nip></td>
									</tr>
								</tbody>
							</table>
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
			<?php
		}


?>
</body></html>
