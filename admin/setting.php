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
                        <div class="col-12 col-md-6">
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
                                            <!-- Nama Aplikasi -->
                                            <div class="mb-3">
                                                <label for="nama_aplikasi" class="form-label">Nama Aplikasi</label>
                                                <input type="text" class="form-control" name="nama_aplikasi" id="nama_aplikasi" value="<?= $data['nama_aplikasi'] ?? '' ?>" required>
                                            </div>

                                            <!-- Logo Sekolah -->
                                            <div class="mb-3">
                                                <label for="logo_sekolah" class="form-label">Logo</label>
                                                <?php if (!empty($data['logo_sekolah'])): ?>
                                                    <div class="mt-2 mb-2">
                                                        <img id="preview-logo" src="../assets/images/<?= $data['logo_sekolah'] ?>" alt="Logo" width="150">
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" name="logo_sekolah" id="logo_sekolah" accept="image/*">
                                            </div>

                                            <!-- Warna Tema Aplikasi -->
                                            <div class="mb-3">
                                                <label for="warna_tema" class="form-label">Warna Tema Ujian Siswa</label>
                                                <input type="color" class="form-control form-control-color" name="warna_tema" id="warna_tema" value="<?= $data['warna_tema'] ?? '#0d6efd' ?>">
                                            </div>

                                            <!-- Waktu Sinkronisasi -->
                                            <div class="mb-3">
                                                <label for="waktu_sinkronisasi" class="form-label">Waktu Sinkronisasi (detik)</label>
                                                <input type="number" class="form-control" name="waktu_sinkronisasi" id="waktu_sinkronisasi" value="<?= $data['waktu_sinkronisasi'] ?? 60 ?>" min="10">
                                            </div>

                                            <!-- Sembunyikan Nilai -->
                                            <div class="mb-3 form-check">
                                                <input type="checkbox" class="form-check-input" name="sembunyikan_nilai" id="sembunyikan_nilai" value="1" <?= !empty($data['sembunyikan_nilai']) ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="sembunyikan_nilai">Sembunyikan Nilai Siswa (Dashboard Siswa)</label>
                                            </div>

                                            <!-- Status Login Ganda -->
                                            <div class="mb-3">
                                                <label for="login_ganda" class="form-label">Status Login Ganda</label>
                                                <select class="form-select" name="login_ganda" id="login_ganda">
                                                    <option value="izinkan" <?= $data['login_ganda'] === 'izinkan' ? 'selected' : '' ?>>Izinkan</option>
                                                    <option value="blokir" <?= $data['login_ganda'] === 'blokir' ? 'selected' : '' ?>>Blokir</option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Simpan Pengaturan</button>
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
document.getElementById('logo_sekolah').addEventListener('change', function (e) {
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
    reader.onload = function (e) {
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

</body>
</html>