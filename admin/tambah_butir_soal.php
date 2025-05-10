<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if (empty($_GET['kode_soal']) || empty($_GET['nomer_baru'])) {
    header("Location: soal.php"); // Ganti dengan URL halaman yang sesuai
    exit();
}

$kode_soal = $_GET['kode_soal'];
$nomer_baru = $_GET['nomer_baru'];


// Ambil data soal
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['pertanyaan']) || empty($_POST['tipe_soal']) || empty($_POST['nomer_soal'])) {
        die("Harap isi semua field wajib");
    }

    $pertanyaan = mysqli_real_escape_string($koneksi, $_POST['pertanyaan']);
    $tipe_soal = mysqli_real_escape_string($koneksi, $_POST['tipe_soal']);
    $nomer_soal = mysqli_real_escape_string($koneksi, $_POST['nomer_soal']);

    // Check if nomer_soal already exists for the given kode_soal
    $query_check = "SELECT * FROM butir_soal WHERE kode_soal = '$kode_soal' AND nomer_soal = '$nomer_soal'";
    $result_check = mysqli_query($koneksi, $query_check);

    if (mysqli_num_rows($result_check) > 0) {
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
                    text: "Harap pilih nomor soal yang lain.",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>';
        exit;
    }

    $query = "";

    if ($tipe_soal == 'Pilihan Ganda' || $tipe_soal == 'Pilihan Ganda Kompleks') {
        $pilihan_1 = mysqli_real_escape_string($koneksi, $_POST['pilihan_1']);
        $pilihan_2 = mysqli_real_escape_string($koneksi, $_POST['pilihan_2']);
        $pilihan_3 = mysqli_real_escape_string($koneksi, $_POST['pilihan_3']);
        $pilihan_4 = mysqli_real_escape_string($koneksi, $_POST['pilihan_4']);

        if (!isset($_POST['jawaban_benar']) || count($_POST['jawaban_benar']) == 0) {
            die("Harap pilih minimal satu jawaban benar");
        }

        $jawaban_benar = implode(",", $_POST['jawaban_benar']);

        $query = "INSERT INTO butir_soal (kode_soal, nomer_soal, pertanyaan, tipe_soal,
                  pilihan_1, pilihan_2, pilihan_3, pilihan_4, jawaban_benar, status_soal)
                  VALUES ('$kode_soal', '$nomer_soal', '$pertanyaan', '$tipe_soal',
                  '$pilihan_1', '$pilihan_2', '$pilihan_3', '$pilihan_4', '$jawaban_benar', 'Aktif')";

    } elseif ($tipe_soal == 'Benar/Salah') {
        if (empty($_POST['jawaban_benar'])) {
            die("Harap pilih jawaban benar");
        }
        $jawaban_benar = implode("|", $_POST['jawaban_benar']);
        $pilihan_1 = mysqli_real_escape_string($koneksi, $_POST['pilihan_1']);
        $pilihan_2 = mysqli_real_escape_string($koneksi, $_POST['pilihan_2']);
        $pilihan_3 = mysqli_real_escape_string($koneksi, $_POST['pilihan_3']);
        $pilihan_4 = mysqli_real_escape_string($koneksi, $_POST['pilihan_4']);

        $query = "INSERT INTO butir_soal (kode_soal, nomer_soal, pertanyaan, tipe_soal,
                  pilihan_1, pilihan_2, pilihan_3, pilihan_4, jawaban_benar, status_soal)
                  VALUES ('$kode_soal', '$nomer_soal', '$pertanyaan', '$tipe_soal',
                  '$pilihan_1', '$pilihan_2', '$pilihan_3', '$pilihan_4', '$jawaban_benar', 'Aktif')";

    } elseif ($tipe_soal == 'Menjodohkan') {
        $pasangan_valid = false;
        $pasangan_data = [];

        foreach ($_POST['pasangan_soal'] as $i => $soal) {
            $jawaban = $_POST['pasangan_jawaban'][$i];
            if (!empty($soal) && !empty($jawaban)) {
                $soal = mysqli_real_escape_string($koneksi, $soal);
                $jawaban = mysqli_real_escape_string($koneksi, $jawaban);
                $pasangan_data[] = "$soal:$jawaban";
                $pasangan_valid = true;
            }
        }

        if (!$pasangan_valid) {
            die("Harap isi minimal satu pasangan soal dan jawaban");
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

        $query = "INSERT INTO butir_soal (kode_soal, nomer_soal, pertanyaan, tipe_soal,
                  jawaban_benar, status_soal)
                  VALUES ('$kode_soal', '$nomer_soal', '$pertanyaan', '$tipe_soal',
                  '$jawaban_benar', 'Aktif')";
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
    <title>Tambah Butir Soal</title>
    <?php include '../inc/css.php'; ?>
    <script src="../assets/js/jquery-3.6.0.min.js"></script>
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
            height: auto;
            object-fit: contain;
        }
        label.note-form-label{display:none;!important}
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
                                    <h5 class="card-title mb-0">Form Tambah Butir Soal</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="mb-3" style="max-width:80px;">
                                            <label for="nomer_soal" class="form-label">Nomor Soal</label>
                                            <input type="number" class="form-control" id="nomer_soal" name="nomer_soal" value="<?= $nomer_baru ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tipe_soal" class="form-label">Tipe Soal</label>
                                            <select class="form-control" id="tipe_soal" name="tipe_soal" onchange="showFields(this.value)" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Pilihan Ganda">Pilihan Ganda</option>
                                                <option value="Pilihan Ganda Kompleks">Pilihan Ganda Kompleks</option>
                                                <option value="Benar/Salah">Benar/Salah</option>
                                                <option value="Menjodohkan">Menjodohkan</option>
                                                <option value="Uraian">Uraian</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pertanyaan" class="form-label">Pertanyaan</label>
                                            <textarea class="form-control" id="pertanyaan" name="pertanyaan" required></textarea>
                                            <hr>
                                        </div>
                                        
                                        <!-- Fields for Pilihan Ganda -->
                                        <div id="pilihan-ganda-fields" class="d-none">
                                            <div class="mb-3">
                                                <label for="pilihan_1" class="form-label">Pilihan 1</label>
                                                <textarea class="form-control" id="pilihan_1" name="pilihan_1" required></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_1" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pilihan_2" class="form-label">Pilihan 2</label>
                                                <textarea class="form-control" id="pilihan_2" name="pilihan_2" required></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_2" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pilihan_3" class="form-label">Pilihan 3</label>
                                                <textarea class="form-control" id="pilihan_3" name="pilihan_3" required></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_3" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="pilihan_4" class="form-label">Pilihan 4</label>
                                                <textarea class="form-control" id="pilihan_4" name="pilihan_4" required></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_4" onclick="checkOnlyOne(this)"> Jawaban Benar
                                                <hr>
                                            </div>
                                        </div>

                                        <!-- Fields for Pilihan Ganda Kompleks -->
                                        <div id="pilihan-ganda-kompleks-fields" class="d-none">
                                            <div class="mb-3">
                                                <label for="kompleks_1" class="form-label">Pilihan 1</label>
                                                <textarea type="text" class="form-control" id="kompleks_1" name="pilihan_1"></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_1"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kompleks_2" class="form-label">Pilihan 2</label>
                                                <textarea type="text" class="form-control" id="kompleks_2" name="pilihan_2"></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_2"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kompleks_3" class="form-label">Pilihan 3</label>
                                                <textarea type="text" class="form-control" id="kompleks_3" name="pilihan_3"></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_3"> Jawaban Benar
                                                <hr>
                                            </div>
                                            <div class="mb-3">
                                                <label for="kompleks_4" class="form-label">Pilihan 4</label>
                                                <textarea type="text" class="form-control" id="kompleks_4" name="pilihan_4"></textarea>
                                                <input type="checkbox" name="jawaban_benar[]" value="pilihan_4"> Jawaban Benar
                                                <hr>
                                            </div>
                                        </div>

                                        <!-- Benar/Salah -->
                                        <div id="benar-salah-fields" class="d-none">
                                            <label>Pernyataan dan Jawaban</label><br><br>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_1" name="pilihan_1" placeholder="Pernyataan 1"></textarea>
                                               <label><input type="radio" name="jawaban_benar[0]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[0]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_2" name="pilihan_2" placeholder="Pernyataan 2"></textarea>
                                              <label><input type="radio" name="jawaban_benar[1]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[1]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_3" name="pilihan_3" placeholder="Pernyataan 3"></textarea>
                                              <label><input type="radio" name="jawaban_benar[2]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[2]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                            <div class="form-group">
                                            <textarea type="text" class="form-control mb-1" id="bs_4" name="pilihan_4" placeholder="Pernyataan 4"></textarea>
                                             <label><input type="radio" name="jawaban_benar[3]" value="Benar"> Benar</label>
                                                <label><input type="radio" name="jawaban_benar[3]" value="Salah"> Salah</label>
                                                <hr><br><br>
                                            </div>
                                        </div>

                                        <!-- Menjodohkan -->
                                        <div id="menjodohkan-fields" class="d-none">
                                            <?php for ($i = 1; $i <= 8; $i++) : ?>
                                                <div class="row mb-2">
                                                    <div class="col">
                                                        <textarea type="text" class="form-control" name="pasangan_soal[]" placeholder="Pilihan <?= $i ?>"></textarea>
                                                    </div>
                                                    <div class="col">
                                                        <textarea type="text" class="form-control" name="pasangan_jawaban[]" placeholder="Pasangan <?= $i ?>"></textarea>
                                                    </div>
                                                </div>
                                            <?php endfor; ?>
                                        </div>

                                        <!-- Uraian -->
                                        <div id="uraian-fields" class="d-none">
                                            <div class="mb-3">
                                                <label for="jawaban_benar" class="form-label">Jawaban Benar</label>
                                                <textarea class="form-control" name="jawaban_benar" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                                        <a href="daftar_butir_soal.php?kode_soal=<?= htmlspecialchars($data_soal['kode_soal']) ?>" class="btn btn-danger"><i class="fa fa-refresh" aria-hidden="true"></i> Batal</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="../assets/bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/adminkit/static/js/app.js"></script>
    <script src="../assets/js/sweetalert.js"></script>
    <script src="../assets/datatables/datatables.js"></script>
    <script src="../assets/summernote/summernote-bs5.js"></script>
    <script>
    function bersihkanHTML(html) {
        return html.replace(/<(?!\/?(img|br)\b)[^>]*>/gi, '');
    }

    $(document).ready(function () {
        var configEditor = {
            height: 300,
            callbacks: {
                onImageUpload: function (files) {
                    var editor = this;
                    uploadImage(files[0], editor);
                },
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
                onChange: function (contents, $editable) {
                    let bersih = bersihkanHTML(contents);
                    if (bersih !== contents) {
                        $(this).summernote('code', bersih);
                    }
                }
            },
            toolbar: [
                ['insert', ['picture']],
                ['view', ['codeview']]
            ]
        };

        $('#pertanyaan').summernote(configEditor);

        $('#pilihan_1, #pilihan_2, #pilihan_3, #pilihan_4, #kompleks_1, #kompleks_2, #kompleks_3, #kompleks_4, #bs_1, #bs_2, #bs_3, #bs_4').summernote({
            ...configEditor,
            height: 80
        });
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
            success: function (response) {
                try {
                    var url = JSON.parse(response).url;
                    $(editor).summernote('insertImage', url, function ($image) {
                        $image.attr('id', 'gbrsoal'); // Tambahkan atribut id jika perlu
                    });
                } catch (e) {
                    console.error('Invalid response format:', e);
                }
            },
            error: function (xhr, status, error) {
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
    </script>
</body>
</html>