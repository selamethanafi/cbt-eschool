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
    $tahun = mysqli_real_escape_string($koneksi, $_POST['tahun']);
    $semester = mysqli_real_escape_string($koneksi, $_POST['semester']);
    $nama_soal = mysqli_real_escape_string($koneksi, $_POST['nama_soal']);
    $mapel = mysqli_real_escape_string($koneksi, $_POST['mapel']);
    $kelas = mysqli_real_escape_string($koneksi, $_POST['kelas']);
    $tampilan_soal = mysqli_real_escape_string($koneksi, $_POST['tampilan_soal']);
    $waktu_ujian = mysqli_real_escape_string($koneksi, $_POST['waktu_ujian']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $exambrowser = mysqli_real_escape_string($koneksi, $_POST['exambrowser']);
    // Update data soal
    $update_query = "UPDATE soal SET 
                        kode_soal = '$kode_soal', 
                        nama_soal = '$nama_soal',
                        mapel = '$mapel', 
                        kelas = '$kelas', 
                        tampilan_soal = '$tampilan_soal', 
                        waktu_ujian = '$waktu_ujian', 
                        user_id = '$id_user',
                        tanggal = '$tanggal',
                        exambrowser = '$exambrowser'
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
                        <div class="col-12 col-lg-8">
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
                                            <label for="tampilan_soal" class="form-label">Tahun</label>
                                            <input type="number" class="form-control" id="tampilan_soal" name="tahun" value="<?php echo $row['tahun'];?>" required>
                                        </div>
	                                  <div class="mb-3">
                                            <label for="tampilan_soal" class="form-label">Semester</label>
                                            <input type="number" class="form-control" id="tampilan_soal" name="semester" value="<?php echo $row['semester'];?>" required>

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
                                                     <option value="<?php echo $row['kelas']; ?>"> <?php echo $row['kelas'];?></option>

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
                                            <label for="tampilan_soal" class="form-label">Tampilan Soal</label>
                                            <select class="form-control" id="tampilan_soal" name="tampilan_soal" required>
                                                <option value="<?php echo $row['tampilan_soal']; ?>"><?php echo $row['tampilan_soal']; ?></option>
                                                    <option value="Acak">Acak</option>
                                                    <option value="Urut">Urut</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tanggal" class="form-label">Tanggal Ujian</label>
                                            <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" value="<?php echo $row['tanggal']; ?>" required onclick="this.showPicker()">
                                        </div>

                                       <div class="mb-3">
                                            <label for="tampilan_soal" class="form-label">Menggunakan Exambrowser</label>
                                            <select class="form-control" id="tampilan_soal" name="exambrowser" required>
                                            <?php
                                            if($row['exambrowser'] == '1')
                                            {?>
                                                <option value="<?php echo $row['exambrowser']; ?>">Ya</option>
                                                <option value="0">Tidak</option>     
                                                <?php                                          
                                                }
                                                else
                                                
                                            {?>
                                                <option value="<?php echo $row['exambrowser']; ?>">Tidak</option>
                                                <option value="1">Ya</option>     
                                                <?php                                          
                                                }
?>                                                 
                                            </select>
                                        </div>
                                       <div class="mb-3">
                                            <label for="tampilan_soal" class="form-label">Guru Pengampu</label>
                                            <select class="form-control" id="tampilan_soal" name="id_user" required>
                                            <?php
                                            $user_idx = $row['user_id'];
                                            $ta = mysqli_query($koneksi, "select * from `admins` where `id` = '$user_idx'");
                                            $da = mysqli_fetch_assoc($ta);
                                            $nama_guru = $da['nama_admin'];
                                            ?>
                                                <option value="<?php echo $user_idx; ?>"><?php echo $nama_guru; ?></option>
                                            <?php
                                            $ta = mysqli_query($koneksi, "select * from `admins` where `id` > 1");
                                            $da = mysqli_fetch_assoc($ta);
                                            while($da = mysqli_fetch_assoc($ta))
                                            {?>
                                                <option value="<?php echo $da['id']; ?>"><?php echo $da['nama_admin']; ?></option>
                                                <?php                                          
                                                }
?>                                                 
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

