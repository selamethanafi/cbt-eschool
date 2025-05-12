<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

// Ambil data dari form
$nama_aplikasi = $_POST['nama_aplikasi'];
$warna_tema = $_POST['warna_tema'];
$waktu_sinkronisasi = intval($_POST['waktu_sinkronisasi']);
if ($waktu_sinkronisasi < 60) {
    echo "
    <script src='../assets/js/sweetalert.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Waktu sinkronisasi tidak boleh kurang dari 60 detik.',
                icon: 'error',
                confirmButtonText: 'Kembali'
            }).then(() => {
                window.location.href = 'setting.php';
            });
        });
    </script>";
    exit;
}
$sembunyikan_nilai = isset($_POST['sembunyikan_nilai']) ? 1 : 0;
$login_ganda = $_POST['login_ganda'];

$logo_path = ''; // default kosong

// Validasi dan handle upload logo
if (!empty($_FILES['logo_sekolah']['name'])) {
    $nama_file = $_FILES['logo_sekolah']['name'];
    $tmp_file = $_FILES['logo_sekolah']['tmp_name'];
    $ukuran = $_FILES['logo_sekolah']['size'];

    // Dapatkan ekstensi
    $ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    // Validasi ekstensi, ukuran, dan MIME type
    $mime = mime_content_type($tmp_file);
    $mime_valid = in_array($mime, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

    if (in_array($ext, $ekstensi_valid) && $mime_valid && $ukuran <= 2 * 1024 * 1024) { // max 2MB
        $nama_baru = 'logo_' . time() . '.' . $ext; // Rename file
        $tujuan = '../assets/images/' . $nama_baru;

        if (move_uploaded_file($tmp_file, $tujuan)) {
            $logo_path = $nama_baru;
        }
    } else {
        echo "<script>
            alert('Upload gagal: hanya file gambar (.jpg/.png/.gif/.webp) maksimal 2MB yang diperbolehkan.');
            window.location.href = 'setting.php';
        </script>";
        exit;
    }
}


// Cek apakah sudah ada data
$query = mysqli_query($koneksi, "SELECT id FROM pengaturan WHERE id=1");
if (mysqli_num_rows($query) > 0) {
    // Update data
    $sql = "UPDATE pengaturan SET 
        nama_aplikasi='$nama_aplikasi',
        warna_tema='$warna_tema',
        waktu_sinkronisasi=$waktu_sinkronisasi,
        sembunyikan_nilai=$sembunyikan_nilai,
        login_ganda='$login_ganda'"
        . ($logo_path ? ", logo_sekolah='$logo_path'" : "") .
        " WHERE id=1";
} else {
    // Insert awal (jika belum ada)
    $sql = "INSERT INTO pengaturan (id, nama_aplikasi, logo_sekolah, warna_tema, waktu_sinkronisasi, sembunyikan_nilai, login_ganda)
        VALUES (1, '$nama_aplikasi', '$logo_path', '$warna_tema', $waktu_sinkronisasi, $sembunyikan_nilai, '$login_ganda')";
}

// Eksekusi query
if (mysqli_query($koneksi, $sql)) {
     echo "
    <script src='../assets/js/sweetalert.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pengaturan berhasil disimpan!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'setting.php';
            });
        });
    </script>";

} else {
    echo "Gagal menyimpan pengaturan: " . mysqli_error($koneksi);
}
?>
