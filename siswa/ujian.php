<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa'); // Pastikan siswa sudah login
include '../inc/datasiswa.php';
require_once '../assets/phpqrcode/qrlib.php';

// Dapatkan protokol dan host dinamis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
$basePath = rtrim(dirname($scriptPath, 1), '/');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Siswa</title>
    <?php include '../inc/css.php'; ?>
    <style>
    .ujian-card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 16px;
        background: linear-gradient(to bottom right, #f9f9f9, #ffffff);
        border: 1px solid #999999 !important;
        /* border tipis abu-abu terang */
    }

    .ujian-card-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        background: linear-gradient(to bottom right, #e9f5ff, #fdfdfd);
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #e0f0ff;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    #last-updated {
        color: white !important;
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
                            <div class="card">
                                <div
                                    class="card-header d-flex bg-secondary text-white justify-content-between align-items-center flex-wrap gap-2">
                                    <h5 class="card-title mb-0 text-white">Ujian Aktif</h5>
                                    <small id="last-updated" class="text-muted text-white"></small>
                                </div>
                                <div class="card-body">
                                    <input type="text" id="searchInput" class="form-control mb-3"
                                        placeholder="Cari ujian...">
                                    <div id="ujian-container" class="row g-3">
                                        <!-- Kartu ujian akan dimuat di sini -->
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
    <script src="../assets/js/qrcode.min.js"></script>

    <script>
    let semuaUjian = [];

    function tampilkanUjian(data) {
    const container = document.getElementById('ujian-container');
    container.innerHTML = '';

    if (data.length === 0) {
        container.innerHTML =
            '<div class="col-12 text-center py-5"><i class="fa fa-user-slash fa-3x text-muted mb-3"></i><br>Tidak ada ujian ditemukan.</div>';
        return;
    }

    const pathNameParts = window.location.pathname.split('/').filter(Boolean);
    const appFolder = pathNameParts.length > 0 ? '/' + pathNameParts[0] : '';

    let allCardsHTML = '';
    const qrList = []; // Simpan data QR yang perlu digenerate

    data.forEach(ujian => {
        const cardId = 'qr-' + ujian.kode_soal;
        const qrLink = `${window.location.origin}${appFolder}/siswa/konfirmasi_ujian.php?kode_soal=${encodeURIComponent(ujian.kode_soal)}`;

        qrList.push({ id: cardId, link: qrLink }); // Simpan data QR-nya

        allCardsHTML += `
        <div class="col-12 col-lg-4 col-xl-3 col-sm-6 col-md-4">
            <div class="card ujian-card h-100 shadow-sm border-0 bg-light ujian-card-hover">
                <div class="card-body d-flex flex-column text-center py-4">
                    <div id="${cardId}" class="icon-wrapper mb-3 mx-auto"></div>
                    <h5 class="card-title text-dark fw-bold mb-2">${ujian.kode_soal}</h5>
                    <hr class="my-2">
                    <p class="mb-1"><i class="far fa-file-alt text-secondary me-1"></i> ${ujian.mapel}</p>
                    <p class="mb-1"><i class="fas fa-stopwatch text-secondary me-1"></i> ${ujian.waktu_ujian} menit</p>
                    <p class="mb-3"><i class="far fa-calendar text-secondary me-1"></i> ${ujian.tanggal}</p>
                    <a href="konfirmasi_ujian.php?kode_soal=${ujian.kode_soal}" class="btn btn-outline-secondary mt-auto">
                        <i class="fa fa-sign-in-alt me-1"></i> Masuk Ujian
                    </a>
                </div>
            </div>
        </div>`;
    });

    container.innerHTML = allCardsHTML;

    // Setelah semua elemen ada di DOM, generate semua QR code
    qrList.forEach(({ id, link }) => {
        const el = document.getElementById(id);
        if (el) {
            new QRCode(el, {
                text: link,
                width: 80,
                height: 80,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.L
            });
        }
    });
}

    function loadUjian() {
        fetch('get_ujian.php')
            .then(res => res.json())
            .then(data => {
                semuaUjian = data;
                tampilkanUjian(data);

                // Update waktu terakhir
                let now = new Date();
                $('#last-updated').html(
                    `<i class="fa fa-refresh fa-spin text-success me-1"></i> Terakhir diperbarui: ${now.toLocaleTimeString('id-ID')}`
                    );
            });
    }

    // Live search
    document.getElementById('searchInput').addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        const hasil = semuaUjian.filter(ujian =>
            ujian.mapel.toLowerCase().includes(keyword) ||
            ujian.kode_soal.toLowerCase().includes(keyword)
        );
        tampilkanUjian(hasil);
    });

    // Jalankan saat load dan per 1 menit
    loadUjian();
    setInterval(loadUjian, 60000);
    </script>
    <?php if (isset($_SESSION['alert'])): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'warning',
            title: 'Warning!',
            text: '<?php echo $_SESSION['warning_message']; ?>',
            showConfirmButton: false,
            timer: 2000
        });
    });
    </script>
    <?php unset($_SESSION['warning_message']); ?>
    <?php endif; ?>
</body>

</html>