<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
// Cek jika sudah login
check_login('admin');
include '../inc/dataadmin.php';

// Ambil data statistik dari database
$total_siswa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa"))['total'];
$total_soal = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM soal"))['total'];
$total_ujian = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM nilai"))['total'];

$ujian_terdekat = mysqli_query($koneksi, "SELECT * FROM soal WHERE tanggal > NOW() ORDER BY tanggal ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <?php include '../inc/css.php'; ?>
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
                                    <h5 class="card-title mb-0">Selamat datang di Dashboard Admin, <?php echo $nama_admin; ?>!</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Statistik Siswa -->
                                        <div class="col-md-4">
                                            <div class="card text-white bg-primary mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">Jumlah Siswa</h5>
                                                    <p class="card-text"><?php echo $total_siswa; ?> siswa terdaftar</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Statistik Soal -->
                                        <div class="col-md-4">
                                            <div class="card text-white bg-success mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">Jumlah Soal</h5>
                                                    <p class="card-text"><?php echo $total_soal; ?> soal tersedia</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Statistik Ujian -->
                                        <div class="col-md-4">
                                            <div class="card text-white bg-warning mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title text-white">Jumlah Ujian</h5>
                                                    <p class="card-text"><?php echo $total_ujian; ?> ujian Selesai</p>
                                                </div>
                                            </div>
                                        </div>

                                        
                                            <div class="col-md-4">
                                                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                    <h5><strong>Pengingat:</strong></h5><hr>
                                                        <?php while ($ujian = mysqli_fetch_assoc($ujian_terdekat)): ?>
                                                        <p><i class="far fa-calendar-check" aria-hidden="true"></i> Ujian <?php echo $ujian['kode_soal']; ?> akan dimulai pada <?php echo date('d M Y', strtotime($ujian['tanggal'])); ?></p>
                                                        <?php endwhile; ?>
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            </div>
                                      

                                    </div>

                                    
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
