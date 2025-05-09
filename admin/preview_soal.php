<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
// Cek jika sudah login
check_login('admin');
include '../inc/dataadmin.php';

if (!isset($_GET['kode_soal'])) {
  header('Location: soal.php');
  exit();
}

$kode_soal = $_GET['kode_soal'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Soal</title>
    <?php include '../inc/css.php'; ?>
    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <link href="../assets/summernote/summernote-bs5.css" rel="stylesheet">
    <style>
       .card img {
        max-width: 400px !important;
        max-height: 300px !important;
        height: auto;
        width: auto;
        object-fit: contain;
        display: block;
        margin: 10px 0;
    }

        label.note-form-label{display:none;!important}
        table th, table td {
    text-align: left !important;
    }
    .form-check-label strong {
        width: 24px;
        display: inline-block;
    }
    .card-utama {
    background-color: #fff !important; /* Warna putih */
    color: #000; /* Menjaga teks tetap terbaca dengan warna hitam */
    padding : 30px;
    }
    .mb-4.p-3.border.rounded.bg-white {
    border: 1px solid grey !important; /* Menghilangkan border */
    border-bottom: 1px solid grey !important; /* Menambahkan border bawah berwarna hitam */
    border-radius: 10px!important;
    }
    .custom-card {
    border: 1px solid #343a40; /* Border gelap untuk card */
    border-radius: 10px;        /* Sudut border yang lebih halus */
    }

    /* Kustomisasi Border untuk header card */
    .custom-card-header {
        border-bottom: 1px solid #343a40; /* Garis bawah header card */
        background-color: #f8f9fa; /* Warna latar belakang header */
        padding: 10px; /* Padding tambahan untuk header */
        font-weight: bold; /* Agar teks header lebih tebal */
    }
    .custom-radio-spacing {
    margin-right: 100px; /* Menambah jarak kanan antar radio button */
    }
    input[type="radio"]:not(:checked) {
        border-color: black;  /* Warna border hitam */
    }
    input[type="radio"]:checked {
        background-color: green;  /* Warna latar belakang hijau */
        border-color: green;  /* Warna border hijau */
    }
    input[type="checkbox"]:not(:checked) {
        border-color: black;  /* Warna border hitam */
    }
    input[type="checkbox"]:checked {
        background-color: green;  /* Warna latar belakang hijau */
        border-color: green;  /* Warna border hijau */
    }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'navbar.php'; ?>
            <!-- Content -->
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                            <div class="card-header">
                            <button type="button" class="btn btn-outline-danger" onclick="exportPDF()"><i class="fa-solid fa-file-pdf"></i> Download PDF</button>
                            <button type="button" class="btn btn-outline-secondary" onclick="printModalContent()"><i class="fa fa-print"></i> Print</button>
                            <a href="daftar_butir_soal.php?kode_soal=<?php echo $kode_soal;?>"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button></a>
                                </div>
                                <div class="card-body card-utama" id="canvas_div_pdf">
                <?php
                    // Ambil info soal (header)
                    $query_info = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal='$kode_soal' LIMIT 1");
                    $info_soal = mysqli_fetch_assoc($query_info);

                    // Hitung jumlah soal per tipe soal
                    $tipe_soal_count = [];
                    $query_tipe_soal = mysqli_query($koneksi, "SELECT tipe_soal, COUNT(*) as jumlah FROM butir_soal WHERE kode_soal='$kode_soal' GROUP BY tipe_soal");
                    while ($row = mysqli_fetch_assoc($query_tipe_soal)) {
                        $tipe_soal_count[$row['tipe_soal']] = $row['jumlah'];
                    }

                    // Hitung total jumlah soal
                    $query_jumlah = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM butir_soal WHERE kode_soal='$kode_soal'");
                    $jumlah_soal = mysqli_fetch_assoc($query_jumlah)['total'];
                    ?>

                    <div class="card custom-card mb-3" style="max-width: 28rem">
                    <div class="card-header custom-card-header">&nbsp;&nbsp;Detail Soal</div>
                    <div class="card-body text-dark">
                        <h3 class="card-title text-dark"><strong>Kode Soal:</strong> <?= htmlspecialchars($info_soal['kode_soal']) ?></h3>
                        <h5>Mapel:</strong> <?= htmlspecialchars($info_soal['mapel']) ?></h5>
                        <h5>Jumlah Soal:
                        
                            <?php
                            // Hitung total soal dari semua tipe
                            $total_soal = array_sum($tipe_soal_count);
                            echo $total_soal;
                            ?>
                        | 
                        
                            <?php
                            $tipe_soal_list = [];
                            foreach ($tipe_soal_count as $tipe => $count) {
                                // Mengganti tipe soal dengan singkatan yang sesuai
                                switch ($tipe) {
                                    case 'Pilihan Ganda':
                                        $tipe = 'PG';
                                        break;
                                    case 'Pilihan Ganda Kompleks':
                                        $tipe = 'PGX';
                                        break;
                                    case 'Benar/Salah':
                                        $tipe = 'BS';
                                        break;
                                    case 'Menjodohkan':
                                        $tipe = 'MJD';
                                        break;
                                    case 'Uraian':
                                        $tipe = 'U';
                                        break;
                                }
                                $tipe_soal_list[] = htmlspecialchars($tipe) . " " . $count;
                            }
                            echo implode(" | ", $tipe_soal_list);
                            ?>
                        </h5>
                    </div>
                    </div>
            <?php
            $query_preview = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE kode_soal='$kode_soal' ORDER BY nomer_soal ASC");
            while ($soal = mysqli_fetch_assoc($query_preview)) {
                echo "<div class='mb-4 p-3 border rounded bg-white oke'>";
                echo "<h5 style='background-color:grey;padding:5px;color:white'><b style='padding:5px;background-color:black;color:white;'>No. " . htmlspecialchars($soal['nomer_soal']) . "</b>  <i> (" . htmlspecialchars($soal['tipe_soal']) . ")</i></h5>";

                $jawaban_benar = $soal['jawaban_benar'];

                if ($soal['tipe_soal'] === 'Pilihan Ganda') {
                    echo "<p class='text-dark'>" . $soal['pertanyaan'] . "</p>";
                    $opsi_huruf = ['A', 'B', 'C', 'D'];
                    for ($i = 1; $i <= 4; $i++) {
                        $opsi_label = $opsi_huruf[$i - 1];
                        $opsi_nama = 'pilihan_' . $i;
                        $nilai = $soal[$opsi_nama];
                        $checked = (trim($soal['jawaban_benar']) === $opsi_nama) ? 'checked' : '';
                        echo "
                        <div class='form-check mb-1'>
                            <input class='form-check-input' type='radio' name='preview_radio_{$soal['id_soal']}' value='$nilai' $checked onclick='return false;'>
                            <label class='form-check-label text-dark'>
                                $opsi_label. $nilai
                            </label>
                        </div>";
                    }
                } elseif ($soal['tipe_soal'] === 'Pilihan Ganda Kompleks') {
                    echo "<p class='text-dark'>" . $soal['pertanyaan'] . "</p>";
                    $jawaban_benar = array_map('trim', explode(',', $soal['jawaban_benar']));
                    $opsi_huruf = ['A', 'B', 'C', 'D'];
                    for ($i = 1; $i <= 4; $i++) {
                        $opsi_label = $opsi_huruf[$i - 1];
                        $opsi_nama = 'pilihan_' . $i;
                        $nilai = $soal[$opsi_nama];
                        $checked = in_array($opsi_nama, $jawaban_benar) ? 'checked' : '';
                        echo "
                        <div class='form-check mb-1'>
                            <input class='form-check-input' type='checkbox' name='preview_checkbox_{$soal['id_soal']}[]' value='$nilai' $checked onclick='return false;'>
                            <label class='form-check-label text-dark'>
                                $opsi_label. $nilai
                            </label>
                        </div>";
                    }
                } elseif ($soal['tipe_soal'] === 'Menjodohkan') {
                    echo "<p class='text-dark'>" . $soal['pertanyaan'] . "</p>";
                    $pasangan = explode('|', $jawaban_benar);
                    echo "<table class='table-menjodohkan-print' style='width:100%; border-collapse: collapse; margin-top:10px;'>";
                    echo "<thead><tr>
                            <th style='border:1px solid #000; padding:5px; background:#f0f0f0;'>Pilihan</th>
                            <th style='border:1px solid #000; padding:5px; background:#f0f0f0;'>Pasangan</th>
                        </tr></thead><tbody>";
                    foreach ($pasangan as $pair) {
                        if (strpos($pair, ':') !== false) {
                            [$kiri, $kanan] = explode(':', $pair, 2);
                            echo "<tr>
                                    <td style='border:1px solid #000; padding:5px;'>$kiri</td>
                                    <td style='border:1px solid #000; padding:5px;'>$kanan</td>
                                </tr>";
                        }
                    }
                    echo "</tbody></table>";
                }  elseif ($soal['tipe_soal'] === 'Benar/Salah') {
                    echo "<p class='text-dark'>" . $soal['pertanyaan'] . "</p>";
                    $opsi = [];
                    for ($i = 1; $i <= 4; $i++) {
                        $opsi_nama = 'pilihan_' . $i;
                        $nilai = $soal[$opsi_nama];
                        if (!empty($nilai)) {
                            $opsi[] = $nilai;
                        }
                    }
                    $jawaban_benar = array_map('trim', explode('|', $soal['jawaban_benar']));
                
                    // HANYA 1x buat tabel
                    echo "<table style='width:100%; border-collapse:collapse; margin-top:10px;'>
                            <thead>
                                <tr style='background-color:#f0f0f0;'>
                                    <th style='border:1px solid black; padding:8px;'>Pernyataan</th>
                                    <th style='border:1px solid black; padding:8px; text-align:center;'>Benar</th>
                                    <th style='border:1px solid black; padding:8px; text-align:center;'>Salah</th>
                                </tr>
                            </thead>
                            <tbody>";
                
                    foreach ($opsi as $index => $nilai) {
                        $is_benar = isset($jawaban_benar[$index]) && $jawaban_benar[$index] === 'Benar';
                        $is_salah = isset($jawaban_benar[$index]) && $jawaban_benar[$index] === 'Salah';
                
                                echo "<tr>
                                        <td style='border:1px solid black; padding:8px;'>$nilai</td>
                                        <td style='border:1px solid black; text-align:center;padding-left:10px;'>
                                            <input class='form-check-input' type='radio' name='preview_radio_{$soal['id_soal']}_{$index}' value='Benar' " . ($is_benar ? 'checked' : '') . " onclick='return false;'>
                                        </td>
                                        <td style='border:1px solid black; text-align:center;padding-left:10px;'>
                                            <input class='form-check-input' type='radio' name='preview_radio_{$soal['id_soal']}_{$index}' value='Salah' " . ($is_salah ? 'checked' : '') . " onclick='return false;'>
                                        </td>
                                      </tr>";
                            }
                
                                  echo "</tbody></table>";
                              }elseif ($soal['tipe_soal'] === 'Uraian') {
                                  echo "<p class='text-dark'>Pertanyaan : " . $soal['pertanyaan'] . "</p>";
                                  echo "<p class='mt-2'>Jawaban Benar: <span class='text-dark'>" . $soal['jawaban_benar'] . "</span></p>";
                              }

                              echo "</div>";
                          }
                          ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
<?php include '../inc/js.php'; ?>
<script src="../assets/html2pdf.js/dist/html2pdf.bundle.min.js"></script>
<script>
    function exportPDF() {
        var element = document.getElementById('canvas_div_pdf');
        html2pdf().set({
            margin: 0.2,
            filename: 'Soal_<?php echo $kode_soal;?>.pdf',
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 2, logging: true },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        }).from(element).save();
    }
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const images = document.querySelectorAll('.card-utama img');

    images.forEach(function(img) {
        img.style.maxWidth = '200px';
        img.style.maxHeight = '200px';

    });
});
</script>
    <script>
        $(document).ready(function() {
            $('#butirsoal').DataTable({
                "paging": true,
                "searching": true,
                order: [[0, 'asc']],
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
                    window.location.href = 'hapus_butir_soal.php?id_soal=' + encodeURIComponent(kodeSoal);
                }
            });
        });
    });
    </script>
<script>
function printModalContent() {
    const modalBody = document.querySelector('.card-utama').innerHTML;
    const printWindow = window.open('', '', 'width=1000,height=700');

    printWindow.document.write(`
        <html>
            <head>
                <title>Print Preview</title>
                <link rel="stylesheet" href="../inc/print-soal.css" type="text/css" />
            </head>
            <body onload="window.print(); window.close();">
                ${modalBody}
            </body>
        </html>
    `);

    printWindow.document.close();
}
</script>
</body>
</html>