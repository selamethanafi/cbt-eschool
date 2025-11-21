<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$semester = cari_semester();
$ajaran = cari_thnajaran();
if($id_saya == '1')
{
	$query = "
    SELECT 
        s.id_soal, s.kode_soal, s.nama_soal, s.mapel, s.kelas, s.tampilan_soal, s.status, s.tanggal, s.waktu_ujian, s.token,
        COUNT(b.id_soal) AS jumlah_butir
    FROM soal s
    LEFT JOIN butir_soal b ON s.kode_soal = b.kode_soal where tahun = '$ajaran' and semester = '$semester'
    GROUP BY s.id_soal, s.kode_soal, s.nama_soal, s.mapel, s.kelas, s.status,  s.tanggal, s.waktu_ujian, s.token
";
}
else
{
	$query = "
    SELECT 
        s.id_soal, s.kode_soal, s.nama_soal, s.mapel, s.kelas, s.tampilan_soal, s.status, s.tanggal, s.waktu_ujian, s.token,
        COUNT(b.id_soal) AS jumlah_butir
    FROM soal s
    LEFT JOIN butir_soal b ON s.kode_soal = b.kode_soal where user_id = '$id_saya' and tahun = '$ajaran' and semester = '$semester'
    GROUP BY s.id_soal, s.kode_soal, s.nama_soal, s.mapel, s.kelas, s.status,  s.tanggal, s.waktu_ujian, s.token
";
}
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
                                    <h5 class="card-title mb-0">Daftar Soal</h5>
                                </div>
                                <div class="card-body table-wrapper">
                                    <a href="tambah_soal.php" class="btn btn-primary"><i class="fas fa-plus"></i>
                                        Tambah Soal Baru</a>  <a class="btn btn-warning" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#modalunduhtes">  <i class="fas fa-download"></i> Unduh CBT dari Server Lain</a>

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
                                                <th>Generate</th>
                                                <th>Aksi</th>
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
                                                <td><i class="fa fa-calendar-alt" aria-hidden="true"></i>
                                                    <?php echo date('d M Y H:i', strtotime($row['tanggal'])); ?></td>
                                                <td><?php echo $row['tampilan_soal']; ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 'Aktif') { ?>
                                                    <span class="badge bg-success">Aktif</span>
                                                    <?php } else { ?>
                                                    <span class="badge bg-danger">Nonaktif</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $row['token']; ?></td>
                                                <td>
                                                    <?php if ($row['status'] == 'Aktif') { ?>
                                                    <a href="generate_token.php?id_soal=<?php echo $row['id_soal']; ?>"
                                                        class="btn btn-sm btn-outline-secondary"><i
                                                            class="fa fa-history"></i> Token</a>
                                                    <?php } else { ?>
                                                    <?php } ?>
                                                    <?php if ($row['status'] == 'Aktif') { ?>
                                                    <a href="ubah_status_soal.php?id_soal=<?= $row['id_soal']; ?>&aksi=nonaktif"
                                                        class="btn btn-sm btn-secondary"><i class="fa fa-toggle-off"
                                                            aria-hidden="true"></i> Nonaktifkan</a>
                                                    <?php } else { ?>
                                                    <a href="ubah_status_soal.php?id_soal=<?= $row['id_soal']; ?>&aksi=aktif"
                                                        class="btn btn-sm btn-info"><i class="fa fa-toggle-on"
                                                            aria-hidden="true"></i> Aktifkan</a>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <a href="preview_soal.php?kode_soal=<?php echo $row['kode_soal']; ?>"
                                                        class="btn btn-sm btn-outline-secondary"><i
                                                            class="fa fa-eye"></i> Preview</a>
                                                    <a href="edit_soal.php?id_soal=<?php echo $row['id_soal']; ?>"
                                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i>
                                                        Edit</a>
                                                    <a href="simulasi_ujian.php?kode_soal=<?php echo $row['kode_soal']; ?>" target="_blank" class="btn btn-sm btn-info btn-duplicate">
                                                        <i class="fa fa-copy"></i> Simulasi
                                                    </a>
                                                    <a href="daftar_butir_soal.php?kode_soal=<?php echo $row['kode_soal']; ?>"
                                                        class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Input
                                                        Soal</a>
                                                    <button class="btn btn-danger btn-sm btn-hapus"
                                                        data-kode="<?= $row['kode_soal']; ?>">
                                                        <i class="fa fa-close" aria-hidden="true"></i>
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="modal fade" id="modalunduhtes" tabindex="-1" aria-labelledby="modalunduhtes"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="unduh_tes_dari_server_pusat.php" method="post">
                               
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalImportExcelLabel">Unduh CBT dari Server lain</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Tutup"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="file_excel" class="form-label">Kode soal</label>
                                             <input type="text" name="kode_soal" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Unduh</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
    <?php include '../inc/js.php'; ?>
    <script>
        // Tambahkan di bagian script yang sudah ada

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize DataTables
        $(document).ready(function() {
            $('#soalTable').DataTable({
                paging: true,
                lengthChange: true,
                searching: true,
                ordering: true,
                info: true,
                autoWidth: false,
                responsive: true
            });
        });
        document.querySelectorAll('.btn-hapus').forEach(function(button) {
            button.addEventListener('click', function() {
                const kodeSoal = this.getAttribute('data-kode');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: 'Ketik <strong>HAPUS</strong> untuk menghapus data soal ini.',
                    input: 'text',
                    inputPlaceholder: 'Ketik HAPUS di sini',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Hapus',
                    confirmButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    preConfirm: (inputValue) => {
                        if (inputValue !== 'HAPUS') {
                            Swal.showValidationMessage(
                                'Anda harus mengetik "HAPUS" dengan benar (huruf besar semua)'
                                );
                        }
                        return inputValue;
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value === 'HAPUS') {
                        window.location.href = 'hapus_soal.php?kode_soal=' +
                            encodeURIComponent(kodeSoal);
                    }
                });
            });
        });


    });
    </script>
    <?php if (isset($_SESSION['success'])): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: '<?php echo addslashes($_SESSION['success']); ?>',
        showConfirmButton: false,
        timer: 2000
    });
    </script>
    <?php unset($_SESSION['success']); endif; ?>
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
    <?php if (isset($_SESSION['success_message'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: '<?php echo $_SESSION['success_message']; ?>',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });
    </script>
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['warning_message'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Tidak Bisa Diedit!',
            text: '<?php echo $_SESSION['warning_message']; ?>',
            showConfirmButton: false,
            timer: 2000
        });
    });
    </script>
    <?php unset($_SESSION['warning_message']); ?>
    <?php endif; ?>
</body>

</html>
