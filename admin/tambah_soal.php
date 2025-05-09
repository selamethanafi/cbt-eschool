<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_soal = mysqli_real_escape_string($koneksi, $_POST['kode_soal']);
    $nama_soal = mysqli_real_escape_string($koneksi, $_POST['nama_soal']);
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $waktu_ujian = mysqli_real_escape_string($koneksi, $_POST['waktu_ujian']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

    // Cek duplikasi kode_soal
    $cek_kode = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal = '$kode_soal'");
if (mysqli_num_rows($cek_kode) > 0) {
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
                    title: "Kode Soal Sudah Ada!",
                    text: "Harap pilih Kode soal yang lain.",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
        </html>';
        exit;
}
    $query = "INSERT INTO soal (kode_soal, nama_soal, mapel, kelas, waktu_ujian, tanggal)
              VALUES ('$kode_soal', '$nama_soal', '$mapel', '$kelas', '$waktu_ujian', '$tanggal')";

    if (mysqli_query($koneksi, $query)) {
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
                    icon: "success",
                    title: "Sukses!",
                    text: "Soal Berhasil Ditambahkan.",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "soal.php";
                });
            </script>
        </body>
        </html>';
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Soal</title>
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
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Tambah Soal</h5>
                                </div>
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="mb-3">
                                            <label for="kode_soal" class="form-label">Kode Soal</label>
                                            <input type="text" class="form-control" id="kode_soal" name="kode_soal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama_soal" class="form-label">Nama Soal</label>
                                            <input type="text" class="form-control" id="nama_soal" name="nama_soal" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mapel" class="form-label">Mata Pelajaran</label>
                                            <input type="text" class="form-control" id="mapel" name="mapel" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kelas" class="form-label">Kelas</label>
                                            <input type="text" class="form-control" id="kelas" name="kelas" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="waktu_ujian" class="form-label">Waktu Ujian (Menit)</label>
                                            <input type="number" class="form-control" id="waktu_ujian" name="waktu_ujian" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Ujian</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal" required onclick="this.showPicker()">
                                        </div>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
										<a href="soal.php" class="btn btn-danger">Batal</a>
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
</body>
</html>
