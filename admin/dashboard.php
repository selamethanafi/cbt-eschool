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
// Ambil 10 kode soal dengan rata-rata tertinggi
$kode_soal_query = mysqli_query($koneksi, "
    SELECT kode_soal, ROUND(AVG(nilai + IFNULL(nilai_uraian, 0)), 2) AS rata_rata 
    FROM nilai 
    GROUP BY kode_soal 
    ORDER BY rata_rata DESC 
    LIMIT 10
");

$kode_soal_data = ['labels' => [], 'rata' => []];
while ($row = mysqli_fetch_assoc($kode_soal_query)) {
    $kode_soal_data['labels'][] = $row['kode_soal'];
    $kode_soal_data['rata'][] = $row['rata_rata'];
}

// Ambil 10 siswa dengan rata-rata nilai akhir tertinggi
$top_siswa_query = mysqli_query($koneksi, "
    SELECT siswa.nama_siswa AS nama, 
           COUNT(*) AS jumlah_ujian,
           ROUND(AVG(nilai + IFNULL(nilai_uraian, 0)), 2) AS rata 
    FROM nilai 
    JOIN siswa ON nilai.id_siswa = siswa.id_siswa 
    GROUP BY nilai.id_siswa 
    ORDER BY rata DESC 
    LIMIT 10
") or die("Query error: " . mysqli_error($koneksi));

$top_siswa_data = ['labels' => [], 'rata' => [], 'ujian' => []];
while ($row = mysqli_fetch_assoc($top_siswa_query)) {
    $top_siswa_data['labels'][] = $row['nama'];
    $top_siswa_data['rata'][] = $row['rata'];
    $top_siswa_data['ujian'][] = $row['jumlah_ujian'];
}
$game = $_GET['game'] ?? 'math_puzzle';
$game2 = $_GET['game'] ?? 'scramble';
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
                                    <h5 class="card-title mb-0">Selamat datang di Dashboard Admin,
                                        <?php echo $nama_admin; ?>!</h5>
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
                                        <div class="col-lg-4 md-4">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">10 Siswa Nilai Tertinggi</h5>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="chartTopSiswa"
                                                        style="height: 400px; width: 100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 md-4">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Rekap Peserta Ujian</h5>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="chartRekapUjian"
                                                        style="height: 400px; width: 100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 md-4">
                                            <div class="card mb-3">
                                                <div class="card-header">
                                                    <h5 class="card-title mb-0">Statistik Nilai</h5>
                                                </div>
                                                <div class="card-body">
                                                    <canvas id="chartKodeSoal"
                                                        style="height: 400px; width: 100%;"></canvas>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 md-4">
                                            <div class="card card-minimal h-100">
                                                <div class="card-body">
                                                    <div class="card-title">Leaderboard - <?= ucfirst(str_replace('_', ' ', htmlspecialchars($game))) ?></div>
                                                    <p class="text-muted small mb-3">10 Skor Tertinggi</p>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-sm mb-0">
                                                            <thead class="table-secondary">
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Nama</th>
                                                                    <th>Skor</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php 
                                                            $stmt = $koneksi->prepare("
                                                                SELECT siswa.nama_siswa, skor_game.skor
                                                                FROM skor_game
                                                                JOIN siswa ON skor_game.id_siswa = siswa.id_siswa
                                                                WHERE skor_game.nama_game = ?
                                                                ORDER BY skor_game.skor DESC
                                                                LIMIT 10
                                                            ");
                                                            $stmt->bind_param("s", $game);
                                                            $stmt->execute();
                                                            $res = $stmt->get_result();
                                                            $rank = 1;
                                                            while ($row = $res->fetch_assoc()):
                                                                $icon = '';
                                                                if ($rank == 1) $icon = '<i class="fas fa-medal text-warning"></i>'; // Gold
                                                                elseif ($rank == 2) $icon = '<i class="fas fa-medal text-secondary"></i>'; // Silver
                                                                elseif ($rank == 3) $icon = '<i class="fas fa-medal" style="color: #cd7f32;"></i>'; // Bronze
                                                            ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $icon ?: $rank ?></td>
                                                                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                                                    <td><?= (int)$row['skor'] ?></td>
                                                                </tr>
                                                            <?php 
                                                                $rank++;
                                                            endwhile; 
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 md-4">
                                            <div class="card card-minimal h-100">
                                                <div class="card-body">
                                                    <div class="card-title">Leaderboard - Scramble Text</div>
                                                    <p class="text-muted small mb-3">10 Skor Tertinggi</p>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-sm mb-0">
                                                            <thead class="table-secondary">
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Nama</th>
                                                                    <th>Skor</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php 
                                                            $stmt2 = $koneksi->prepare("
                                                                SELECT siswa.nama_siswa, skor_game.skor
                                                                FROM skor_game
                                                                JOIN siswa ON skor_game.id_siswa = siswa.id_siswa
                                                                WHERE skor_game.nama_game = ?
                                                                ORDER BY skor_game.skor DESC
                                                                LIMIT 10
                                                            ");
                                                            $stmt2->bind_param("s", $game2);
                                                            $stmt2->execute();
                                                            $res2 = $stmt2->get_result();
                                                            $rank2 = 1;
                                                            while ($row2 = $res2->fetch_assoc()):
                                                                $icon2 = '';
                                                                if ($rank2 == 1) $icon2 = '<i class="fas fa-medal text-warning"></i>'; // Gold
                                                                elseif ($rank2 == 2) $icon2 = '<i class="fas fa-medal text-secondary"></i>'; // Silver
                                                                elseif ($rank2 == 3) $icon2 = '<i class="fas fa-medal" style="color: #cd7f32;"></i>'; // Bronze
                                                            ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $icon2 ?: $rank2 ?></td>
                                                                    <td><?= htmlspecialchars($row2['nama_siswa']) ?></td>
                                                                    <td><?= (int)$row2['skor'] ?></td>
                                                                </tr>
                                                            <?php 
                                                                $rank2++;
                                                            endwhile; 
                                                            ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
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
    <script src="../assets/js/chart.js"></script>
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

    // Buat gradient linear (dari kiri ke kanan)
    const gradientBlue = ctxKode.createLinearGradient(0, 0, 400, 0);
    gradientBlue.addColorStop(0, 'rgba(255, 0, 200, 0.6)');
    gradientBlue.addColorStop(1, 'rgba(0, 200, 255, 0.9)');
    const chartKodeSoal = new Chart(ctxKode, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($kode_soal_data['labels']); ?>,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: <?php echo json_encode($kode_soal_data['rata']); ?>,
                backgroundColor: gradientBlue,
                borderWidth: 0,
                borderRadius: 0, // lebih bulat ujung bar
                barThickness: 5 // bar tipis
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

    // Grafik 10 Siswa dengan Rata-rata Nilai Tertinggi
    // Grafik 10 Siswa dengan Rata-rata Nilai Tertinggi (Doughnut Chart)
    const ctxTop = document.getElementById('chartTopSiswa').getContext('2d');
    const chartTopSiswa = new Chart(ctxTop, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($top_siswa_data['labels']); ?>,
            datasets: [{
                label: 'Rata-rata Nilai',
                data: <?php echo json_encode($top_siswa_data['rata']); ?>,
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                    '#9966FF', '#FF9F40', '#00C49F', '#FF6666',
                    '#6699FF', '#FFCC99'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const index = context.dataIndex;
                            const nama = context.label;
                            const nilai = context.dataset.data[index];
                            const jumlahUjian = <?php echo json_encode($top_siswa_data['ujian']); ?>[index];
                            return `${nama}: ${nilai} (Ujian: ${jumlahUjian}x)`;
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                },
                title: {
                    display: true,
                    text: 'Top 10 Siswa (Rata-rata Nilai)'
                }
            },
            animation: {
                animateRotate: true,
                duration: 1500
            }
        }
    });
    </script>
</body>

</html>