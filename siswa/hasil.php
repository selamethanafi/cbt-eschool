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
                                <div class="card-header bg-secondary text-white d-flex align-items-center">
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
<?php include 'chatbot.php'; ?>
<?php include '../inc/js.php'; ?>
<?php include '../inc/check_activity.php'; ?>
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
            // Tambahkan kolom nilai dengan formatter 2 digit
            kolom.splice(3, 0, { 
                data: 'nilai', 
                title: 'Nilai',
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        // Format dengan 2 digit desimal
                        return parseFloat(data).toFixed(2);
                    }
                    return data;
                },
                type: 'num-fmt' // Untuk sorting numerik yang benar
            });
        }

        $('#tabelHasil').DataTable({
            data: response.data,
            columns: kolom,
            destroy: true,
            language: {
                decimal: ",", // Untuk format desimal Indonesia
                thousands: "." // Untuk format ribuan Indonesia
            },
            columnDefs: [
                {
                    targets: 3, // Kolom nilai (index 3 setelah disisipkan)
                    className: 'dt-body-right' // Rata kanan untuk kolom angka
                }
            ]
        });
    });
});

</script>
<?php if (isset($_SESSION['error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: '<?php echo addslashes($_SESSION['error']); ?>',
        showConfirmButton: false,
        timer: 2000
    });
</script>
<?php unset($_SESSION['error']); endif; ?>

</body>
</html>
