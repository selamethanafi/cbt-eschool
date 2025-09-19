<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

if (!isset($_GET['id_soal']) || !isset($_GET['kode_soal'])) {
    header('Location: soal.php');
    exit();
}

$id_soal = $_GET['id_soal'];
$kode_soal = $_GET['kode_soal'];

// Ambil data soal utama
$query_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal='$kode_soal'");
$data_soal = mysqli_fetch_assoc($query_soal);
if ($data_soal['status'] == 'Aktif') {
    die('
    <!DOCTYPE html>
    <html>
    <head>
        <title>Peringatan</title>
        <script src="../assets/js/sweetalert.js"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "warning",
                title: "Tidak Bisa Diedit!",
                text: "Soal ini sudah aktif dan tidak bisa diedit!",
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                window.location.href = "soal.php";
            });
        </script>
    </body>
    </html>
    ');
}

// Ambil data butir soal yang akan diedit
$query_butir = mysqli_query($koneksi, "SELECT * FROM butir_soal WHERE id_soal='$id_soal'");
$butir_soal = mysqli_fetch_assoc($query_butir);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['pertanyaan']) || empty($_POST['tipe_soal']) || empty($_POST['nomor_soal'])) {
        die("Harap isi semua field wajib");
    }

    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $tipe_soal = mysqli_real_escape_string($koneksi, $_POST['tipe_soal']);
    $nomor_soal = mysqli_real_escape_string($koneksi, $_POST['nomor_soal']);
    // Cek duplikat nomor soal kecuali untuk soal itu sendiri
