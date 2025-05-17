<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

if (!isset($_GET['kode_soal']) || !isset($_GET['id_siswa'])) {
    echo "Parameter kode_soal dan id_siswa harus ada.";
    exit;
}

$kode_soal = $_GET['kode_soal'];
$id_siswa = $_GET['id_siswa'];

// Ambil data nilai, nama siswa dan tanggal ujian dari tabel nilai
$query_nilai = mysqli_query($koneksi, "SELECT jawaban_siswa, nilai, nama_siswa, tanggal_ujian FROM nilai WHERE kode_soal='$kode_soal' AND id_siswa='$id_siswa' LIMIT 1");
if (!$query_nilai || mysqli_num_rows($query_nilai) == 0) {
    echo "Data nilai siswa tidak ditemukan.";
    exit;
}
$row_nilai = mysqli_fetch_assoc($query_nilai);
$jawaban_siswa_raw = $row_nilai['jawaban_siswa'];
$nilai_siswa = $row_nilai['nilai'] ?? '-';
$nama_siswa = $row_nilai['nama_siswa'] ?? '-';
$tanggal_ujian = $row_nilai['tanggal_ujian'] ?? '-';

function parseJawabanSiswa($str) {
    $pattern = '/\[(\d+):([^\]]*)\]/';
    preg_match_all($pattern, $str, $matches, PREG_SET_ORDER);
    $hasil = [];
    foreach ($matches as $m) {
        $no = (int)$m[1];
        $jawab = trim($m[2]);
        $hasil[$no] = $jawab;
    }
    return $hasil;
}

$jawaban_siswa = parseJawabanSiswa($jawaban_siswa_raw);

