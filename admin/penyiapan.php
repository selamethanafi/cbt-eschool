<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Manajemen Bank Soal</title>
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
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title mb-0">Penyiapan Bank Soal</h5>
                </div>
                <div class="card-body">
                <?php
                $ta = mysqli_query($koneksi, "SELECT * FROM `cbt_konfigurasi` WHERE `konfigurasi_kode` = 'url_bank_soal'");
		$da = mysqli_fetch_assoc($ta);
		$url_bank_soal = $da['konfigurasi_isi'];
		if(empty($url_bank_soal))
		{?>
                  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <div class="btn-group" role="group" aria-label="Button group">
                      <a href="gambar.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Kompress Folder Gambar
                      </a>
                    </div>
                  </div>
                  
		<?php
			$tb = mysqli_query($koneksi, "SELECT * FROM `gambar` order by `created_at` DESC limit 0,5");
			echo '<ol>';
			while($db = mysqli_fetch_assoc($tb))
			{
				echo '<li><a href="../backup/'.$db['filename'].'">'.$db['filename'].'</a></li>';
			}
			echo '</ol>';
		}
		else
		{?>
                   <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#mdunduh1">
  Unduh Siswa Per Ruang dari SIM
</button>
<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#mdunduh2">
  Unduh Tes dari Bank Soal
</button>
			<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#mdunduh3">
  Unduh Soal dari Bank Soal
</button>
			<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#mdunduh4">
  Unduh Gambar dari Bank Soal
</button>
		<?php
		}
		?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <div class="modal fade" id="mdunduh1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Unduh Peserta Ruang ini dari SIM</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin hendak mengunduh siswa ruang ini dari sistem informasi madrasah?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="sinkron_peserta_per_ruang.php" class="btn btn-success">Yakin</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="mdunduh2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Unduh Tes</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin hendak mengunduh tes dari server lain?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="unduh_tes.php" class="btn btn-success">Yakin</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="mdunduh3" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Unduh Soal</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin hendak mengunduh soal dari bank soal?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="unduh_soal.php" class="btn btn-success">Yakin</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="mdunduh4" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Unduh Gambar</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin hendak mengunduh gambar dari bank soal?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="unduh_gambar.php" class="btn btn-success">Yakin</a>
      </div>
    </div>
  </div>
</div>
<?php include '../inc/js.php'; ?>
</body>
</html>