$cek_duplikat = mysqli_query($koneksi, "SELECT * FROM butir_soal 
WHERE nomer_soal = '$nomor_soal' 
AND kode_soal = '$kode_soal' 
AND id_soal != '$id_soal'");

if (mysqli_num_rows($cek_duplikat) > 0) {
echo '
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peringatan</title>
    <script src="../assets/js/sweetalert.js"></script>
</head>
<body>
    <script>
        Swal.fire({
            icon: "error",
            title: "Nomor Soal Sudah Ada!",
            text: "Nomor soal yang Anda masukkan sudah digunakan oleh soal lain.",
            confirmButtonText: "Kembali"
        }).then(() => {
            window.history.back();
        });
    </script>
</body>
</html>';
exit();
}

    $query = "";

    if ($tipe_soal == 'Pilihan Ganda' || $tipe_soal == 'Pilihan Ganda Kompleks') {
        $pilihan_1 = mysqli_real_escape_string($koneksi, $_POST['pilihan_1']);
        $pilihan_2 = mysqli_real_escape_string($koneksi, $_POST['pilihan_2']);
        $pilihan_3 = mysqli_real_escape_string($koneksi, $_POST['pilihan_3']);
        $pilihan_4 = mysqli_real_escape_string($koneksi, $_POST['pilihan_4']);
	$pilihan_5 = mysqli_real_escape_string($koneksi, $_POST['pilihan_5']);

        if (!isset($_POST['jawaban_benar']) || count($_POST['jawaban_benar']) == 0) {
            die("Harap pilih minimal satu jawaban benar");
        }

        $jawaban_benar = implode(",", $_POST['jawaban_benar']);

        $query = "UPDATE butir_soal SET 
                  pertanyaan='$pertanyaan', 
                  tipe_soal='$tipe_soal',
                  nomer_soal='$nomor_soal',
                  pilihan_1='$pilihan_1', 
                  pilihan_2='$pilihan_2', 
                  pilihan_3='$pilihan_3', 
                  pilihan_4='$pilihan_4',
                  pilihan_5='$pilihan_5',  
                  jawaban_benar='$jawaban_benar'
                  WHERE id_soal='$id_soal'";

    } elseif ($tipe_soal == 'Benar/Salah') {
        if (empty($_POST['jawaban_benar'])) {
            die("Harap pilih jawaban benar");
        }
        $jawaban_benar = implode("|", $_POST['jawaban_benar']);
        $pilihan_1 = mysqli_real_escape_string($koneksi, $_POST['pilihan_1']);
        $pilihan_2 = mysqli_real_escape_string($koneksi, $_POST['pilihan_2']);
        $pilihan_3 = mysqli_real_escape_string($koneksi, $_POST['pilihan_3']);
        $pilihan_4 = mysqli_real_escape_string($koneksi, $_POST['pilihan_4']);
	$pilihan_5 = mysqli_real_escape_string($koneksi, $_POST['pilihan_5']);

        $query = "UPDATE butir_soal SET 
                  pertanyaan='$pertanyaan', 
                  tipe_soal='$tipe_soal',
                  nomer_soal='$nomor_soal',
                  pilihan_1='$pilihan_1', 
                  pilihan_2='$pilihan_2', 
                  pilihan_3='$pilihan_3', 
                  pilihan_4='$pilihan_4', 
                  pilihan_5='$pilihan_5',  
                  jawaban_benar='$jawaban_benar'
                  WHERE id_soal='$id_soal'";

    } elseif ($tipe_soal == 'Menjodohkan') {
    $pasangan_data = [];
    $pasangan_valid = 0;
    $pasangan_cek = [];
    $jawaban_cek = [];

    foreach ($_POST['pasangan_soal'] as $i => $soal) {
        $jawaban = $_POST['pasangan_jawaban'][$i];

        if (!empty($soal) && !empty($jawaban)) {
            if (trim($soal) === trim($jawaban)) {
                echo '
                <!DOCTYPE html>
                <html>
                <head><script src="../assets/js/sweetalert.js"></script></head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "error",
                            title: "Pasangan Tidak Valid",
                            text: "Soal dan jawaban dalam satu baris tidak boleh sama!",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.history.back();
                        });
                    </script>
                </body>
                </html>';
                exit;
            }

            $soal_clean = mysqli_real_escape_string($koneksi, trim($soal));
            $jawaban_clean = mysqli_real_escape_string($koneksi, trim($jawaban));
            $pasangan_key = $soal_clean . ':' . $jawaban_clean;

            // Cek apakah pasangan sudah ada sebelumnya
            if (in_array($pasangan_key, $pasangan_cek)) {
                echo '
                <!DOCTYPE html>
                <html>
                <head><script src="../assets/js/sweetalert.js"></script></head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "error",
                            title: "Pasangan Duplikat",
                            text: "Terdapat pasangan soal dan jawaban yang sama lebih dari sekali!",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.history.back();
                        });
                    </script>
                </body>
                </html>';
                exit;
            }

            // Cek apakah jawaban sudah digunakan di pasangan lain
            if (in_array($jawaban_clean, $jawaban_cek)) {
                echo '
                <!DOCTYPE html>
                <html>
                <head><script src="../assets/js/sweetalert.js"></script></head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "error",
                            title: "Jawaban Ganda",
                            text: "Satu jawaban tidak boleh digunakan untuk lebih dari satu soal!",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.history.back();
                        });
                    </script>
                </body>
                </html>';
                exit;
            }

            $pasangan_data[] = "$soal_clean:$jawaban_clean";
            $pasangan_cek[] = $pasangan_key;
            $jawaban_cek[] = $jawaban_clean;
            $pasangan_valid++;
        }
    }

    if ($pasangan_valid < 2) {
        echo '
        <!DOCTYPE html>
        <html>
        <head><script src="../assets/js/sweetalert.js"></script></head>
        <body>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Minimal 2 Pasangan",
                    text: "Harap isi minimal dua pasangan soal dan jawaban yang valid!",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>';
        exit;
    }

    $jawaban_benar = implode("|", $pasangan_data);

    $query = "INSERT INTO butir_soal (kode_soal, nomer_soal, pertanyaan, tipe_soal,
              jawaban_benar, status_soal)
              VALUES ('$kode_soal', '$nomer_soal', '$pertanyaan', '$tipe_soal',
              '$jawaban_benar', 'Aktif')";
} elseif ($tipe_soal == 'Uraian') {
        if (empty($_POST['jawaban_benar'])) {
            die("Harap isi jawaban benar");
        }
        $jawaban_benar = mysqli_real_escape_string($koneksi, $_POST['jawaban_benar']);

        $query = "UPDATE butir_soal SET 
                  pertanyaan='$pertanyaan', 
                  tipe_soal='$tipe_soal',
                  nomer_soal='$nomor_soal',
                  jawaban_benar='$jawaban_benar'
                  WHERE id_soal='$id_soal'";
    }

    if (!empty($query)) {
        if (mysqli_query($koneksi, $query)) {
            header("Location: daftar_butir_soal.php?kode_soal=$kode_soal&success=1");
            exit();
        } else {
            die("Gagal menyimpan data: " . mysqli_error($koneksi));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Butir Soal</title>
    <?php include '../inc/css.php'; ?>
    <script src="../assets/js/jquery-3.6.0.min.js"></script>
    <link href="../assets/summernote/summernote-bs5.css" rel="stylesheet">
    <style>
    .note-editable img {
    max-width: 400px !important;
    max-height: 400px !important;
    height: auto;
    width: auto;
}
        label.note-form-label{display:none;!important}
        .no-click {
  pointer-events: none;
  background-color: #e9ecef; /* warna Bootstrap untuk disabled */
  opacity: 1; /* tetap terlihat normal */
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
                                    <h5 class="card-title mb-0">Form Edit Butir Soal</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <input type="hidden" name="id_soal" value="<?= $butir_soal['id_soal'] ?>">
                                        <div class="mb-3" style="max-width:80px;">
                                            <label for="nomor_soal" class="form-label">Nomor Soal</label>
                                            <input type="number" class="form-control" id="nomor_soal" name="nomor_soal" value="<?= htmlspecialchars($butir_soal['nomer_soal']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tipe_soal" class="form-label">Tipe Soal</label>
                                            <select class="form-control no-click" id="tipe_soal" name="tipe_soal" onchange="showFields(this.value)" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Pilihan Ganda" <?= $butir_soal['tipe_soal'] == 'Pilihan Ganda' ? 'selected' : '' ?>>Pilihan Ganda</option>
                                                <option value="Pilihan Ganda Kompleks" <?= $butir_soal['tipe_soal'] == 'Pilihan Ganda Kompleks' ? 'selected' : '' ?>>Pilihan Ganda Kompleks</option>
                                                <option value="Benar/Salah" <?= $butir_soal['tipe_soal'] == 'Benar/Salah' ? 'selected' : '' ?>>Benar/Salah</option>
                                                <option value="Menjodohkan" <?= $butir_soal['tipe_soal'] == 'Menjodohkan' ? 'selected' : '' ?>>Menjodohkan</option>
                                                <option value="Uraian" <?= $butir_soal['tipe_soal'] == 'Uraian' ? 'selected' : '' ?>>Uraian</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                            <textarea class="form-control" id="pertanyaan" name="pertanyaan" required><?= htmlspecialchars($butir_soal['pertanyaan']) ?></textarea>
                                            <hr>
                                        </div>
                                        
                                        <!-- Fields for Pilihan Ganda -->
                                        <div id="pilihan-ganda-fields" class="d-none">
                                            <div class="mb-3">
                                                <label for="pilihan_1" class="form-label">Pilihan 1</label>
                                                <textarea class="form-control" id="pilihan_1" name="pilihan_1" required><?= htmlspecialchars($butir_soal['pilihan_1']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_1" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pilihan_2" class="form-label">Pilihan 2</label>
                                                <textarea class="form-control" id="pilihan_2" name="pilihan_2" required><?= htmlspecialchars($butir_soal['pilihan_2']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_2" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pilihan_3" class="form-label">Pilihan 3</label>
                                                <textarea class="form-control" id="pilihan_3" name="pilihan_3" required><?= htmlspecialchars($butir_soal['pilihan_3']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_3" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pilihan_4" class="form-label">Pilihan 4</label>
                                                <textarea class="form-control" id="pilihan_4" name="pilihan_4" required><?= htmlspecialchars($butir_soal['pilihan_4']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_4" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pilihan_4" class="form-label">Pilihan 5</label>
                                                <textarea class="form-control" id="pilihan_5" name="pilihan_5" required><?= htmlspecialchars($butir_soal['pilihan_5']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_5" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>

                                        </div>

                                        <!-- Fields for Pilihan Ganda Kompleks -->
                                        <div id="pilihan-ganda-kompleks-fields" class="d-none">
                                            <div class="mb-3">
                                                <label for="kompleks_1" class="form-label">Pilihan 1</label>
                                                <textarea type="text" class="form-control" id="kompleks_1" name="pilihan_1"><?= htmlspecialchars($butir_soal['pilihan_1']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_1"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kompleks_2" class="form-label">Pilihan 2</label>
                                                <textarea type="text" class="form-control" id="kompleks_2" name="pilihan_2"><?= htmlspecialchars($butir_soal['pilihan_2']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_2"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kompleks_3" class="form-label">Pilihan 3</label>
                                                <textarea type="text" class="form-control" id="kompleks_3" name="pilihan_3"><?= htmlspecialchars($butir_soal['pilihan_3']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_3"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kompleks_4" class="form-label">Pilihan 4</label>
                                                <textarea type="text" class="form-control" id="kompleks_4" name="pilihan_4"><?= htmlspecialchars($butir_soal['pilihan_4']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_4"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kompleks_4" class="form-label">Pilihan 5</label>
                                                <textarea type="text" class="form-control" id="kompleks_5" name="pilihan_5"><?= htmlspecialchars($butir_soal['pilihan_5']) ?></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_5"> Jawaban Benar
                                                <hr>
                                            </div>
                                        </div>

                                        <!-- Benar/Salah -->
                                        <div id="benar-salah-fields" class="d-none">
                                            <label>Pernyataan dan Jawaban</label><br><br>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_1" name="pilihan_1" placeholder="Pernyataan 1"><?= htmlspecialchars($butir_soal['pilihan_1']) ?></textarea>
                                               <label><input type="radio" name="jawaban_benar[0]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[0]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_2" name="pilihan_2" placeholder="Pernyataan 2"><?= htmlspecialchars($butir_soal['pilihan_2']) ?></textarea>
                                              <label><input type="radio" name="jawaban_benar[1]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[1]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_3" name="pilihan_3" placeholder="Pernyataan 3"><?= htmlspecialchars($butir_soal['pilihan_3']) ?></textarea>
                                              <label><input type="radio" name="jawaban_benar[2]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[2]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_4" name="pilihan_4" placeholder="Pernyataan 4"><?= htmlspecialchars($butir_soal['pilihan_4']) ?></textarea>
                                             <label><input type="radio" name="jawaban_benar[3]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[3]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                        </div>

                                        <!-- Menjodohkan -->
                                        <div id="menjodohkan-fields" class="d-none">
                                            <?php 
                                            $pasangan_data = [];
                                            if ($butir_soal['tipe_soal'] == 'Menjodohkan') {
                                                $pasangan_data = explode('|', $butir_soal['jawaban_benar']);
                                            }
                                            for ($i = 1; $i <= 8; $i++) : 
                                                $pair = isset($pasangan_data[$i-1]) ? explode(':', $pasangan_data[$i-1]) : ['', ''];
                                            ?>
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <textarea type="text" class="form-control" name="pasangan_soal[]" placeholder="Pilihan <?= $i ?>"><?= htmlspecialchars($pair[0]) ?></textarea>
                                                    </div>
                                                    <div class="col">
                                                        <textarea type="text" class="form-control" name="pasangan_jawaban[]" placeholder="Pasangan <?= $i ?>"><?= htmlspecialchars($pair[1]) ?></textarea>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>

                                        <!-- Uraian -->
                                        <div id="uraian-fields" class="d-none">
                                            <div class="mb-3">
                                                <label for="jawaban_benar" class="form-label">Jawaban Benar</label>
                                                <textarea class="form-control" name="jawaban_benar" rows="3" required><?= htmlspecialchars($butir_soal['jawaban_benar']) ?></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update</button>
                                        <a href="daftar_butir_soal.php?kode_soal=<?= htmlspecialchars($kode_soal) ?>" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i> Batal</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
<?php include '../inc/js.php'; ?>
    <script src="../assets/summernote/summernote-bs5.js"></script>
    <script>
        //function bersihkanHTML(html) {
            //return html.replace(/<(?!\/?(img|br)\b)[^>]*>/gi, '');
        //}

        $(document).ready(function () {
        var configEditor = {
        height: 300,
        callbacks: {
            // Hanya tempel teks polos (tanpa format)
            onPaste: function (e) {
                e.preventDefault();
                var clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                var text = clipboardData.getData('text/plain');
                document.execCommand("insertText", false, text);
            },

            // Upload gambar
            onImageUpload: function (files) {
                var editor = this;
                uploadImage(files[0], editor);
            },

            // Hapus file saat gambar dihapus dari editor
            onMediaDelete: function (target) {
                var imageUrl = target[0].src;
                $.ajax({
                    url: 'hapus_gambar_editor.php',
                    method: 'POST',
                    data: { src: imageUrl },
                    success: function (response) {
                        console.log('Gambar dihapus:', response);
                    },
                    error: function (err) {
                        console.error('Gagal menghapus gambar:', err);
                    }
                });
            },
             // Hapus <p><br></p> awal saat inisialisasi
            onInit: function () {
                var $editor = $(this).next('.note-editor').find('.note-editable');
                setTimeout(function () {
                    var content = $editor.html().trim();
                    if (content === '<p><br></p>' || content === '<p><br></p>\n') {
                        $editor.html('');
                    }
                }, 10);
            }

        },
        toolbar: [
            ['insert', ['picture']],
            ['view', ['codeview']]
        ]
    };

            $('#pertanyaan').summernote(configEditor);
            $('#pilihan_1, #pilihan_2, #pilihan_3, #pilihan_4, #pilihan_5, #kompleks_1, #kompleks_2, #kompleks_3, #kompleks_4, #kompleks_5, #bs_1, #bs_2, #bs_3, #bs_4').summernote({
                ...configEditor,
                height: 80
            });

            // Tampilkan fields sesuai tipe soal saat halaman dimuat
            showFields('<?= $butir_soal["tipe_soal"] ?>');
            
            // Set jawaban benar untuk soal yang sedang diedit
            setTimeout(() => {
                const tipeSoal = '<?= $butir_soal["tipe_soal"] ?>';
                const jawabanBenar = '<?= $butir_soal["jawaban_benar"] ?>';
                
                if (tipeSoal === 'Pilihan Ganda') {
                    const checkboxes = document.querySelectorAll('#pilihan-ganda-fields input[name="jawaban_benar[]"]');
                    checkboxes.forEach(cb => {
                        cb.checked = jawabanBenar.includes(cb.value);
                    });
                } else if (tipeSoal === 'Pilihan Ganda Kompleks') {
                    const checkboxes = document.querySelectorAll('#pilihan-ganda-kompleks-fields input[name="jawaban_benar[]"]');
                    const jawabanArray = jawabanBenar.split(',');
                    checkboxes.forEach(cb => {
                        cb.checked = jawabanArray.includes(cb.value);
                    });
                } else if (tipeSoal === 'Benar/Salah') {
                    const jawabanArray = jawabanBenar.split('|');
                    jawabanArray.forEach((val, i) => {
                        const radio = document.querySelector(`input[name="jawaban_benar[${i}]"][value="${val.trim()}"]`);
                        if (radio) radio.checked = true;
                    });
                }
            }, 500);
        });

        function uploadImage(file, editor) {
    let formData = new FormData();
    formData.append('file', file);

    $.ajax({
        url: 'uploadeditor.php',
        method: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            try {
                var url = JSON.parse(response).url;
                $(editor).summernote('insertImage', url, function($image) {
                    $image.attr('id', 'gbrsoal'); // Tambahkan atribut id
                });
            } catch (e) {
                console.error('Invalid response format');
            }
        },
        error: function(xhr, status, error) {
            console.error('Upload error:', error);
        }
    });
}


        function showFields(tipeSoal) {
            const fieldSets = {
                "Pilihan Ganda": "pilihan-ganda-fields",
                "Pilihan Ganda Kompleks": "pilihan-ganda-kompleks-fields",
                "Benar/Salah": "benar-salah-fields",
                "Menjodohkan": "menjodohkan-fields",
                "Uraian": "uraian-fields"
            };

            for (const [tipe, id] of Object.entries(fieldSets)) {
                const el = document.getElementById(id);
                const inputs = el.querySelectorAll('input, select, textarea');

                if (tipeSoal === tipe) {
                    el.classList.remove("d-none");
                    el.style.display = 'block';
                    inputs.forEach(i => {
                        i.disabled = false;
                        if (i.dataset.originalRequired === "true") {
                            i.required = true;
                        }
                    });
                } else {
                    el.classList.add("d-none");
                    el.style.display = 'none';
                    inputs.forEach(i => {
                        if (i.required) {
                            i.dataset.originalRequired = "true";
                        }
                        i.required = false;
                        i.disabled = true;
                    });
                }
            }
        }

        function checkOnlyOne(checkbox) {
            var checkboxes = document.getElementsByName('jawaban_benar[]');
            checkboxes.forEach((item) => {
                if (item !== checkbox) item.checked = false;
            });
        }

        function validateMenjodohkan() {
            const kiri = document.querySelectorAll('[name="pasangan_kiri[]"]');
            const kanan = document.querySelectorAll('[name="pasangan_kanan[]"]');

            for (let i = 0; i < kiri.length; i++) {
                const kiriVal = kiri[i].value.trim();
                const kananVal = kanan[i].value.trim();

                const hanyaSatuTerisi = (kiriVal && !kananVal) || (!kiriVal && kananVal);

                if (hanyaSatuTerisi) {
                    alert(`Baris pasangan ke-${i + 1} harus diisi kedua kolom atau dikosongkan.`);
                    return false;
                }
            }

            return true;
        }

        document.querySelector("form").addEventListener("submit", function(e) {
            const tipeSoal = document.getElementById("tipe_soal").value;
            if (tipeSoal === "Menjodohkan" && !validateMenjodohkan()) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
