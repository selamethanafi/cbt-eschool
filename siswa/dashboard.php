<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

$query = "SELECT * FROM faq";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Siswa</title>
    <?php include '../inc/css.php'; ?>

    <style>
        /* Tabel responsive sederhana */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 320px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f4f4f4;
        }
        /* Style status online/offline */
        .status-online {
            color: #28a745;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-offline {
            color: #dc3545;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .dashboard-header {
      padding: 30px 0;
      text-align: center;
    }

    .dashboard-header h4 {
      font-weight: 600;
    }

    .card-minimal {
      border: none;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
      transition: all 0.2s ease-in-out;
      background: #ffffff;
    }

    .card-minimal:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .card-minimal .card-body {
      text-align: center;
      padding: 30px 20px;
    }

    .dashboard-icon {
      font-size: 36px;
      color:rgba(3, 3, 3, 0.82);
      margin-bottom: 15px;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .kartu-ujian-box {
      text-align: left;
      background: #f1f5f9;
      border-radius: 10px;
      padding: 15px;
      font-size: 14px;
      margin-top: 15px;
      color: #444;
    }

    @media (max-width: 768px) {
      .dashboard-icon {
        font-size: 28px;
      }
    }
    </style>
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
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-secondary text-white d-flex align-items-center">
                                    <h5 class="mb-0 text-white"><strong>Dashbord Siswa</strong></h5>
                                </div>
                                <div class="card-body">
                                    <div class="dashboard-header">
                                                <h4>Halo, <span id="namaSiswa"><?php echo htmlspecialchars($nama_siswa); ?>!</span> 👋</h4>
                                                <p class="text-muted">Selamat datang di dashboard ujian kamu</p>
                                            </div>

                                            <div class="container pb-5">
                                                <div class="row g-4">

                                                <!-- Ujian -->
                                                <div class="col-md-4">
                                                    <a href="ujian.php" class="text-decoration-none text-dark">
                                                    <div class="card card-minimal">
                                                        <div class="card-body">
                                                        <div class="dashboard-icon"><i class="fas fa-pen"></i></div>
                                                        <div class="card-title">Kerjakan Ujian</div>
                                                        <p class="text-muted small mb-0">Akses ujian aktif dan mulai sekarang</p>
                                                        </div>
                                                    </div>
                                                    </a>
                                                </div>

                                                <!-- Hasil -->
                                                <div class="col-md-4">
                                                    <a href="hasil.php" class="text-decoration-none text-dark">
                                                    <div class="card card-minimal">
                                                        <div class="card-body">
                                                        <div class="dashboard-icon"><i class="fas fa-chart-line"></i></div>
                                                        <div class="card-title">Hasil Ujian</div>
                                                        <p class="text-muted small mb-0">Lihat nilai dari ujian yang sudah dikerjakan</p>
                                                        </div>
                                                    </div>
                                                    </a>
                                                </div>

                                                <!-- Kartu Ujian -->
                                                <div class="col-md-4">
                                                    <a href="perangkat.php" class="text-decoration-none text-dark">
                                                    <div class="card card-minimal">
                                                        <div class="card-body">
                                                        <div class="dashboard-icon"><i class="fas fa-laptop"></i></div>
                                                        <div class="card-title">Status Perangkat</div>
                                                        <p class="text-muted small mb-0">Lihat Status Perangkat Anda</p>
                                                        </div>
                                                    </div>
                                                    </a>
                                                </div>

                                                </div>
                                            </div>
=                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <?php include 'chatbot.php'; ?>
    <?php include '../inc/js.php'; ?>
    <?php include '../inc/check_activity.php'; ?>
</body>
</html>
