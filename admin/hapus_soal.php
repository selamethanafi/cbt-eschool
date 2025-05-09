<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if (isset($_GET['kode_soal'])) {
    $kode_soal = mysqli_real_escape_string($koneksi, $_GET['kode_soal']);
    $query_soal = mysqli_query($koneksi, "SELECT * FROM soal WHERE kode_soal='$kode_soal'");
    $data_soal = mysqli_fetch_assoc($query_soal);

    if ($data_soal['status'] == 'Aktif') {
        die('
        <!DOCTYPE html>
        <html>
        <head>
            <title>Peringatan</title>
            <script src="../assets/js/sweetalert.js"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "warning",
                    title: "Tidak Bisa Dihapus!",
                    text: "Soal ini sudah aktif dan tidak bisa dihapus!",
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => {
                    window.location.href = "soal.php";
                });
            </script>
        </body>
        </html>
        ');
    }

    // Hapus butir soal
    mysqli_query($koneksi, "DELETE FROM butir_soal WHERE kode_soal = '$kode_soal'");

    // Hapus soal
    if (mysqli_query($koneksi, "DELETE FROM soal WHERE kode_soal = '$kode_soal'")) {
        $_SESSION['success'] = 'Soal berhasil dihapus.';
    } else {
        $_SESSION['error'] = 'Gagal menghapus soal: ' . mysqli_error($koneksi);
    }

    header('Location: soal.php');
    exit();
} else {
    $_SESSION['error'] = "Parameter kode_soal tidak ditemukan.";
    header('Location: soal.php');
    exit();
}
?>
