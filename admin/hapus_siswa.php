<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    // Cek apakah data siswa ada
    $data = mysqli_query($koneksi, "SELECT * FROM siswa WHERE id_siswa = '$id'");
    if (mysqli_num_rows($data) == 0) {
        echo "
        <script src='../assets/js/sweetalert.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Data siswa tidak ditemukan.',
                    icon: 'error',
                    confirmButtonText: 'Kembali'
                }).then(() => {
                    window.location.href = 'siswa.php';
                });
            });
        </script>";
        exit;
    }

    // Hapus data siswa
    mysqli_query($koneksi, "DELETE FROM siswa WHERE id_siswa = '$id'");

    echo "
    <script src='../assets/js/sweetalert.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Siswa berhasil dihapus.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'siswa.php';
            });
        });
    </script>";
} else {
    // Jika diakses tanpa POST
    header('Location: siswa.php');
    exit;
}
?>
