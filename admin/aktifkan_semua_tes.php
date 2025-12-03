<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
if (!isset($_GET['jam']))
 {
    $_SESSION['error'] = 'Jam belum ditentukan.';
    header('Location: soal_hari_ini.php');
    exit;
}
$jam = $_GET['jam'];
$aksi = 'aktif';
$tanggal = date("Y-m-d").' '.$jam;
// Untuk aksi AKTIF
    $kode = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
    $status_success = 0;
    $status_gagal = 0;
if ($aksi == 'aktif') 
{

    $status = 'Aktif';

    // Ambil kode_soal dari tabel soal
    
    $sqlKode = mysqli_query($koneksi, "SELECT * FROM soal WHERE tanggal = '$tanggal'");
    while($dataKode = mysqli_fetch_assoc($sqlKode))
    {
    if ($sqlKode && mysqli_num_rows($sqlKode) > 0) 
    {
        $kode_soal = $dataKode['kode_soal'];
        $id_soal = $dataKode['id_soal'];
        // Cek apakah ada butir soal
        $cekButir = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM butir_soal WHERE kode_soal = '$kode_soal'");
        $dataButir = mysqli_fetch_assoc($cekButir);
        if ($dataButir['total'] == 0) {
            $_SESSION['error'] = 'Soal '.$kode_soal.' tidak dapat diaktifkan karena belum memiliki butir soal.';
            header('Location: soal_hari_ini.php');
            exit;
        }

        // Ambil tipe_soal
        $sqlTipeSoal = mysqli_query($koneksi, "SELECT tipe_soal FROM butir_soal WHERE kode_soal = '$kode_soal' LIMIT 1");
        if ($sqlTipeSoal && mysqli_num_rows($sqlTipeSoal) > 0) {
            $dataTipeSoal = mysqli_fetch_assoc($sqlTipeSoal);
            $tipe_soal = $dataTipeSoal['tipe_soal'];
            // Ambil jawaban_benar dari tabel butir_soal
            $sqlKunci = mysqli_query($koneksi, "SELECT nomer_soal, jawaban_benar FROM butir_soal WHERE kode_soal = '$kode_soal' ORDER BY nomer_soal ASC");
            if ($sqlKunci) {
                if (mysqli_num_rows($sqlKunci) > 0) {
                    $kunci_array = [];
                    $nomor = 1;

                    while ($row = mysqli_fetch_assoc($sqlKunci)) {
                        if ($tipe_soal == 'menjodohkan') {
                            $jawaban = explode('|', $row['jawaban_benar']);
                            $opsi_string = '';
                            foreach ($jawaban as $jawaban_item) {
                                $opsi_parts = explode(':', $jawaban_item);
                                if (count($opsi_parts) == 2) {
                                    $opsi_string .= $opsi_parts[0] . ':' . $opsi_parts[1] . '|';
                                }
                            }
                            $opsi_string = rtrim($opsi_string, '|');
                            $kunci_array[] = '[' . $nomor . ':' . $opsi_string . ']';
                        } else {
                            $kunci_array[] = '[' . $nomor . ':' . $row['jawaban_benar'] . ']';
                        }
                        $nomor++;
                    }

                    $kunci_string = implode(',', $kunci_array);

                    // Update kolom kunci di tabel soal
                    $kunci_string = mysqli_real_escape_string($koneksi,$kunci_string);
                    $updateKunci = mysqli_query($koneksi, "UPDATE soal SET kunci = '$kunci_string' WHERE id_soal = '$id_soal'");
                    if (!$updateKunci) {
                        
                    }

                    // ✅ Setelah semua sukses, barulah aktifkan soal
                    $updateStatus = mysqli_query($koneksi, "UPDATE soal SET status = 'Aktif', token = '$kode' WHERE id_soal = '$id_soal'");
                    if (!$updateStatus) {
                        $_SESSION['error'] = 'Gagal mengaktifkan soal: ' . mysqli_error($koneksi);
                        header('Location: soal_hari_ini.php');
                        exit;
                    }

                } else {
                    $_SESSION['error'] = 'Tidak ada data jawaban_benar pada butir_soal.';
                    header('Location: soal_hari_ini.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Query butir_soal gagal: ' . mysqli_error($koneksi);
                header('Location: soal_hari_ini.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Gagal mengambil tipe_soal dari tabel butir_soal.';
            header('Location: soal_hari_ini.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Gagal mengambil kode_soal dari tabel soal.';
        header('Location: soal_hari_ini.php');
        exit;
    }
}

                    $_SESSION['success'] = 'Status soal berhasil diperbarui.';
                    header('Location: soal_aktif.php');
                    exit;

}
header('Location: soal_hari_ini2.php');
exit;
