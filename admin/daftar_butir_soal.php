<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

if (!isset($_GET['kode_soal'])) {
    header('Location: soal.php');
    exit();
}

$kode_soal = $_GET['kode_soal'];
// Ambil data soal
$query_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal='$kode_soal'");
$data_soal = mysqli_fetch_assoc($query_soal);
if ($data_soal['status'] == 'Aktif') {
        $_SESSION['warning_message'] = "Soal ini sudah aktif dan tidak bisa diedit!.";
    header('Location: soal.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Butir Soal</title>
    <?php include '../inc/css.php'; ?>
    <link href="../assets/summernote/summernote-bs5.css" rel="stylesheet">
    <style>
    .table-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .dataTables_paginate {
        display: block;
        text-align: center;
        margin-top: 10px;
    }

    .dataTables_paginate .paginate_button {
        padding: 5px 10px;
        margin: 0 5px;
        background-color: #f4f4f4;
        border: 1px solid #ddd;
        cursor: pointer;
    }

    .dataTables_paginate .paginate_button:hover {
        background-color: #007bff;
        color: white;
    }

    table img {
        max-width: 150px;
        max-height: 150px;
        height: auto;
        object-fit: contain;
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
                    <!-- Tampilkan data soal -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Daftar Butir Soal</h5>
                                    <br>
                                    <h2 class=""><strong>Kode Soal:
                                            <?= htmlspecialchars($data_soal['kode_soal']) ?></strong></h2>
                                    <?php
                                $query_butir = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE kode_soal='$kode_soal' ORDER BY id_soal ASC");
                                $jumlah_pg = 0;
                                $jumlah_pg_kompleks = 0;
                                $jumlah_benar_salah = 0;
                                $jumlah_menjodohkan = 0;
                                $jumlah_uraian = 0;
                                while ($data = mysqli_fetch_assoc($query_butir)) {
                                    switch ($data['tipe_soal']) {
                                        case 'Pilihan Ganda':
                                            $jumlah_pg++;
                                            break;
                                        case 'Pilihan Ganda Kompleks':
                                            $jumlah_pg_kompleks++;
                                            break;
                                        case 'Benar/Salah':
                                            $jumlah_benar_salah++;
                                            break;
                                        case 'Menjodohkan':
                                            $jumlah_menjodohkan++;
                                            break;
                                        case 'Uraian':
                                            $jumlah_uraian++;
                                            break;
                                    }
                                }
                                echo "<p>PG: " . $jumlah_pg . " | PGX: " . $jumlah_pg_kompleks . " | BS: " . $jumlah_benar_salah . " | MJD: " . $jumlah_menjodohkan . " | U: " . $jumlah_uraian . " <p>";
                                ?>
                                    <div class="table-wrapper">
                                        <?php
                                $kode_soal = mysqli_real_escape_string($koneksi, $_GET['kode_soal']);

                                // Cari nomor yang hilang dulu
                                $query_gap = mysqli_query($koneksi, "
                                    SELECT MIN(t1.nomer_soal + 1) AS nomor_lompatan
                                    FROM butir_soal t1
                                    LEFT JOIN butir_soal t2 
                                        ON t2.nomer_soal = t1.nomer_soal + 1 AND t2.kode_soal = '$kode_soal'
                                    WHERE t1.kode_soal = '$kode_soal' AND t2.nomer_soal IS NULL
                                ");
                                
                                $data_gap = mysqli_fetch_assoc($query_gap);
                                $nomor_baru = $data_gap['nomor_lompatan'];
                                
                                // Jika tidak ada gap (misalnya hasilnya NULL), ambil MAX + 1
                                if (!$nomor_baru) {
                                    $query_last = mysqli_query($koneksi, "SELECT MAX(nomer_soal) AS nomor_terakhir FROM butir_soal WHERE kode_soal = '$kode_soal'");
                                    $data = mysqli_fetch_assoc($query_last);
                                    $nomor_terakhir = $data['nomor_terakhir'] ?? 0;
                                    $nomor_baru = $nomor_terakhir + 1;
                                }
                                
                                ?>
                                        <a href="soal.php" class="btn btn btn-outline-danger">
                                            <i class="fas fa-arrow-left"></i> Bank Soal
                                        </a>
                                        <a href="tambah_butir_soal.php?kode_soal=<?= htmlspecialchars($data_soal['kode_soal']) ?>&nomer_baru=<?= $nomor_baru ?>"
                                            class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Tambah Soal
                                        </a>
                                        <a href="preview_soal.php?kode_soal=<?php echo $kode_soal;?>"
                                            class="btn btn btn-outline-info">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                        <a href="export_excel.php?kode_soal=<?= urlencode($kode_soal) ?>" onmouseover="this.style.color='white';" onmouseout="this.style.color='#198754';"
                                            class="btn btn-outline-success">
                                            <i class="fas fa-file-excel"></i> Export
                                        </a>

                                        <table id="butirsoal" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No Soal</th>
                                                    <th>Pertanyaan</th>
                                                    <th>Tipe Soal</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                            $query_butir = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE kode_soal='$kode_soal' ORDER BY id_soal ASC");
                                            while ($butir = mysqli_fetch_assoc($query_butir)) {
                                                $json_butir = htmlspecialchars(json_encode($butir), ENT_QUOTES, 'UTF-8');
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($butir['nomer_soal']) . "</td>";
                                                echo "<td>$butir[pertanyaan]</td>";
                                                echo "<td>" . htmlspecialchars($butir['tipe_soal']) . "</td>";
                                                echo "<td>" . htmlspecialchars($butir['status_soal']) . "</td>";
                                                echo "<td>
                                                        <a href='edit_butir_soal.php?id_soal=" . htmlspecialchars($butir['id_soal']) . "&kode_soal=" . htmlspecialchars($kode_soal) . "' class='btn btn-sm btn-primary'>
                                                            <i class='fas fa-edit'></i> Edit
                                                        </a>
                                                        <button class='btn btn-sm btn-danger btn-hapus' data-kode=" . htmlspecialchars($butir['id_soal']) . ">
                                                            <i class='fa fa-close'></i> Hapus
                                                        </button>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                            ?>
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
    <?php include '../inc/js.php'; ?>
    <script>
    $(document).ready(function() {
        $('#butirsoal').DataTable({
            "paging": true,
            "searching": true,
            order: [
                [0, 'asc']
            ],
            "info": true,
            "lengthChange": true,
            "autoWidth": false,
        });
    });


    window.onload = function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success') && urlParams.get('success') === '1') {
            Swal.fire({
                icon: 'success',
                title: 'Data berhasil diperbarui!',
                showConfirmButton: true,
            });

            urlParams.delete('success');
            window.history.replaceState(null, null, window.location.pathname + '?' + urlParams.toString());
        }
    }

    document.querySelectorAll('.btn-hapus').forEach(function(button) {
        button.addEventListener('click', function() {
            const kodeSoal = this.getAttribute('data-kode');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data soal akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'hapus_butir_soal.php?id_soal=' + encodeURIComponent(
                        kodeSoal);
                }
            });
        });
    });
    </script>
</body>

</html>