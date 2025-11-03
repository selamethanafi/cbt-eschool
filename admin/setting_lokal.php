<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'cbt_sianis'");
if(mysqli_num_rows($ta) == 0)
{
	mysqli_query($koneksi, "insert into `cbt_konfigurasi` (`konfigurasi_kode`, `konfigurasi_isi`, `konfigurasi_keterangan`) values ('cbt_sianis', '', 'alamat web sistem informasi madrasah')");
}
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'app_key_server_cbt_lokal'");
if(mysqli_num_rows($ta) == 0)
{
	mysqli_query($koneksi, "insert into `cbt_konfigurasi` (`konfigurasi_kode`, `konfigurasi_isi`, `konfigurasi_keterangan`) values ('app_key_server_cbt_lokal', '', 'Kunci rahasia CBT dan SIM')");
}
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'sek_nama'");
if(mysqli_num_rows($ta) == 0)
{
	mysqli_query($koneksi, "insert into `cbt_konfigurasi` (`konfigurasi_kode`, `konfigurasi_isi`, `konfigurasi_keterangan`) values ('sek_nama', 'MAN 2 Semarang', 'Nama Madrasah')");
}
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'batas'");
if(mysqli_num_rows($ta) == 0)
{
	mysqli_query($koneksi, "insert into `cbt_konfigurasi` (`konfigurasi_kode`, `konfigurasi_isi`, `konfigurasi_keterangan`) values ('batas', '5100', 'Waktu minimal siswa dibolehkan menghentikan tes (detik)')");
}
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'cbt_ruang'");
if(mysqli_num_rows($ta) == 0)
{
	mysqli_query($koneksi, "insert into `cbt_konfigurasi` (`konfigurasi_kode`, `konfigurasi_isi`, `konfigurasi_keterangan`) values ('cbt_ruang', '', 'Ruang')");
}
$ta = mysqli_query($koneksi, "select * from `cbt_konfigurasi` where `konfigurasi_kode` = 'url_bank_soal'");
if(mysqli_num_rows($ta) == 0)
{
	mysqli_query($koneksi, "insert into `cbt_konfigurasi` (`konfigurasi_kode`, `konfigurasi_isi`, `konfigurasi_keterangan`) values ('url_bank_soal', '', 'URL Bank Soal')");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <?php include '../inc/css.php'; ?>
    <style>
    .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        overflow: hidden;
    }

    .progress-bar {
        background-color: #0d6efd;
        height: 100%;
        transition: width 0.4s ease;
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
                        <div class="col-12 col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">Form Pengaturan</h5>
                                </div>
                                <div class="card-body">
                                    <?php

                                        ?>
                                    <form action="simpan_pengaturan_lokal.php" method="post" enctype="multipart/form-data">
                                    <?php
	                                $q = mysqli_query($koneksi, "SELECT * FROM cbt_konfigurasi");
                                        
                                        $nomor = 1;
                                        while($data = mysqli_fetch_assoc($q))
                                        {?>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                    <label for="<?php echo $data['konfigurasi_kode'];?>" class="form-label"><?php echo $data['konfigurasi_keterangan'];?></label>
                                                <input type="text" class="form-control" name="nilai_<?php echo $nomor;?>"
                                                    id="<?php echo $data['konfigurasi_kode'];?>" value="<?= $data['konfigurasi_isi'] ?? '';?>"
                                                    required>
                                            </div>
                                            <?php
                                            echo '<input type="hidden" name="id_referensi_'.$nomor.'" value="'.$data['konfigurasi_id'].'">';
					 $nomor++;
					}
					$cacah_item = $nomor;
					echo '<input type="hidden" name="cacah" value="'.$cacah_item.'">';
                                       ?>
                                </div>

                                <div class="d-flex justify-content-start gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Simpan Pengaturan
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
