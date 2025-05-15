<?php
include 'koneksi.php'; // pastikan koneksi tersedia

// Ambil data dari POST
$id_siswa     = $_POST['id_siswa'] ?? '';
$nama_siswa   = $_POST['nama_siswa'] ?? '';
$kode_soal    = $_POST['kode_soal'] ?? '';
$total_soal   = intval($_POST['total_soal'] ?? 0);
$waktu_sisa   = $_POST['waktu_sisa'] ?? '00:00:00';
$jawaban_data = $_POST['jawaban'] ?? []; // array: 0-based index

// Validasi input
if (empty($id_siswa) || empty($kode_soal) || $total_soal <= 0) {
    echo json_encode(['status' => 'error', 'pesan' => 'Data tidak lengkap']);
    exit;
}

// Format jawaban: [1:jawaban],[2:jawaban],... jika kosong pakai "x"
$formatted = [];
for ($i = 0; $i < $total_soal; $i++) {
    $nomor = $i + 1;
    $jawaban = isset($jawaban_data[$i]) && trim($jawaban_data[$i]) !== '' ? trim($jawaban_data[$i]) : 'x';

    // Bersihkan karakter yang bisa bikin error SQL
    $jawaban = str_replace(["\n", "\r"], '', $jawaban);
    $jawaban = mysqli_real_escape_string($conn, $jawaban);

    $formatted[] = "[$nomor:$jawaban]";
}
$jawaban_siswa = implode(',', $formatted);

// Waktu simpan
$waktu_dijawab = date('Y-m-d H:i:s');

// Cek apakah jawaban siswa sudah ada
$cek = mysqli_query($conn, "SELECT id_jawaban FROM jawaban_siswa WHERE id_siswa='$id_siswa' AND kode_soal='$kode_soal'");

if (mysqli_num_rows($cek) > 0) {
    // Update jawaban jika sudah ada
    $sql = "UPDATE jawaban_siswa 
            SET jawaban_siswa='$jawaban_siswa', 
                waktu_sisa='$waktu_sisa', 
                waktu_dijawab='$waktu_dijawab' 
            WHERE id_siswa='$id_siswa' AND kode_soal='$kode_soal'";
} else {
    // Simpan baru jika belum ada
    $sql = "INSERT INTO jawaban_siswa 
            (id_siswa, nama_siswa, kode_soal, total_soal, jawaban_siswa, waktu_sisa, waktu_dijawab, status_ujian) 
            VALUES 
            ('$id_siswa', '$nama_siswa', '$kode_soal', '$total_soal', '$jawaban_siswa', '$waktu_sisa', '$waktu_dijawab', 'Belum Selesai')";
}

// Eksekusi query
if (mysqli_query($conn, $sql)) {
    echo json_encode(['status' => 'sukses']);
} else {
    echo json_encode(['status' => 'gagal', 'error' => mysqli_error($conn)]);
}
?>
