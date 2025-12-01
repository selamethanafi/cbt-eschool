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
if(isset($_GET['tanggal']))
{
	$tanggal= $_GET['tanggal'];
	}
else
{
	$tanggal = '';
}

$waktu = $tanggal.' '.$jam;
if((empty($waktu)) or ($waktu == ' '))
{
	$waktu = date("Y-m-d H:i:s");
}
	$query = "SELECT * from `soal` where `tanggal` = '$waktu'";
	$ta = mysqli_query($koneksi, $query);

	// Check if the query was successful
	if (!$ta) {
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
                                    <h5 class="card-title mb-0">Ubah Jadwal Tes</h5>
                                </div>
                                <div class="card-body table-wrapper">
                                <form action="simpan_jadwal.php" method="post"">
                                <?php
//                                echo $query;
                                $no = 0;
                                while($data = mysqli_fetch_assoc($ta))
                                        {
                                        $nomor = $no+1;?>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-12">
                                                    <label for="label_<?= $no;?>" class="form-label"><?= $nomor;?>. <?php echo $data['nama_soal'];?></label>
                                                <input type="text" class="form-control" name="nilai_<?php echo $no;?>"
                                                    id="labale_<?= $no;?>" value="<?= $data['tanggal'] ?? '';?>"
                                                    required>
                                            </div>
                                            <?php
                                            echo '<input type="hidden" name="id_soal_'.$no.'" value="'.$data['id_soal'].'">';
					 $no++;
					 echo '</div>';
					}
					$cacah_item = $no;
					echo '<input type="hidden" name="cacah" value="'.$cacah_item.'">';
                                       ?>
                                </div>

                                <div class="d-flex justify-content-start gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Jadwal
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php include '../inc/js.php'; ?>
    
</body>

</html>
