<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$user_id = $_SESSION['admin_id'];
if(isset($_GET['tanggal']))
{
	$hari_ini = $_GET['tanggal'];
}
else
{
	$hari_ini = date("Y-m-d");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian</title>
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
                        <a href="kirim_nilai.php?tanggal=<?php echo $hari_ini;?>" class="btn btn-primary mb-3"><i class="fas fa-upload"></i>
                                        Kirim ke Sistem Informasi Madrasah</a> <a href="kirim_nilai_semua.php?tanggal=<?php echo $hari_ini;?>" class="btn btn-success mb-3"><i class="fas fa-upload"></i>Kirim Nilai Semua ke Sistem Informasi Madrasah</a>
                                    <table class="table table-bordered table-responsive" id="nilaiTableData">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Siswa</th>
                        <th>Kode Soal</th>
                        <th>Total Soal</th>
                        <th>Kelas</th>
                        <th>Nilai PG|PGX|MJD|BS</th>
                        <th>Nilai Uraian</th>
                        <th>Nilai Akhir</th>
                        <th>Tanggal Ujian</th>
                        <th>Kirim</th>                        
                    </tr>
                </thead>
                <?php
                $where = " `tanggal_ujian` like '$hari_ini%'";
    $query = "SELECT n.id_nilai, n.id_siswa, s.nama_siswa, s.kelas, s.rombel, n.kode_soal, n.total_soal, 
                n.status_penilaian, n.jawaban_benar, n.jawaban_salah, n.jawaban_kurang, 
                n.nilai, n.nilai_uraian, n.tanggal_ujian
              FROM nilai n
              JOIN siswa s ON n.id_siswa = s.id_siswa
              WHERE $where
              ORDER BY n.tanggal_ujian DESC";

    $result = mysqli_query($koneksi, $query);
    		?>
                <tbody>
	                <?php
$no = 1;
        while ($row = mysqli_fetch_assoc($result)) {

            // Format Nilai
            $nilai = number_format($row['nilai'], 2);
            $nilai_akhir = number_format($row['nilai'] + $row['nilai_uraian'], 2);
            $tanggal_ujian = date('d M Y, H:i', strtotime($row['tanggal_ujian']));

            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_siswa']}</td>
                    <td>{$row['kode_soal']}</td>
                    <td>{$row['total_soal']}</td>
                    <td>{$row['kelas']}</td>
                    <td>{$nilai}</td>
                    <td>{$row['nilai_uraian']}</td>
                    <td class='nilai-col'>{$nilai_akhir}</td>
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
