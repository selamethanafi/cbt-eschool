<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';

// Cek jika sudah login
check_login('admin');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Ujian Siswa</title>
<?php include '../inc/css.php'; ?>
</head>

<body>
    <div class="wrapper">

    <?php include 'sidebar.php'; ?>

<div class="main">
    <?php include 'navbar.php'; ?>
            <!-- /Navbar -->

            <!-- Content -->
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Monitor Ujian</h5>
                                    <small id="last-updated" class="text-muted"></small>
                                </div>
                                <div class="card-body">
                                    <div class="table-wrapper">
                                        <table id="monitor" class="table table-bordered table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Kode Soal</th>
                                                    <th>Waktu Sisa</th>
                                                    <th>Waktu Mulai</th>
                                                    <th>Status Ujian</th>
                                                    <th>Progres Ujian</th>
                                                </tr>
                                            </thead>
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
$(document).ready(function () {
    var table = $('#monitor').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: 'monitor_data.php',
            type: 'GET'
        }, 
        columns: [
            { data: 'nama_siswa', title: 'Nama Siswa' },
            { data: 'kode_soal', title: 'Kode Soal' },
            { data: 'progres', title: 'Progres Ujian' },
            { data: 'waktu_sisa', title: 'Waktu Sisa' },
            { data: 'waktu_dijawab', title: 'Waktu Dijawab' },
            { data: 'status_badge', title: 'Status Ujian' }
        ],
        columnDefs: [
            { targets: 4, orderable: false, searchable: false }
        ],
        initComplete: function () {
            // Panggil pertama kali
            updateTimestamp();

            // Ulangi setiap 1 menit
            setInterval(function () {
                table.ajax.reload(null, false); // Reload data tanpa reset halaman
                updateTimestamp();
            }, 60000); // 60 detik
        }
    });

    function updateTimestamp() {
        let now = new Date();
        let formatted = now.toLocaleTimeString();
        $('#last-updated').html(
            '<i class="fa fa-refresh fa-spin me-1" style="color:green;" aria-hidden="true"></i>' +
            'Terakhir diperbarui: ' + formatted
        );
    }
});
</script>
</body>
</html>