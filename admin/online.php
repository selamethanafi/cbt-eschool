<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

$threshold = date('Y-m-d H:i:s', strtotime('-5 minutes'));
$query = "SELECT nama_siswa, kelas, rombel, last_activity, page_url FROM siswa WHERE last_activity >= '$threshold' ORDER BY nama_siswa ASC";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Who's online</title>
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
                                    <h5 class="card-title mb-0">Who's online</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="tabel-online">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama</th>
                                                    <th>Kelas</th>
                                                    <th>Rombel</th>
                                                    <th>Terakhir Aktif</th>
                                                    <th>Halaman Terakhir</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    $nomor = 1; // Inisialisasi nomor urut
                                                    while ($row = mysqli_fetch_assoc($result)): 
                                                    $is_online = isset($row['last_activity']) && $row['last_activity'] >= $threshold;
                                                    $status = $is_online 
                                                        ? '<span class="badge bg-success">Online</span>' 
                                                        : '<span class="badge bg-secondary">Offline</span>';
                                                ?>
                                                <tr>
                                                    <td><?= $nomor++ ?></td> <!-- Menampilkan nomor urut -->
                                                    <td><?= htmlspecialchars($row['nama_siswa']) ?></td>
                                                    <td><?= htmlspecialchars($row['kelas']) ?></td>
                                                    <td><?= htmlspecialchars($row['rombel']) ?></td>
                                                    <td><?= $row['last_activity'] ?? '-' ?></td>
                                                    <td><?= htmlspecialchars($row['page_url']) ?></td>
                                                    <td><?= $status ?></td>
                                                </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
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
    $(document).ready(function () {
        $('#tabel-online').DataTable();
    });
</script>

</body>

</html>