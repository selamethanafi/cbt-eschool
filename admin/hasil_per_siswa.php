<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$user_id = $_SESSION['admin_id'];
if(isset($_GET['id_siswa']))
{
	$id_siswa = $_GET['id_siswa'];
}
else
{
	$id_siswa = '';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian Per Murid</title>
    <?php include '../inc/css.php'; ?>
    <style>
        td.nilai-col {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .table-wrapper {
            max-height: 70vh;
            overflow-y: auto;
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
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Daftar Nilai Ujian</h5>
                        </div>
                        <div class="card-body">
                                   <table class="table table-bordered table-responsive" id="nilaiTableData">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Kode Soal</th>
                        <th>Nama Tes</th>
                        <th>Tanggal Ujian</th>
                        <th>Kirim</th>                        
                    </tr>
                </thead>
                <?php
                $where = " `id_siswa` = '$id_siswa'";
    $query = "SELECT * from `nilai` WHERE $where ORDER BY `tanggal_ujian` DESC";
    $result = mysqli_query($koneksi, $query);
    		?>
                <tbody>
	                <?php
$no = 1;
        while ($row = mysqli_fetch_assoc($result)) {

            $tanggal_ujian = date('d M Y, H:i', strtotime($row['tanggal_ujian']));
            $kode_soal = $row['kode_soal'];
		$q_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal = '$kode_soal'");
		$data_soal = mysqli_fetch_assoc($q_soal);
		$nama_tes = $data_soal['nama_soal'];
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_siswa']}</td>
                    <td>{$row['kode_soal']}</td>
                    <td>{$nama_tes}</td>
                    <td>{$tanggal_ujian}</td>";
		echo '<td><a href="kirim_nilai_per_siswa_per_tes.php?id_siswa='.$row['id_siswa'].'&kode_soal='.$row['kode_soal'].'" target="_blank">Kirim</a></td> </tr>';
            $no++;
        }
        
        echo '</tbody></table>';
        ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../inc/js.php'; ?>
	

</body>
</html>
