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

    th,
    td {
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
        color: rgba(3, 3, 3, 0.82);
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

    .game-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 16px;
    }

    .game-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        background: linear-gradient(to bottom right, #e9f5ff, #fdfdfd);
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background-color: #e0f0ff;
        display: flex;
        justify-content: center;
        align-items: center;
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
                                        <h4>Halo, <span
                                                id="namaSiswa"><?php echo htmlspecialchars($nama_siswa); ?>!</span> 👋
                                        </h4>
                                        <p class="text-muted">Selamat datang di dashboard ujian kamu</p>
                                    </div>

                                    <div class="container pb-5">
                                        <div class="row g-4">

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <a href="#" class="text-decoration-none text-dark">
                                                    <div
                                                        class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                        <div
                                                            class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                            <div class="icon-wrapper mb-3">
                                                                <i class="fas fa-camera fa-3x text-primary"></i>
                                                            </div>
                                                            <h5 class="card-title fw-bold">Scan QR Ujianmu</h5>
                                                            <p class="text-muted mb-2">Akses ujian menggunakan QR code
                                                            </p>
                                                            <button id="btnScanQR" class="btn btn-outline-primary mb-3">
                                                                <i class="fas fa-qrcode me-1"></i> Scan QR Ujian
                                                            </button>
                                                            <div id="qr-reader" style="width: 300px; display: none;"
                                                                class="mx-auto mb-3"></div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <a href="ujian.php" class="text-decoration-none text-dark">
                                                    <div
                                                        class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                        <div
                                                            class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                            <div class="icon-wrapper mb-3">
                                                                <i class="fas fa-pen fa-3x text-primary"></i>
                                                            </div>
                                                            <h5 class="card-title fw-bold">Kerjakan Ujianmu</h5>
                                                            <p class="text-muted mb-2">Akses ujian aktif dan mulai
                                                                sekarang</p>
                                                            <button class="btn btn-outline-primary mb-3">cek Sekarang</button>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <a href="hasil.php" class="text-decoration-none text-dark">
                                                    <div
                                                        class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                        <div
                                                            class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                            <div class="icon-wrapper mb-3">
                                                                <i class="fas fa-chart-line fa-3x text-primary"></i>
                                                            </div>
                                                            <h5 class="card-title fw-bold">Hasil Ujianmu</h5>
                                                            <p class="text-muted mb-2">Lihat nilai dari ujian yang sudah
                                                                dikerjakan</p>
                                                            <button class="btn btn-outline-primary mb-3">cek Sekarang</button>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <a href="perangkat.php" class="text-decoration-none text-dark">
                                                    <div
                                                        class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                        <div
                                                            class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                            <div class="icon-wrapper mb-3">
                                                                <i class="fas fa-laptop fa-3x text-primary"></i>
                                                            </div>
                                                            <h5 class="card-title fw-bold">Status Perangkat</h5>
                                                            <p class="text-muted mb-2">Lihat Status Perangkat Anda</p>
                                                            <button class="btn btn-outline-primary mb-3">cek Sekarang</button>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <a href="game.php" class="text-decoration-none text-dark">
                                                    <div
                                                        class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                        <div
                                                            class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                            <div class="icon-wrapper mb-3">
                                                                <i class="fa fa-gamepad fa-3x text-primary"></i>
                                                            </div>
                                                            <h5 class="card-title fw-bold">Mini Games</h5>
                                                            <p class="text-muted mb-2">Belajar sambil bermain!</p>
                                                            <button class="btn btn-outline-primary mb-3">Main Sekarang</button>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>

                                            <div class="col-md-6 col-sm-6 col-lg-4">
                                                <a href="chat.php" class="text-decoration-none text-dark">
                                                    <div
                                                        class="card card-minimal h-100 border-0 shadow-sm bg-light game-card">
                                                        <div
                                                            class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">
                                                            <div class="icon-wrapper mb-3">
                                                                <i class="fas fa-comments  fa-3x text-primary"></i>
                                                            </div>
                                                            <h5 class="card-title fw-bold">ChatBox</h5>
                                                            <p class="text-muted mb-2">Diskusi dengan teman kalian!</p>
                                                            <button class="btn btn-outline-primary mb-3">Chat Sekarang</button>
                                                        </div>
                                                    </div>
                                                </a>
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

    <?php include 'chatbot.php'; ?>
    <?php include '../inc/js.php'; ?>
    <?php include '../inc/check_activity.php'; ?>
    <script src="../assets/js/html5-qrcode.min.js"></script>
    <script>
    document.getElementById('btnScanQR').addEventListener('click', function() {
        const qrReader = document.getElementById('qr-reader');
        qrReader.style.display = 'block';

        const html5QrCode = new Html5Qrcode("qr-reader");

        html5QrCode.start({
                facingMode: "environment"
            }, // Kamera belakang
            {
                fps: 10,
                qrbox: 250
            },
            qrCodeMessage => {
                html5QrCode.stop().then(() => {
                    qrReader.style.display = 'none';
                    window.location.href = qrCodeMessage;
                }).catch(err => {
                    alert("Gagal menghentikan kamera: " + err);
                });
            },
            errorMessage => {
                // Scan gagal, abaikan
            }
        ).catch(err => {
            alert("Gagal mengakses kamera: " + err);
        });
    });
    </script>

    <?php if (isset($_SESSION['error'])): ?>
    <script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '<?= $_SESSION['error']; ?>',
        confirmButtonColor: '#dc3545'
    });
    </script>
    <?php unset($_SESSION['error']); endif; ?>
</body>

</html>