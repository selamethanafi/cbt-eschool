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
// Ambil data rata-rata nilai per kode_soal
$kode_soal_query = mysqli_query($koneksi, "
    SELECT kode_soal, ROUND(AVG(nilai), 2) AS rata_rata 
    FROM nilai 
    GROUP BY kode_soal
");

$kode_soal_data = [];
while ($row = mysqli_fetch_assoc($kode_soal_query)) {
    $kode_soal_data['labels'][] = $row['kode_soal'];
    $kode_soal_data['rata'][] = $row['rata_rata'];
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
                                            <div class="card text-dark bg-white border border-primary mb-3">
                                                <div class="card-body bg-light">
                                                    <h5 class="card-title text-dark">Jumlah Siswa</h5>
                                                    <p class="card-text"><?php echo $total_siswa; ?> siswa terdaftar</p>
                                                    <a href="tambah_siswa.php" class="btn btn-outline-primary">
                                                        <i class="fas fa-plus"></i> Tambah Siswa
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Statistik Soal -->
                                        <div class="col-md-4">
                                            <div class="card text-dark bg-white border border-danger mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title text-dark">Jumlah Soal</h5>
                                                    <p class="card-text"><?php echo $total_soal; ?> soal tersedia</p>
                                                    <a href="tambah_soal.php" class="btn btn-outline-danger">
                                                        <i class="fas fa-plus"></i> Tambah Soal
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Statistik Ujian -->
                                        <div class="col-md-4">
                                            <div class="card text-dark bg-white border border-secondary mb-3">
                                                <div class="card-body">
                                                    <h5 class="card-title text-dark">Ujian</h5>
                                                    <p class="card-text"><?php echo $total_ujian; ?> Siswa Selesai</p>
                                                    <a href="hasil.php" class="btn btn-outline-secondary">
                                                        <i class="fas fa-edit"></i> Nilai
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="col-lg-8 md-6">
                                                <div class="card mb-3">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">Rekap Peserta Ujian</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <canvas id="chartRekapUjian" style="height: 400px; width: 100%;"></canvas>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Statistik Nilai per Kode Soal -->
                                        <div class="col-lg-4 md-6">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Statistik Nilai</h5>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="chartKodeSoal" style="height: 400px; width: 100%;"></canvas>
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
    // Grafik Statistik Nilai per Kode Soal
const ctxKode = document.getElementById('chartKodeSoal').getContext('2d');
const chartKodeSoal = new Chart(ctxKode, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($kode_soal_data['labels']); ?>,
        datasets: [{
            label: 'Rata-rata Nilai',
            data: <?php echo json_encode($kode_soal_data['rata']); ?>,
            backgroundColor: 'rgba(153, 102, 255, 0.2)', // warna soft
            borderWidth: 1,
            borderRadius: 20, // lebih bulat ujung bar
            barThickness: 10 // bar tipis
        }]
    },
    options: {
        indexAxis: 'y', // horizontal
        responsive: true,
        animation: {
            duration: 1200,
            easing: 'easeOutCubic' // animasi smooth modern
        },
        scales: {
            x: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    stepSize: 10
                },
                grid: {
                    drawBorder: false,
                    color: 'rgba(0,0,0,0.05)' // grid halus
                }
            },
            y: {
                ticks: {
                    autoSkip: false
                },
                grid: {
                    display: false // hilangkan garis grid Y
                }
            }
        },
        plugins: {
            legend: {
                display: false // buang legend supaya clean
            }
        }
    }
});

</script>
</body>
</html>