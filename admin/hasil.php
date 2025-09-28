<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$user_id = $_SESSION['admin_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_siswa = $_POST['nama_siswa'] ?? '';
    $kelas_rombel = $_POST['kelas_rombel'] ?? '';
    $kode_soal = $_POST['kode_soal'] ?? '';

    $where = "1 = 1"; // Kondisi awal untuk semua data

    // Filter Kelas Rombel
    if (!empty($kelas_rombel)) {
        list($kelas, $rombel) = explode(' - ', $kelas_rombel);
        $kelas = mysqli_real_escape_string($koneksi, $kelas);
        $rombel = mysqli_real_escape_string($koneksi, $rombel);
        $where .= " AND s.kelas = '$kelas' AND s.rombel = '$rombel'";
    }

    // Filter Kode Soal
    if (!empty($kode_soal)) {
        $kode_soal = mysqli_real_escape_string($koneksi, $kode_soal);
        $where .= " AND n.kode_soal = '$kode_soal'";
    }

    // Filter Nama Siswa
    if (!empty($nama_siswa)) {
        $nama_siswa = mysqli_real_escape_string($koneksi, $nama_siswa);
        $where .= " AND s.nama_siswa LIKE '%$nama_siswa%'";
    }

    $query = "SELECT n.id_nilai, n.id_siswa, s.nama_siswa, s.kelas, s.rombel, n.kode_soal, n.total_soal, 
                n.status_penilaian, n.jawaban_benar, n.jawaban_salah, n.jawaban_kurang, 
                n.nilai, n.nilai_uraian, n.tanggal_ujian
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
                        <th>Nilai PG|PGX|MJD|BS</th>
                        <th>Nilai Uraian</th>
                        <th>Nilai Akhir</th>
                        <th>Tanggal Ujian</th>
                        <th>Koreksi Uraian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>';
        
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            // Tombol Aksi
            $hapusBtn = "<button class='btn btn-sm btn-danger btnHapus' data-id='{$row['id_nilai']}'>
                            <i class='fa fa-close'></i> Hapus
                         </button>";
            
            $prevBtn = "<a href='preview_siswa.php?id_siswa={$row['id_siswa']}&kode_soal={$row['kode_soal']}' 
                          class='btn btn-sm btn-secondary'>
                          <i class='fa fa-eye'></i> Preview
                       </a>";
            
            $koreksiBtn = '';
            if ($row['status_penilaian'] === 'perlu_dinilai') {
                $btnClass = ($row['nilai_uraian'] <= 0) ? 'outline-danger' : 'outline-info';
                $koreksiBtn = "<button class='btn btn-sm btn-{$btnClass} btnKoreksi' 
                                  data-id_siswa='{$row['id_siswa']}' 
                                  data-kode_soal='{$row['kode_soal']}'>
                                  <i class='fa fa-edit'></i> " . 
                                  (($row['nilai_uraian'] <= 0) ? 'Belum' : 'Sudah') . 
                               "</button>";
            }

            // Format Nilai
            $nilai = number_format($row['nilai'], 2);
            $nilai_akhir = number_format($row['nilai'] + $row['nilai_uraian'], 2);
            $tanggal_ujian = date('d M Y, H:i', strtotime($row['tanggal_ujian']));

            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['nama_siswa']}</td>
                    <td>{$row['kode_soal']}</td>
                    <td>{$row['total_soal']}</td>
                    <td>{$row['kelas']} {$row['rombel']}</td>
                    <td>{$nilai}</td>
                    <td>{$row['nilai_uraian']}</td>
                    <td class='nilai-col'>{$nilai_akhir}</td>
                    <td>{$tanggal_ujian}</td>
                    <td>{$koreksiBtn}</td>
                    <td>
                        <div class='btn-group' role='group'>
                            {$prevBtn}
                            {$hapusBtn}
                        </div>
                    </td>
                  </tr>";
            $no++;
        }
        
        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-primary alert-dismissible fade show col-12" role="alert">
                Data tidak ditemukan.
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
                            <form id="filterForm" method="POST" class="row g-3 mb-4 align-items-end">
                                <div class="col-md-4">
                                    <label for="nama_siswa" class="form-label">Cari Siswa</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control" name="nama_siswa" id="nama_siswa"
                                            placeholder="Ketikan nama siswa...">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="kelas_rombel" class="form-label">Kelas & Rombel</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                                        <select class="form-select" name="kelas_rombel" id="kelas_rombel">
                                            <option value="">Semua Kelas</option>
                                            <?php
                                            $qKR = mysqli_query($koneksi, "SELECT DISTINCT CONCAT(kelas, ' - ', rombel) 
                                                                          AS kelas_rombel FROM siswa 
                                                                          ORDER BY kelas, rombel");
                                            while ($kr = mysqli_fetch_assoc($qKR)) {
                                                echo "<option value='{$kr['kelas_rombel']}'>{$kr['kelas_rombel']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="kode_soal" class="form-label">Kode Ujian</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                        <select class="form-select" name="kode_soal" id="kode_soal">
                                            <option value="">Semua Kode</option>
                                            <?php
                                            $qSoal = mysqli_query($koneksi, "SELECT DISTINCT kode_soal, `nama_soal` FROM soal where `user_id` = '$user_id'");
                                            while ($soal = mysqli_fetch_assoc($qSoal)) {
                                                echo "<option value='{$soal['kode_soal']}'>{$soal['kode_soal']} {$soal['nama_soal']}</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 d-grid">
                                    <button type="button" class="btn btn-light" onclick="resetFilter()">
                                        <i class="fas fa-sync"></i> Reset
                                    </button>
                                </div>
                            </form>

                            <div id="nilaiTable" class="table-wrapper"></div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include '../inc/js.php'; ?>
    <script>
    $(document).ready(function() {
        let delayTimer;
        
        // Live Search
        $('#nama_siswa').on('input', function() {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(() => $('#filterForm').submit(), 500);
        });

        // Auto Submit on Filter Change
        $('#kelas_rombel, #kode_soal').on('change', function() {
            $('#filterForm').submit();
        });

        // Handle Form Submit
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '',
                type: 'POST',
                data: $(this).serialize(),
                beforeSend: () => $('#nilaiTable').html('<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>'),
                success: function(response) {
                    $('#nilaiTable').html(response);
                    initDataTable();
                },
                error: () => Swal.fire('Error', 'Gagal memuat data', 'error')
            });
        });

        // Initialize DataTable
        function initDataTable() {
            if ($.fn.DataTable.isDataTable('#nilaiTableData')) {
                $('#nilaiTableData').DataTable().destroy();
            }

            $('#nilaiTableData').DataTable({
                dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"l><"col-sm-12 col-md-7 text-end"p>>',
                buttons: [
                    'copy', 'excel', 
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Laporan Nilai Ujian'
                    },
                    'print'
                ],
                responsive: true,
                order: [[8, 'desc']],
                columnDefs: [
                    { targets: 0, orderable: false, searchable: false },
                    { targets: -1, orderable: false, searchable: false }
                ],
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                language: {
                    decimal: ",",
                    thousands: ".",
                    lengthMenu: "Tampilkan _MENU_ data",
                    search: "Cari:",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                },
                drawCallback: function(settings) {
                    var api = this.api();
                    api.column(0, {search: 'applied', order: 'applied'}).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }
            });
        }


        // Reset Filter
        window.resetFilter = () => {
            $('#filterForm')[0].reset();
            $('#filterForm').submit();
        }

        // Delete Handler
        $(document).on('click', '.btnHapus', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Hapus Data?',
                text: "Data nilai akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('hapus_nilai.php', {id_nilai: id}, () => {
                        Swal.fire('Sukses!', 'Data berhasil dihapus', 'success');
                        $('#filterForm').submit();
                    }).fail(() => Swal.fire('Error', 'Gagal menghapus data', 'error'));
                }
            });
        });

        // Koreksi Uraian Handler
        $(document).on('click', '.btnKoreksi', function() {
            const id_siswa = $(this).data('id_siswa');
            const kode_soal = $(this).data('kode_soal');
            
            $('#koreksiContent').html('<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>');
            $('#modalKoreksiUraian').modal('show');
            
            $.post('koreksi_uraian.php', {id_siswa, kode_soal}, (res) => {
                $('#koreksiContent').html(res);
            }).fail(() => $('#koreksiContent').html('<div class="alert alert-danger">Gagal memuat data</div>'));
        });

        // Submit Koreksi
        $('#formKoreksiUraian').on('submit', function(e) {
            e.preventDefault();
            $.post('simpan_nilai_uraian.php', $(this).serialize(), (res) => {
                Swal.fire('Sukses!', 'Nilai berhasil disimpan', 'success');
                $('#modalKoreksiUraian').modal('hide');
                $('#filterForm').submit();
            }).fail(() => Swal.fire('Error', 'Gagal menyimpan nilai', 'error'));
        });
    });
    </script>

    <!-- Modal Koreksi -->
    <div class="modal fade" id="modalKoreksiUraian" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Koreksi Jawaban Uraian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="formKoreksiUraian">
                    <div class="modal-body">
                        <div id="koreksiContent"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
