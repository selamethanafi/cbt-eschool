<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Ujian</title>
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
                                    Hasil Ujian <?php echo htmlspecialchars($nama_siswa); ?>
                                </div>
                                <div class="card-body">
                                    <div class="table-wrapper">  
                                        <table id="tabelHasil" class="table table-bordered table-striped" style="width:100%">
                                            <thead></thead>
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

<?php include '../inc/js.php'; ?>
<script>
$(document).ready(function() {
    $.getJSON('get_nilai.php', function(response) {
        let sembunyikan = response.sembunyikan_nilai == 1;

        let kolom = [
            { data: 'nama_siswa', title: 'Nama Siswa' },
            { data: 'kode_soal', title: 'Kode Soal' },
            { data: 'mapel', title: 'Mapel' },
            { data: 'tanggal_ujian', title: 'Waktu Ujian' },
            { data: 'aksi', title: 'Aksi', orderable: false }
        ];

        if (!sembunyikan) {
            kolom.splice(3, 0, { data: 'nilai', title: 'Nilai' });
        }

        $('#tabelHasil').DataTable({
            data: response.data,
            columns: kolom,
            destroy: true
        });
    });
});
</script>

<?php include '../inc/check_activity.php'; ?>
</body>
</html>
