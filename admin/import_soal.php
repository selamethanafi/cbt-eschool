<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
include '../inc/encrypt.php';
check_login('admin');
include '../inc/dataadmin.php';
require 'autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_excel'])) {
    $allowed_types = [
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'text/csv'
    ];

    $file_type = $_FILES['file_excel']['type'];
    $file_name = $_FILES['file_excel']['name'];
    $tmp_file  = $_FILES['file_excel']['tmp_name'];

    if (in_array($file_type, $allowed_types)) {
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $reader = IOFactory::createReader(match(strtolower($ext)) {
            'csv'  => 'Csv',
            'xls'  => 'Xls',
            default => 'Xlsx'
        });

        try {
            $spreadsheet = $reader->load($tmp_file);
            $data = $spreadsheet->getActiveSheet()->toArray();

            $successCount = 0;
            $duplicateEntries = [];

            // Ambil kode_soal dari baris pertama data (index 1) supaya bisa redirect ke kode_soal yang diimport
            $redirect_kode_soal = isset($data[1][1]) ? trim($data[1][1]) : '';

            for ($i = 1; $i < count($data); $i++) {
                $nomer_soal     = intval($data[$i][0]);
                $kode_soal      = trim($data[$i][1]);
                $pertanyaan     = trim($data[$i][2]);
                $tipe_soal      = trim($data[$i][3]);
                $pilihan_1      = trim($data[$i][4]);
                $pilihan_2      = trim($data[$i][5]);
                $pilihan_3      = trim($data[$i][6]);
                $pilihan_4      = trim($data[$i][7]);
                $jawaban_benar  = trim($data[$i][8]);
                $status_soal    = trim($data[$i][9]);

                $cek = $koneksi->prepare("SELECT COUNT(*) FROM butir_soal WHERE nomer_soal = ? AND kode_soal = ?");
                $cek->bind_param("is", $nomer_soal, $kode_soal);
                $cek->execute();
                $cek->bind_result($count);
                $cek->fetch();
                $cek->close();

                if ($count > 0) {
                    $duplicateEntries[] = "No. $nomer_soal (Kode: $kode_soal)";
                    continue;
                }

                $stmt = $koneksi->prepare("INSERT INTO butir_soal 
                    (nomer_soal, kode_soal, pertanyaan, tipe_soal, pilihan_1, pilihan_2, pilihan_3, pilihan_4, jawaban_benar, status_soal)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssssssss", $nomer_soal, $kode_soal, $pertanyaan, $tipe_soal, 
                                  $pilihan_1, $pilihan_2, $pilihan_3, $pilihan_4, $jawaban_benar, $status_soal);
                if ($stmt->execute()) {
                    $successCount++;
                }
                $stmt->close();
            }

            $_SESSION['import_result'] = [
                'successCount' => $successCount,
                'failCount' => count($duplicateEntries),
                'duplicates' => $duplicateEntries
            ];

            // Redirect ke halaman daftar_butir_soal dengan kode_soal
            header("Location: daftar_butir_soal.php?kode_soal=" . urlencode($redirect_kode_soal));
            exit;

        } catch (Exception $e) {
            $_SESSION['import_error'] = "Terjadi kesalahan saat membaca file Excel: " . $e->getMessage();
            header("Location: daftar_butir_soal.php?kode_soal=" . urlencode($redirect_kode_soal));
            exit;
        }

    } else {
        $_SESSION['import_error'] = "Format file tidak valid!";
        header("Location: daftar_butir_soal.php?kode_soal=" . urlencode($redirect_kode_soal));
        exit;
    }
}
