<?php
session_start();
include '../koneksi/koneksi.php';

$isAdmin = isset($_SESSION['admin_id']);
$isSiswa = isset($_SESSION['siswa_id']);

if (!$isAdmin && !$isSiswa) {
    http_response_code(403);
    exit('Unauthorized');
}

if (isset($_POST['pesan']) && !empty(trim($_POST['pesan']))) {
    $pesan = trim($_POST['pesan']);
    $pesan_escaped = mysqli_real_escape_string($koneksi, $pesan);

    // Tentukan ID dan role pengirim
    $id_user = $isAdmin ? $_SESSION['admin_id'] : $_SESSION['siswa_id'];
    $role = $isAdmin ? 'admin' : 'siswa';

    // Hanya siswa dikenai delay pengiriman
    if ($isSiswa) {
        $q = mysqli_query($koneksi, "SELECT waktu FROM chat WHERE id_user = '$id_user' AND role = 'siswa' AND deleted = 0 ORDER BY waktu DESC LIMIT 1");
        $last_time = null;
        if ($q && mysqli_num_rows($q) > 0) {
            $last_time = mysqli_fetch_assoc($q)['waktu'];
        }

        $now = new DateTime();
        $last = $last_time ? new DateTime($last_time) : null;
        $minDelay = 10; // detik

        if ($last) {
            $diff = $now->getTimestamp() - $last->getTimestamp();
            if ($diff < $minDelay) {
                $delayLeft = $minDelay - $diff;
                header('HTTP/1.1 429 Too Many Requests');
                header("X-Delay-Remaining: $delayLeft");
                echo "Tolong tunggu $delayLeft detik sebelum mengirim pesan lagi.";
                exit;
            }
        }
    }

    // Simpan pesan ke database
    mysqli_query($koneksi, "INSERT INTO chat (id_user, role, pesan) VALUES ('$id_user', '$role', '$pesan_escaped')");

    // Batasi hanya 200 pesan terakhir
    mysqli_query($koneksi, "
        DELETE FROM chat 
        WHERE id NOT IN (
            SELECT id FROM (
                SELECT id FROM chat ORDER BY waktu DESC LIMIT 200
            ) AS temp
        )
    ");
}
?>
