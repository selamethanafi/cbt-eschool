<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_nilai'])) {
    $id = intval($_POST['id_nilai']);
    $query = mysqli_query($koneksi, "DELETE FROM nilai WHERE id_nilai = $id");
    echo $query ? "Data berhasil dihapus." : "Gagal menghapus data.";
} else {
    echo "Permintaan tidak valid.";
}
