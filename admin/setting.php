<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
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
                        <div class="col-12 col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Form Pengaturan</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                        $q = mysqli_query($koneksi, "SELECT * FROM pengaturan WHERE id = 1");
                                        $data = mysqli_fetch_assoc($q);
                                        ?>
                                    <form action="simpan_pengaturan.php" method="post" enctype="multipart/form-data">
                                        <div class="row g-3">
                                            <!-- Nama Aplikasi -->


                                            <!-- Logo Sekolah -->
                                            <div class="col-12 col-md-6">
                                                <label for="logo_sekolah" class="form-label">Logo</label>
                                                <?php if (!empty($data['logo_sekolah'])): ?>
                                                <div class="mb-2">
                                                    <img id="preview-logo"
                                                        src="../assets/images/<?= $data['logo_sekolah'] ?>" alt="Logo"
                                                        width="150" class="img-thumbnail">
                                                </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="logo_sekolah"
                                                    id="logo_sekolah" accept="image/*">
                                            </div>

                                            <!-- Warna Tema Aplikasi -->
                                            <div class="col-12 col-md-6">
                                                <label for="warna_tema" class="form-label">Warna Tema Ujian
                                                    Siswa</label>

                                                <!-- Color Palette -->
                                                <div class="d-flex flex-wrap gap-2 mb-2" id="palette">
                                                    <?php
                                                    $warnaList = ['#0d6efd', '#198754', '#dc3545', '#ffc107', '#6f42c1', '#20c997', '#fd7e14', '#343a40'];
                                                    $warnaSekarang = $data['warna_tema'] ?? '#0d6efd';
                                                    foreach ($warnaList as $warna) {
                                                        $selected = ($warnaSekarang === $warna) ? 'border-3 border-dark' : 'border';
                                                        echo "<div class='color-box $selected rounded' data-warna='$warna' style='width: 36px; height: 36px; background: $warna; cursor: pointer;'></div>";
                                                    }
                                                    ?>
                                                </div>

                                                <!-- Color Picker -->
                                                <input type="color" class="form-control form-control-color"
                                                    id="colorPicker" value="<?= $warnaSekarang ?>"
                                                    title="Pilih Warna Bebas">

                                                <!-- Hidden Input -->
                                                <input type="hidden" name="warna_tema" id="warna_tema"
                                                    value="<?= $warnaSekarang ?>">

                                                <small class="form-text text-muted">Klik warna di atas atau pilih warna
                                                    bebas di bawah.</small>
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label for="nama_aplikasi" class="form-label">Nama Aplikasi</label>
                                                <input type="text" class="form-control" name="nama_aplikasi"
                                                    id="nama_aplikasi" value="<?= $data['nama_aplikasi'] ?? '' ?>"
                                                    required>
                                            </div>

                                            <!-- Waktu Sinkronisasi -->
                                            <div class="col-12 col-md-6">
                                                <label for="waktu_sinkronisasi" class="form-label">Waktu Sinkronisasi
                                                    (detik)</label>
                                                <input type="number" class="form-control" name="waktu_sinkronisasi"
                                                    id="waktu_sinkronisasi"
                                                    value="<?= $data['waktu_sinkronisasi'] ?? 60 ?>" min="10" required>
                                            </div>

                                            <!-- Sembunyikan Nilai -->
                                            <div class="col-12 col-md-6 d-flex align-items-center">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                        name="sembunyikan_nilai" id="sembunyikan_nilai" value="1"
                                                        <?= !empty($data['sembunyikan_nilai']) ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="sembunyikan_nilai">Sembunyikan
                                                        Nilai Siswa (Dashboard Siswa)</label>
                                                </div>
                                            </div>

                                            <!-- Status Login Ganda -->
                                            <div class="col-12 col-md-6">
                                                <label for="login_ganda" class="form-label">Status Login Ganda</label>
                                                <select class="form-select" name="login_ganda" id="login_ganda"
                                                    required>
                                                    <option value="izinkan"
                                                        <?= $data['login_ganda'] === 'izinkan' ? 'selected' : '' ?>>
                                                        Izinkan</option>
                                                    <option value="blokir"
                                                        <?= $data['login_ganda'] === 'blokir' ? 'selected' : '' ?>>
                                                        Blokir</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-start gap-2 mt-4">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Simpan Pengaturan
                                            </button>
                                            <button type="button" class="btn btn-outline-secondary" id="btnCekUpdate">
                                                <i class="fas fa-sync-alt"></i> Cek Update
                                            </button>
                                        </div>
                                        <div id="hasilUpdate" class="form-text text-muted mt-2"></div>
                                    </form>



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
    document.getElementById('logo_sekolah').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview-logo');
        const maxSize = 2 * 1024 * 1024; // 2MB
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (!file) return;

        if (!validTypes.includes(file.type)) {
            Swal.fire({
                icon: 'error',
                title: 'Format Tidak Valid',
                text: 'Hanya gambar JPG, PNG, GIF, atau WEBP yang diperbolehkan.'
            });
            this.value = '';
            preview.src = '../assets/images/<?= $data['logo_sekolah'] ?>';
            return;
        }

        if (file.size > maxSize) {
            Swal.fire({
                icon: 'warning',
                title: 'Ukuran Terlalu Besar',
                text: 'Ukuran file maksimal 2MB.'
            });
            this.value = '';
            preview.src = '../assets/images/<?= $data['logo_sekolah'] ?>';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    });
    </script>
    <?php if (isset($_SESSION['success'])): ?>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '<?= $_SESSION['success']; ?>',
        confirmButtonColor: '#28a745'
    });
    </script>
    <?php unset($_SESSION['success']); endif; ?>

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
    <script>
    document.getElementById('btnCekUpdate').addEventListener('click', function() {
        const hasil = document.getElementById('hasilUpdate');
        hasil.innerHTML = 'Sedang memeriksa versi terbaru...';

        fetch('cek_update.php')
            .then(res => res.json())
            .then(data => {
                hasil.innerHTML = '';
                if (data.status === 'update') {
                    Swal.fire({
                        title: 'Versi Baru Tersedia!',
                        html: `
                        <p><b>Versi saat ini:</b> ${data.versi_saat_ini}</p>
                        <p><b>Versi terbaru:</b> ${data.versi_baru}</p>
                        <hr>
                        <div style="text-align:left; max-height:200px; overflow:auto;">
                            <h6>Changelog:</h6>
                            ${data.changelog}
                        </div>
                    `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Download & Update',
                        cancelButtonText: 'Tutup',
                        preConfirm: () => {
                            return fetch('proses_update.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        versi_baru: data.versi_baru,
                                        url: data.download_url
                                    })
                                })
                                .then(res => res.json())
                                .then(resp => {
                                    if (!resp.success) throw new Error(resp.message);
                                    return resp;
                                })
                                .catch(err => {
                                    Swal.showValidationMessage(
                                        `Gagal update: ${err.message}`);
                                });
                        }
                    }).then(result => {
                        if (result.isConfirmed && result.value.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Update berhasil diunduh dan diterapkan.',
                            }).then(() => location.reload());
                        }
                    });
                } else if (data.status === 'uptodate') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sudah Versi Terbaru',
                        html: `<b>Versi saat ini:</b> ${data.versi_saat_ini}`,
                        confirmButtonColor: '#28a745'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengecek',
                        text: data.message || 'Terjadi kesalahan.'
                    });
                }
            })
            .catch(() => {
                hasil.innerHTML = '';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Terhubung',
                    text: 'Tidak bisa menghubungi server update.'
                });
            });
    });
    </script>
    <script>
    document.querySelectorAll('.color-box').forEach(box => {
        box.addEventListener('click', function() {
            // Reset semua box
            document.querySelectorAll('.color-box').forEach(b => {
                b.classList.remove('border-3', 'border-dark');
                b.classList.add('border');
            });
            // Tandai yang terpilih
            this.classList.remove('border');
            this.classList.add('border-3', 'border-dark');

            // Set nilai input tersembunyi dan picker
            const warna = this.dataset.warna;
            document.getElementById('warna_tema').value = warna;
            document.getElementById('colorPicker').value = warna;
        });
    });

    // Jika user pilih warna bebas di color picker
    document.getElementById('colorPicker').addEventListener('input', function() {
        const warna = this.value;
        document.getElementById('warna_tema').value = warna;

        // Reset semua box, karena warna bebas
        document.querySelectorAll('.color-box').forEach(b => {
            b.classList.remove('border-3', 'border-dark');
            b.classList.add('border');
        });
    });
    </script>
</body>

</html>