<?php 
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
ignore_user_abort(true);
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
if(isset($_GET['id']))
{
	$id = $_GET['id'];
}
else
{
	$id = 0;
}

// ----------------------
// Ambil Total Peserta
// ----------------------
$ta = mysqli_query($koneksi, "SELECT * FROM `jawaban_siswa`");
$total = mysqli_num_rows($ta);
if($id == 0)
{
	$query = "UPDATE soal SET status = ''";
        mysqli_query($koneksi, $query);
        mysqli_query($koneksi,"truncate `siswa_belum_rampung`");
}
$kode_token = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
$status = 'Aktif';
if($id < $total)
{
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mencari siswa belum rampung</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<?php
$ta = mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS `siswa_belum_rampung` (`id_siswa` int NOT NULL, `nama_siswa` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, `kode_soal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, `nama_soal` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

	$tb = mysqli_query($koneksi, "SELECT * FROM `jawaban_siswa` order by `id_jawaban` limit $id,1");
	$db = mysqli_fetch_assoc($tb);
	$id_siswa = $db['id_siswa'];
	$nama_siswa = $db['nama_siswa'];
	$kode_soal = $db['kode_soal'];
	$tc = mysqli_query($koneksi,"select * from `nilai` where `kode_soal` = '$kode_soal' and `id_siswa` = '$id_siswa'");
	$ada_tc = mysqli_num_rows($tc);
	$td = mysqli_query($koneksi,"select * from `soal` where `kode_soal` = '$kode_soal'");
	$dd = mysqli_fetch_assoc($td);
	$nama_soal = $dd['nama_soal'];
	if($ada_tc == 0)
	{
		$query = "UPDATE soal SET status = 'Aktif', `token` = '$kode_token' where `kode_soal` = '$kode_soal'";
	        mysqli_query($koneksi, $query);
	        $query = "insert into `siswa_belum_rampung` (`id_siswa`, `nama_siswa`, `kode_soal`, `nama_soal`) values ('$id_siswa', '$nama_siswa', '$kode_soal', '.$nama_soal')";
	        mysqli_query($koneksi, $query);
	        
	}
	
    // Hitung progress
    $progress = ($total > 0) ? round(($id / $total) * 100) : 0;

?>
<div class="container-fluid">
<div class="progress" style="width: 300px;">
  <div class="progress-bar" role="progressbar" style="width: <?= $progress ?>%;" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="<?= $total;?>">
    <?= $progress ?>%
  </div>
</div>
</div>
<?php
$id++;
						$lanjut = 'siswa_belum_rampung.php?id='.$id;
							?>
							<script>setTimeout(function () {
						   window.location.href= '<?php echo $lanjut;?>';
							},10);
							</script>
							<?php
}
else
{
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Daftar Siswa Belum Rampung</title>
  <?php include '../inc/css.php'; ?>
  <style>
    .table-wrapper {
      overflow-x: auto !important;
      -webkit-overflow-scrolling: touch;
    }
    table th, table td {
    text-align: left !important;
    }
  </style>
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
                  <h5 class="card-title mb-0">Daftar Siswa Belum Rampung</h5>
                </div>
                <div class="card-body">
                  <div class=" table-wrapper">
                  <table id="siswaTable" class="table table-striped nowrap">
                  <thead>
                      <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kode Soal</th>
                        <th>Nama Tes</th>
                      </tr>
                    </thead>
                    <tbody>
		<?php
		$te = mysqli_query($koneksi, "SELECT * FROM `siswa_belum_rampung` order by `nama_siswa`");
		$no = 1;
		while ($data = mysqli_fetch_assoc($te)) {
		echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>{$data['nama_siswa']}</td>";
                        echo "<td>{$data['kode_soal']}</td>";
                        echo "<td>{$data['nama_soal']}</td>";
			echo "</tr>";
			$no++;
		}
		?>
		</tbody>
                  </table>
                    </div>       
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
  <?php include '../inc/js.php';
}
?>
</body>
</html>

