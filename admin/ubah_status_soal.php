<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

$id_soal = $_GET['id_soal'];
$aksi = $_GET['aksi'];

if ($aksi == 'aktif') {
    $kode = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
    $status = 'Aktif';
    $query = "UPDATE soal SET status = '$status', token = '$kode' WHERE id_soal = '$id_soal'";
    
} else if ($aksi == 'nonaktif') {
    $status = 'Nonaktif';
    $query = "UPDATE soal SET status = '$status', token = NULL WHERE id_soal = '$id_soal'";
    
} else {
    $_SESSION['error'] = 'Aksi tidak dikenali.';
    header('Location: soal.php');
    exit;
}

if (mysqli_query($koneksi, $query)) {
    // Ambil kode_soal dari tabel soal
    $sqlKode = mysqli_query($koneksi, "SELECT kode_soal FROM soal WHERE id_soal = '$id_soal'");
    if ($sqlKode && mysqli_num_rows($sqlKode) > 0) {
        $dataKode = mysqli_fetch_assoc($sqlKode);
        $kode_soal = $dataKode['kode_soal'];

        // Ambil tipe_soal dari tabel butir_soal berdasarkan kode_soal
        $sqlTipeSoal = mysqli_query($koneksi, "SELECT tipe_soal FROM butir_soal WHERE kode_soal = '$kode_soal' LIMIT 1");
        if ($sqlTipeSoal && mysqli_num_rows($sqlTipeSoal) > 0) {
            $dataTipeSoal = mysqli_fetch_assoc($sqlTipeSoal);
            $tipe_soal = $dataTipeSoal['tipe_soal'];

            // Ambil jawaban_benar dari tabel butir_soal, urut berdasarkan nomer_soal
            $sqlKunci = mysqli_query($koneksi, "SELECT nomer_soal, jawaban_benar FROM butir_soal WHERE kode_soal = '$kode_soal' ORDER BY nomer_soal ASC");
            if ($sqlKunci) {
                if (mysqli_num_rows($sqlKunci) > 0) {
                    $kunci_array = [];
                    $nomor = 1;

                    while ($row = mysqli_fetch_assoc($sqlKunci)) {
                        if ($tipe_soal == 'menjodohkan') {
                            // Jika tipe soal adalah menjodohkan
                            $jawaban = explode('|', $row['jawaban_benar']); // Split by pipe (|)

                            // Format kunci [nomer_soal:opsi1|opsi2|opsi3|opsi4]
                            $opsi_string = '';
                            foreach ($jawaban as $jawaban_item) {
                                $opsi_parts = explode(':', $jawaban_item); // Split each part by colon (:)
                                if (count($opsi_parts) == 2) {
                                    $opsi_string .= $opsi_parts[0] . ':' . $opsi_parts[1] . '|';
                                }
                            }

                            // Remove last pipe (|)
                            $opsi_string = rtrim($opsi_string, '|');
                            
                            // Gabungkan ke dalam kunci array
                            $kunci_array[] = '[' . $nomor . ':' . $opsi_string . ']';
                        } else {
                            // Format kunci untuk tipe soal lain, misalnya hanya [nomer_soal:jawaban]
                            $kunci_array[] = '[' . $nomor . ':' . $row['jawaban_benar'] . ']';
                        }
                        $nomor++;
                    }

                    $kunci_string = implode(',', $kunci_array);

                    // Update kolom kunci di tabel soal
                    $updateKunci = mysqli_query($koneksi, "UPDATE soal SET kunci = '$kunci_string' WHERE id_soal = '$id_soal'");
                    if (!$updateKunci) {
                        $_SESSION['error'] = 'Gagal memperbarui kolom kunci: ' . mysqli_error($koneksi);
                        header('Location: soal.php');
                        exit;
                    }
                } else {
                    $_SESSION['error'] = 'Tidak ada data jawaban_benar pada butir_soal.';
                    header('Location: soal.php');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Query butir_soal gagal: ' . mysqli_error($koneksi);
                header('Location: soal.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Gagal mengambil tipe_soal dari tabel butir_soal.';
            header('Location: soal.php');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Gagal mengambil kode_soal dari tabel soal.';
        header('Location: soal.php');
        exit;
    }

    $_SESSION['success'] = 'Status soal berhasil diperbarui.';
} else {
    $_SESSION['error'] = 'Gagal memperbarui status soal: ' . mysqli_error($koneksi);
}

header('Location: soal.php');
exit;
?>