$query_soal = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE kode_soal='$kode_soal' ORDER BY nomer_soal ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Preview Jawaban Siswa</title>
    <?php include '../inc/css.php'; ?>
    <style>
        /* style tambahan untuk header 2 kolom */
        .header-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .header-left, .header-right {
            width: 48%;
            font-weight: bold;
            font-size: 16px;
            line-height: 1.5;
        }
        .header-right {
            border: 1px solid #aaa;
            padding: 10px;
            height: 72px; /* kira-kira 3 baris dengan line-height 1.5 * 16px font-size */
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background-color: #f7f7f7;
        }

        .card img {
            max-width: 400px !important;
            max-height: 300px !important;
            object-fit: contain;
            display: block;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #aaa;
            padding: 6px;
        }
        .pembahasan {
            background-color: rgb(213, 213, 213);
            background-image: radial-gradient(rgb(255, 255, 255) 1px, transparent 1px);
            background-size: 20px 20px;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            color: rgb(0, 0, 0);
            font-style: italic;
            white-space: pre-wrap;
        }
        ul {
            list-style-type:none; padding-left:0;
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
                <h1>Preview Jawaban Siswa</h1>
                
                <!-- HEADER 2 KOLM -->
                <div class="row mb-4" style="background-color: #444; color: white; border-radius: 10px; padding: 20px;">
                    <div class="col-md-9">
                        <p><strong>Nama Siswa:</strong> <?= htmlspecialchars($nama_siswa) ?></p>
                        <p><strong>Kode Soal:</strong> <?= htmlspecialchars($kode_soal) ?></p>
                        <p><strong>Tanggal Ujian:</strong> <?= htmlspecialchars($tanggal_ujian) ?></p>
                    </div>
                    <div class="col-md-3 text-center d-flex align-items-center justify-content-center">
                        <div style="background-color: white; color: black; padding: 20px; border-radius: 15px; width: 100%; height: 100%;">
                            <h4 class="mb-0">Nilai</h4>
                            <h1 style="font-size: 3rem;"><?= number_format($nilai_siswa, 2) ?></h1>
                        </div>
                    </div>
                </div>

                <?php while ($soal = mysqli_fetch_assoc($query_soal)): 
                    $no = (int)$soal['nomer_soal'];
                    $jawab = isset($jawaban_siswa[$no]) ? $jawaban_siswa[$no] : '';
                    $tipe = $soal['tipe_soal'];
                    $opsi_huruf = ['A', 'B', 'C', 'D'];
                ?>
                <div class="row">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5>No. <?= $no ?> (<?= $tipe ?>)</h5>
                        <p><?= $soal['pertanyaan'] ?></p>
                        <?php if (!empty($soal['gambar'])): ?>
                            <img src="../assets/img/butir_soal/<?= $soal['gambar'] ?>" alt="Gambar Soal" />
                        <?php endif; ?>
                        
                        <h6>Jawaban Siswa:</h6>
                        <?php
                        switch ($tipe) {
                            case 'Pilihan Ganda':
                                echo "<ul>";
                                for ($i=1; $i<=4; $i++) {
                                    $huruf = $opsi_huruf[$i-1];
                                    $checked = ($jawab == "pilihan_$i") ? "✓" : "";
                                    echo "<li>$huruf. " . $soal["pilihan_$i"] . " $checked</li>";
                                }
                                echo "</ul>";
                                $benar_num = (int)str_replace("pilihan_", "", $soal['jawaban_benar']);
                                $benar_huruf = $opsi_huruf[$benar_num - 1];
                                echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>Jawaban benar: ' . $benar_huruf . '</div>';
                                break;

                            case 'Pilihan Ganda Kompleks':
                                $jawaban_arr = array_map('trim', explode(',', $jawab));
                                echo "<ul>";
                                for ($i=1; $i<=4; $i++) {
                                    $huruf = $opsi_huruf[$i-1];
                                    $checked = in_array("pilihan_$i", $jawaban_arr) ? "✓" : "";
                                    echo "<li>$huruf. " . $soal["pilihan_$i"] . " $checked</li>";
                                }
                                echo "</ul>";
                                $kunci_arr = array_map('trim', explode(',', $soal['jawaban_benar']));
                                $huruf_benar = [];
                                foreach ($kunci_arr as $k) {
                                    $num = (int)str_replace("pilihan_", "", $k);
                                    $huruf_benar[] = $opsi_huruf[$num - 1];
                                }
                                echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>Jawaban benar: ' . implode(', ', $huruf_benar) . '</div>';
                                break;

                            case 'Benar/Salah':
                                $pernyataan = [];
                                for ($i=1; $i<=4; $i++) {
                                    if (!empty($soal["pilihan_$i"])) {
                                        $pernyataan[] = $soal["pilihan_$i"];
                                    }
                                }
                                $jawab_arr = explode('|', $jawab);
                                echo "<table><thead><tr><th>#</th><th>Pernyataan</th><th>Benar</th><th>Salah</th></tr></thead><tbody>";
                                foreach ($pernyataan as $i => $text) {
                                    $val = isset($jawab_arr[$i]) ? $jawab_arr[$i] : '';
                                    echo "<tr><td>" . ($i+1) . "</td><td>" . $text . "</td><td>" . ($val == "Benar" ? "✓" : "") . "</td><td>" . ($val == "Salah" ? "✓" : "") . "</td></tr>";
                                }
                                echo "</tbody></table>";

                               $kunci_arr = explode('|', $soal['jawaban_benar']);
                                echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>';
                                foreach ($pernyataan as $i => $text) {
                                    $nilai = $kunci_arr[$i] ?? '-';
                                    echo "Pernyataan " . ($i + 1) . ": " . htmlspecialchars($nilai) . "<br>";
                                }
                                echo '</div>';
                                break;

                            case 'Menjodohkan':
                                $pairs = explode('|', $jawab);
                                echo "<table><thead><tr><th>#</th><th>Pilihan</th><th>Pasangan</th></tr></thead><tbody>";
                                foreach ($pairs as $i => $pair) {
                                    list($a, $b) = explode(':', $pair) + [null,null];
                                    echo "<tr><td>" . ($i+1) . "</td><td>" . htmlspecialchars($a) . "</td><td>" . htmlspecialchars($b) . "</td></tr>";
                                }
                                echo "</tbody></table>";

                                $kunci_pairs = explode('|', $soal['jawaban_benar']);
                                echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>';
                                foreach ($kunci_pairs as $i => $pair) {
                                    echo htmlspecialchars($pair) . "<br>";
                                }
                                echo '</div>';
                                break;

                            case 'Uraian':
                                echo "<div class='border p-2 mb-2'>" . nl2br(htmlspecialchars($jawab)) . "</div>";
                                echo '<div class="pembahasan"><strong>Pembahasan:</strong><br>' . nl2br(htmlspecialchars($soal['jawaban_benar'])) . '</div>';
                                break;

                            default:
                                echo '<div>Jawaban tidak tersedia untuk tipe soal ini.</div>';
                                break;
                        }
                        ?>
                    </div>
                </div>
                </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>
</div>
<?php include '../inc/js.php'; ?>
</body>
</html>
