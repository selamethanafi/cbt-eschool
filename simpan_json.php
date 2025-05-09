<?php
// Menerima input JSON
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (isset($data['postData'])) {
    $hasil = $data['postData'];

    // Simpan hasil ke file JSON
    file_put_contents("contoh.json", json_encode($hasil));

    echo json_encode([
        "status" => "success",
        "message" => "Jawaban berhasil disimpan."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Data tidak ditemukan."
    ]);
}
