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

// Ambil data jumlah siswa ikut ujian per bulan
$rekap_query = mysqli_query($koneksi, "
    SELECT DATE_FORMAT(tanggal_ujian, '%Y-%m') AS bulan, COUNT(*) AS jumlah 
    FROM nilai 
    GROUP BY bulan 
    ORDER BY bulan ASC
");

$rekap_data = [];
while ($row = mysqli_fetch_assoc($rekap_query)) {
    $rekap_data['labels'][] = date('M Y', strtotime($row['bulan'] . '-01'));
    $rekap_data['jumlah'][] = $row['jumlah'];
}
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
                                            <div class="col-md-8">
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">Rekap Jumlah Siswa Mengikuti Ujian per Bulan</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chartRekapUjian" height="100"></canvas>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    const ctx = document.getElementById('chartRekapUjian').getContext('2d');
    const chartRekapUjian = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($rekap_data['labels']); ?>,
            datasets: [{
                label: 'Jumlah Siswa',
                data: <?php echo json_encode($rekap_data['jumlah']); ?>,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4, // Semakin tinggi nilainya (0–1), semakin bergelombang
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart' // efek animasi gelombang halus
            },
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
    
</script>
</body>
</html>
