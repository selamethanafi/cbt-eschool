<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kelas_rombel = $_POST['kelas_rombel'] ?? '';
    $kode_soal = $_POST['kode_soal'] ?? '';

    if ($kode_soal) {
        $where = "n.kode_soal = '$kode_soal'";
        if (!empty($kelas_rombel)) {
            list($kelas, $rombel) = explode(' - ', $kelas_rombel);
            $where .= " AND s.kelas = '$kelas' AND s.rombel = '$rombel'";
        }

        $query = "SELECT n.id_nilai, s.nama_siswa, s.kelas, s.rombel, n.kode_soal, n.total_soal, 
                         n.jawaban_benar, n.jawaban_salah, n.nilai, n.tanggal_ujian
                  FROM nilai n
                  JOIN siswa s ON n.id_siswa = s.id_siswa
                  WHERE $where
                  ORDER BY n.tanggal_ujian DESC";

        $result = mysqli_query($koneksi, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            echo '<table class="table table-bordered table-responsive" id="nilaiTableData">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Siswa</th>
                            <th>Kode Soal</th>
                            <th>Total Soal</th>
                            <th>Kelas</th>
                            <th>Jawaban Benar</th>
                            <th>Jawaban Salah</th>
                            <th>Nilai</th>
                            <th>Tanggal Ujian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>';
            $no = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $hapusBtn = "<button class='btn btn-sm btn-danger btnHapus' data-id='{$row['id_nilai']}'>
                                <i class='fa fa-trash'></i> Hapus
                            </button>";

                echo "<tr>
                        <td>{$no}</td>
                        <td>{$row['nama_siswa']}</td>
                        <td>{$row['kode_soal']}</td>
                        <td>{$row['total_soal']}</td>
                        <td>{$row['kelas']} {$row['rombel']}</td>
                        <td>{$row['jawaban_benar']}</td>
                        <td>{$row['jawaban_salah']}</td>
                        <td>{$row['nilai']}</td>
                        <td>{$row['tanggal_ujian']}</td>
                        <td>{$hapusBtn}</td>
                    </tr>";
                $no++;
            }
            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-primary alert-dismissible fade show col-md-6" role="alert">
                    Data tidak ditemukan.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show col-md-6" role="alert">
                Filter belum lengkap.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Daftar Nilai Ujian</h5>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" method="POST" class="row g-3 mb-3">
                            <div class="col-md-3">
                                <select class="form-control" name="kelas_rombel" id="kelas_rombel">
                                    <option value="">Semua Kelas</option>
                                    <?php
                                    $qKR = mysqli_query($koneksi, "SELECT DISTINCT CONCAT(kelas, ' - ', rombel) AS kelas_rombel FROM siswa ORDER BY kelas, rombel");
                                    while ($kr = mysqli_fetch_assoc($qKR)) {
                                        echo "<option value=\"{$kr['kelas_rombel']}\">{$kr['kelas_rombel']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="kode_soal" id="kode_soal">
                                    <option value="">-Pilih Kode Soal-</option>
                                    <?php
                                    $qSoal = mysqli_query($koneksi, "SELECT DISTINCT kode_soal FROM nilai");
                                    while ($soal = mysqli_fetch_assoc($qSoal)) {
                                        echo "<option value=\"{$soal['kode_soal']}\">{$soal['kode_soal']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">Tampilkan</button>
                            </div>
                        </form>

                        <div id="nilaiTable" class="d-none table-wrapper"></div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../inc/js.php'; ?>
<script src="../assets/datatables/jszip.min.js"></script>
<script src="../assets/datatables/buttons.html5.min.js"></script>
<script>
$(document).ready(function () {
    $('#filterForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '',
            type: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                $('#nilaiTable').html(response).removeClass('d-none');
                if ($('#nilaiTableData').length) {
                $('#nilaiTableData').DataTable({
                dom: '<"row mb-3"<"col-md-6"B><"col-md-6 text-end"f>>' +
                    '<"row mb-3"<"col-md-6"l><"col-md-6 d-flex justify-content-end"p>>' +
                    't' +
                    '<"row mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-end"p>>',
                buttons: ['copy', 'excel', 'pdf', 'print'],
                order: [[8, 'desc']],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                columnDefs: [{ targets: -1, orderable: false }],
                
                // 👇 Auto-numbering
                columnDefs: [{
                    targets: 0, // Kolom pertama (#)
                    searchable: false,
                    orderable: false,
                }],
                drawCallback: function (settings) {
                    var api = this.api();
                    api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
                }
            },
            error: function () {
                Swal.fire('Gagal', 'Gagal mengambil data.', 'error');
            }
        });
    });

    $(document).on('click', '.btnHapus', function () {
        let btn = $(this);
        let id = btn.data('id');

        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data nilai akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.prop('disabled', true); // Nonaktifkan tombol saat proses
                $.post('hapus_nilai.php', { id_nilai: id }, function (res) {
                    Swal.fire('Sukses', res, 'success');
                    $('#filterForm').submit(); // Reload data tabel
                }).fail(function () {
                    Swal.fire('Gagal', 'Gagal menghapus data.', 'error');
                }).always(function () {
                    btn.prop('disabled', false); // Aktifkan kembali tombol
                });
            }
        });
    });
});
</script>

</body>
</html>
