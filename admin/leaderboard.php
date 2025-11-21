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
    <title>Mini Games Leaderboard</title>
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
                                <div class="card-header d-flex justify-content-between">
                                    <strong><i class="fas fa-comments me-1"></i> Mini Games Leaderboard</strong>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="deleteAlltop()"><i
                                            class="fas fa-history"></i> Reset</button>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-6 md-4">
                                            <div class="card card-minimal h-100">
                                                <div class="card-body">
                                                    <div class="card-title">Leaderboard -
                                                        <?= ucfirst(str_replace('_', ' ', htmlspecialchars($game))) ?>
                                                    </div>
                                                    <p class="text-muted small mb-3">10 Skor Tertinggi</p>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-sm mb-0">
                                                            <thead class="table-secondary">
                                                                <tr>
                                                                    <th class="text-center">#</th>
                                                                    <th>Nama</th>
                                                                    <th>Skor</th>
                                                                    <th>Waktu</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                            $stmt = $koneksi->prepare("
                                                                SELECT siswa.nama_siswa, skor_game.skor, skor_game.waktu
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
                                                                    <td>
                                                                        <?php 
                                                                            $waktu = new DateTime($row['waktu']);
                                                                            echo $waktu->format('d M Y, H:i'); // Output modern
                                                                        ?>
                                                                    </td>
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
                                                                    <th>Waktu</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php 
                                                                $stmt2 = $koneksi->prepare("
                                                                    SELECT siswa.nama_siswa, skor_game.skor, skor_game.waktu
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
                                                                    if ($rank2 == 1) $icon2 = '<i class="fas fa-medal text-warning"></i>';
                                                                    elseif ($rank2 == 2) $icon2 = '<i class="fas fa-medal text-secondary"></i>';
                                                                    elseif ($rank2 == 3) $icon2 = '<i class="fas fa-medal" style="color: #cd7f32;"></i>';
                                                                ?>
                                                                <tr>
                                                                    <td class="text-center"><?= $icon2 ?: $rank2 ?></td>
                                                                    <td><?= htmlspecialchars($row2['nama_siswa']) ?>
                                                                    </td>
                                                                    <td><?= (int)$row2['skor'] ?></td>
                                                                    <td>
                                                                        <?php 
                                                                            $waktu = new DateTime($row2['waktu']);
                                                                            echo $waktu->format('d M Y, H:i'); // Output modern
                                                                        ?>
                                                                    </td>
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
    <script>
    function deleteAlltop() {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: 'Ketik <strong>HAPUS</strong> untuk menghapus semua leaderboard.',
            input: 'text',
            inputPlaceholder: 'Ketik HAPUS di sini',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            confirmButtonColor: '#d33',
            cancelButtonText: 'Batal',
            preConfirm: (inputValue) => {
                if (inputValue !== 'HAPUS') {
                    Swal.showValidationMessage('Anda harus mengetik "HAPUS" dengan benar');
                }
                return inputValue;
            }
        }).then((result) => {
            if (result.isConfirmed && result.value === 'HAPUS') {
                // Kirim ke server tanpa password
                fetch('delete_all_leaderboard.php', {
                        method: 'POST'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'ok') {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message || 'Semua data telah dihapus.',
                                icon: 'success'
                            }).then(() => location.reload());
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: data.message || 'Penghapusan gagal.',
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Kesalahan',
                            text: 'Terjadi kesalahan saat menghapus.',
                            icon: 'error'
                        });
                        console.error(error);
                    });
            }
        });
    }
    </script>
</body>

</html>