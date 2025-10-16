<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$user_id = $_SESSION['admin_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_soal = mysqli_real_escape_string($koneksi, $_POST['kode_soal']);
    $nama_soal = mysqli_real_escape_string($koneksi, $_POST['nama_soal']);
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $tampilan_soal = mysqli_real_escape_string($koneksi, $_POST['tampilan_soal']);
    $waktu_ujian = mysqli_real_escape_string($koneksi, $_POST['waktu_ujian']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $exambrowser = mysqli_real_escape_string($koneksi, $_POST['exambrowser']);

    // Cek duplikasi kode_soal
    $cek_kode = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal = '$kode_soal'");
    if (mysqli_num_rows($cek_kode) > 0) {
        $_SESSION['error'] = 'Kode Soal Sudah Ada! Harap pilih kode soal yang lain.';
        header('Location: soal.php');
        exit;
    }

    $query = "INSERT INTO soal (kode_soal, nama_soal, mapel, kelas, waktu_ujian, tampilan_soal, tanggal, user_id, exambrowser)
              VALUES ('$kode_soal', '$nama_soal', '$mapel', '$kelas', '$waktu_ujian', '$tampilan_soal', '$tanggal', $user_id, $exambrowser)";

    if (mysqli_query($koneksi, $query)) {
        $_SESSION['success'] = 'Soal berhasil ditambahkan.';
    } else {
        $_SESSION['error'] = 'Gagal menambahkan soal: ' . mysqli_error($koneksi);
    }

    header('Location: soal.php');
    exit;
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
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Tambah Soal</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                        // Ambil data kelas dari tabel siswa secara DISTINCT
                                        $query_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC";
                                        $result_kelas = mysqli_query($koneksi, $query_kelas);
                                        ?>
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
                                            <select class="form-control" id="kelas" name="kelas" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php while ($kelas_row = mysqli_fetch_assoc($result_kelas)): ?>
                                                    <option value="<?php echo $kelas_row['kelas']; ?>">
                                                        <?php echo $kelas_row['kelas']; ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="waktu_ujian" class="form-label">Waktu Ujian (Menit)</label>
                                            <input type="number" class="form-control" id="waktu_ujian" name="waktu_ujian" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tampilan_soal" class="form-label">Tampilan Soal</label>
                                            <select class="form-control" id="tampilan_soal" name="tampilan_soal" required>
                                                <option value="">-- Pilih --</option>
                                                    <option value="Acak">Acak</option>
                                                    <option value="Urut">Urut</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Ujian</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal" required onclick="this.showPicker()">
                                        </div>
                                       <div class="mb-3">
                                            <label for="tampilan_soal" class="form-label">Menggunakan Exambrowser</label>
                                            <select class="form-control" id="tampilan_soal" name="exambrowser" required>
                                                <option value="1">Ya</option>
                                                <option value="0">Tidak</option> 
                                            </select>
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
