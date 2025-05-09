<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';

// Cek jika sudah login
check_login('admin');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Aktivitas Siswa</title>
<?php include '../inc/css.php'; ?>
</head>

<body>
    <div class="wrapper">

    <?php include 'sidebar.php'; ?>

<div class="main">
    <?php include 'navbar.php'; ?>
            <!-- /Navbar -->

            <!-- Content -->
            <main class="content">
                <div class="container-fluid p-0">

                    <h1 class="h3 mb-3">Monitoring Aktivitas Siswa</h1>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Daftar Siswa Aktif</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>Status</th>
                                                <th>Waktu Login</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Andi Saputra</td>
                                                <td><span class="badge bg-success">Aktif</span></td>
                                                <td>2025-05-05 08:30</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Siti Rahmawati</td>
                                                <td><span class="badge bg-danger">Offline</span></td>
                                                <td>2025-05-05 08:10</td>
                                            </tr>
                                        </tbody>
                                    </table>
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
