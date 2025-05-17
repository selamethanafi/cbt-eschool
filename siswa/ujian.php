<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa'); // Pastikan siswa sudah login
include '../inc/datasiswa.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian Siswa</title>
    <?php include '../inc/css.php'; ?>
    <style>
.ujian-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border:1px solid grey;
}
.ujian-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
}
.badge-mapel {
    font-size: 0.75rem;
    background: #007bff;
    color: #fff;
    padding: 4px 8px;
    border-radius: 5px;
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
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <h5 class="card-title mb-0">Ujian Aktif</h5>
                                    <small id="last-updated" class="text-muted"></small>
                                </div>
                                <div class="card-body">
                                    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari ujian...">
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
<?php include '../inc/js.php'; ?>
<?php include '../inc/check_activity.php'; ?>
<script>
let semuaUjian = [];

function tampilkanUjian(data) {
    const container = document.getElementById('ujian-container');
    container.innerHTML = '';

    if (data.length === 0) {
        container.innerHTML = '<div class="col-12"><div class="alert alert-secondary">Tidak ada ujian ditemukan.</div></div>';
        return;
    }

    data.forEach(ujian => {
        const card = `
            <div class="col-12 col-lg-4 col-xl-3 col-sm-6 col-md-4">
                <div class="card ujian-card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-secondary"><i class="fas fa-qrcode"></i> ${ujian.kode_soal}</h5>
                        <hr>
                        <p class="mb-1"><i class="far fa-file-alt text-secondary me-1"></i> ${ujian.mapel}</p>
                        <p class="mb-1"><i class="fas fa-stopwatch text-secondary me-1"></i> ${ujian.waktu_ujian} menit</p>
                        <p class="mb-3"><i class="far fa-calendar text-secondary me-1"></i> ${ujian.tanggal}</p>
                        <a href="konfirmasi_ujian.php?kode_soal=${ujian.kode_soal}" class="btn btn-outline-secondary mt-auto"><i class="fa fa-sign-in" aria-hidden="true"></i> Masuk Ujian</a>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += card;
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
            $('#last-updated').html(`<i class="fa fa-refresh fa-spin text-success me-1"></i> Terakhir diperbarui: ${now.toLocaleTimeString('id-ID')}`);
        });
}

// Live search
document.getElementById('searchInput').addEventListener('input', function () {
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