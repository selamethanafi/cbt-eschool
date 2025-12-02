<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

if(isset($_GET['jam']))
{
	$jam = $_GET['jam'];
	}
else
{
	$jam = '';
}
if(isset($_GET['tgl']))
{
	$tgl= $_GET['tgl'];
	}
else
{
	$tgl = '';
}
$tanggal = $tgl;
if(empty($tgl))
{
	$tanggal = date("Y-m-d");
}
$waktu = $tanggal.' '.$jam;
if((empty($waktu)) or ($waktu == ' '))
{
	$waktu = date("Y-m-d H:i:s");
}

	$query = "
    SELECT 
        s.id_soal, s.kode_soal, s.nama_soal, s.mapel, s.kelas, s.tampilan_soal, s.status, s.tanggal, s.waktu_ujian, s.token,
        COUNT(b.id_soal) AS jumlah_butir
    FROM soal s
    LEFT JOIN butir_soal b ON s.kode_soal = b.kode_soal where s.tanggal = '$waktu'
    GROUP BY s.id_soal, s.kode_soal, s.nama_soal, s.mapel, s.kelas, s.status,  s.tanggal, s.waktu_ujian, s.token
";
$result = mysqli_query($koneksi, $query);

// Check if the query was successful
if (!$result) {
    // If there's an error with the query, display the error message
    die('Error with the query: ' . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Soal</title>
    <?php include '../inc/css.php'; ?>
    <style>
    .table-wrapper {
        overflow-x: auto;
        /* Enable horizontal scrolling */
        -webkit-overflow-scrolling: touch;
        /* Smooth scrolling for mobile */
    }

    table th,
    table td {
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
                                    <h5 class="card-title mb-0">Daftar Tes</h5>
                                </div>
                                <div class="card-body table-wrapper">
                                <?php
                                echo $tanggal;
                                if(empty($jam))
                                {
                                	echo '<p>Pilih waktu tes</p>';
                                	$ta = mysqli_query($koneksi, "SELECT DISTINCT `tanggal` FROM `soal` WHERE `tanggal` like '$tanggal%'");
                                	while ($da = mysqli_fetch_assoc($ta)) 
                                	{
                                		echo '<p><a href="soal_hari_ini.php?tgl='.$tanggal.'&jam='.substr($da['tanggal'],-8).'">'.substr($da['tanggal'],-8).'</a></p>';
                                	}
                                }
                                else
                                {?>
                                   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mdunduh">
  Aktifkan Semua Tes
</button>

                                    <table id="soalTable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Kode Soal</th>
                                                <th>Mapel</th>
                                                <th>Kls</th>
                                                <th>Jml Soal</th>
                                                <th>Durasi (menit)</th>
                                                <th>Tgl Ujian</th>
                                                <th>Tampilan</th>
                                                <th>Status</th>
                                                <th>Token</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) { ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo $row['kode_soal']; ?></td>
                                                <td><?php echo $row['mapel']; ?></td>
                                                <td><?php echo $row['kelas']; ?></td>
                                                <td><?php echo $row['jumlah_butir']; ?></td>
                                                <td><i class="fa fa-clock" aria-hidden="true"></i>
                                                    <?php echo $row['waktu_ujian']; ?></td>
                                                <td>
                                                    <?php echo $row['tanggal']; ?></td>
                                                <td><?php echo $row['tampilan_soal']; ?></td>
                                                <td><?php echo $row['status'];?>
                                                </td>
                                                <td><?php echo $row['token']; ?></td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <?php
                                    }?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
     <!-- Modal -->
<div class="modal fade" id="mdunduh" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Mengaktifkan Tes</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Yakin hendak mengaktifkan semua tes?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <a href="aktifkan_semua_tes.php?jam=<?php echo $jam;?>" class="btn btn-success">Yakin</a>
      </div>
    </div>
  </div>
</div>
    <?php include '../inc/js.php'; ?>
    
</body>

</html>
