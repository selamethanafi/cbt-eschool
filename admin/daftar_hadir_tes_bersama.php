<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
require_once '../assets/phpqrcode/qrlib.php';
include '../inc/dataadmin.php';
check_login('admin');

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
$logoPath = '../assets/images/kemdikbud.png';
$logoData = base64_encode(file_get_contents($logoPath));
$logoSrc = 'data:image/png;base64,' . $logoData;
$aksi = '';
$nama_pengawas = '';
$catatan = '';
if(isset($_GET['tanggal']))
{
	$tanggal_tes = $_GET['tanggal'];
	$ta =  mysqli_query($koneksi, "SELECT * FROM `pengawas` WHERE `waktu` = '$tanggal_tes'");
	if(mysqli_num_rows($ta) > 0)
	{
		$aksi = 'ubah';
		$da = mysqli_fetch_assoc($ta);
		$nama_pengawas = $da['nama_pengawas'];
		$catatan = $da['catatan'];
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir</title>
    <?php include '../inc/css.php'; ?>
</head>

<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'navbar.php'; ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card mb-4">
                                
                                    <form method="POST">
                                        <div class="row mb-3">
                                        <?php
                                        if($aksi == 'ubah')
                                        {?>
                                            <div class="col-md-6 mt-3">
                                                <label for="kode_soal" class="form-label">Waktu Pelaksanaan</label>
                                                <select name="post_tanggal_tes" id="kode_soal" class="form-control" required>
                                                    <?php
                                                        echo "<option value='{$tanggal_tes}'>{$tanggal_tes}</option>";
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            }
                                            
                                            else
                                            {?>
                                             <div class="col-md-6 mt-3">
                                                <label for="kode_soal" class="form-label">Waktu Pelaksanaan</label>
                                                <select name="post_tanggal_tes" id="kode_soal" class="form-control" required>
                                                    <option value="">Pilih waktu pelaksanaan</option>
                                                    <?php

                                                    $ta =  mysqli_query($koneksi, "SELECT DISTINCT `tanggal` FROM `soal` WHERE 1 order by `tanggal`");
                                    while ($da = mysqli_fetch_assoc($ta)) 
                                    {
                                                        echo "<option value='{$da['tanggal']}'>{$da['tanggal']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <?php
                                            }
                                            ?>
                                            
                                            <div class="col-md-12 mt-3">
                                                <label for="pengawas" class="form-label">Catatan</label>
                                                <textarea name="catatan" id="catatan" class="form-control" rows="3"
                                                    placeholder="catatan selama pelaksanaan tes"
                                                    required><?= $catatan;?></textarea>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label for="pengawas" class="form-label">Nama Pengawas</label>
                                                <input name="pengawas" id="pengawas" class="form-control" 
                                                    placeholder="nama pengawas" value="<?= $nama_pengawas;?>"
                                                    required></textarea>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <button type="submit" name="tampilkan"
                                                    class="btn btn-secondary w-100">Simpan data Berita Acara</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <?php if (isset($_POST['tampilkan'])): ?>
                            <?php
	                        $post_tanggal_tes = mysqli_real_escape_string($koneksi, $_POST['post_tanggal_tes']);
	                        $pengawas = mysqli_real_escape_string($koneksi, $_POST['pengawas']);
	                        $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);
	                        
                            	$ta = mysqli_query($koneksi,"select * from `pengawas` where `waktu` = '$post_tanggal_tes'");
                            	
                            	if(mysqli_num_rows($ta) == 0)
                            	{
	                            	mysqli_query($koneksi,"insert into `pengawas` (`waktu`, `nama_pengawas`, `catatan`) values ('$post_tanggal_tes', '$pengawas', '$catatan')");
                            	}
                            	else
                            	{
	                            	mysqli_query($koneksi,"update `pengawas` set `nama_pengawas`= '$pengawas', `catatan` ='$catatan' where `waktu` = '$post_tanggal_tes'");
                            	}
                            endif; ?>
                        </div>
                    </div>
                    <div class="row">
                    <?php
	                    $ta = mysqli_query($koneksi,"select * from `pengawas` order by `waktu` DESC");
	                    $no = 1;
	                    ?>
                    	<table class="table table-bordered table-responsive" id="nilaiTableData">
		        	<thead>
		                    <tr>
		                        <th>Nomor</th>
	                	        <th>Waktu Pelaksanaan</th>
		                        <th>Pengawas</th>
		                        <th>Catatan</th>
		                        <th>Ubah</th>
		                        <th colspan="2">Cetak</th>
	                    	</tr>
		                </thead>
		                <tbody>
		                <?php
		                 while ($da = mysqli_fetch_assoc($ta)) 
		                 {
			            echo "<tr><td>{$no}</td><td>{$da['waktu']}</td><td>{$da['nama_pengawas']}</td><td>{$da['catatan']}</td>";
			            echo '<td><a href="daftar_hadir_tes_bersama.php?tanggal='.$da['waktu'].'" class="btn btn-primary">Ubah</a></td><td><a href="berita_acara_tes_bersama_cetak.php?tanggal='.$da['waktu'].'" class="btn btn-warning" target="_blank">BA</a></td><td><a href="daftar_hadir_tes_bersama_cetak.php?tanggal='.$da['waktu'].'" class="btn btn-success" target="_blank">DH</a></td></tr>';
			            $no++;
			        }
			 echo '</tbody></table>';
			    ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../inc/js.php'; ?>
    

</body>

</html>
