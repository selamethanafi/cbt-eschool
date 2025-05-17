<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';

// Pastikan ID soal ada di URL
if (!isset($_GET['id_soal'])) {
    header('Location: soal.php');
    exit();
}

$id_soal = $_GET['id_soal'];

// Ambil data soal berdasarkan ID
$query = "SELECT * FROM soal WHERE id_soal = '$id_soal'";
$result = mysqli_query($koneksi, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Soal tidak ditemukan!";
    exit();
}

// ✅ Jika soal status = aktif, tampilkan SweetAlert + redirect
if ($row['status'] == 'Aktif') {
    $_SESSION['warning_message'] = 'Soal ini sudah aktif dan tidak bisa diedit!';
    header('Location: soal.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $kode_soal = mysqli_real_escape_string($koneksi, $_POST['kode_soal']);
    $nama_soal = mysqli_real_escape_string($koneksi, $_POST['nama_soal']);
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $waktu_ujian = mysqli_real_escape_string($koneksi, $_POST['waktu_ujian']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

    // Update data soal
    $update_query = "UPDATE soal SET 
                        kode_soal = '$kode_soal', 
                        nama_soal = '$nama_soal',
                        mapel = '$mapel', 
                        kelas = '$kelas', 
                        waktu_ujian = '$waktu_ujian', 
                        tanggal = '$tanggal'
                    WHERE id_soal = '$id_soal'";

    if (mysqli_query($koneksi, $update_query)) {
        $_SESSION['success_message'] = 'Data soal berhasil diupdate!';
        header('Location: soal.php');
        exit();
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
    <title>Edit Soal</title>
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
                                    <h5 class="card-title mb-0">Edit Soal</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                        // Ambil data kelas dari tabel siswa secara DISTINCT
                                        $query_kelas = "SELECT DISTINCT kelas FROM siswa ORDER BY kelas ASC";
                                        $result_kelas = mysqli_query($koneksi, $query_kelas);
                                        ?>
                                    <form method="POST">
                                        <div class="mb-3">
                                            <h2>Kode Soal : <?php echo $row['kode_soal']; ?></h2>
                                            <input type="hidden" class="form-control" id="kode_soal" name="kode_soal" value="<?php echo $row['kode_soal']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nama_soal" class="form-label">Nama Soal</label>
                                            <input type="text" class="form-control" id="nama_soal" name="nama_soal" value="<?php echo $row['nama_soal']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="mapel" class="form-label">Mata Pelajaran</label>
                                            <input type="text" class="form-control" id="mapel" name="mapel" value="<?php echo $row['mapel']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="kelas" class="form-label">Kelas</label>
                                            <select class="form-control" id="kelas" name="kelas" required>
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php while ($kelas_row = mysqli_fetch_assoc($result_kelas)): ?>
                                                    <option value="<?php echo $kelas_row['kelas']; ?>" <?php echo ($kelas_row['kelas'] == $row['kelas']) ? 'selected' : ''; ?>>
                                                        <?php echo $kelas_row['kelas']; ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="waktu_ujian" class="form-label">Waktu Ujian (Menit)</label>
                                            <input type="number" class="form-control" id="waktu_ujian" name="waktu_ujian" value="<?php echo $row['waktu_ujian']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Ujian</label>
                                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $row['tanggal']; ?>" required onclick="this.showPicker()">
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