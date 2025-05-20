<?php
session_start();
include '../koneksi/koneksi.php';

$isAdmin = isset($_SESSION['admin_id']);
$isSiswa = isset($_SESSION['siswa_id']);

if (!$isAdmin && !$isSiswa) {
    http_response_code(403);
    exit('Akses ditolak');
}

// Identitas pengguna yang login
$my_id = $isAdmin ? $_SESSION['admin_id'] : $_SESSION['siswa_id'];
$my_role = $isAdmin ? 'admin' : 'siswa';

// Ambil 50 pesan terbaru (admin dan siswa)
$q_chat = mysqli_query($koneksi, "
    SELECT c.*, 
           CASE 
               WHEN c.role = 'siswa' THEN s.nama_siswa 
               ELSE 'Admin' 
           END AS nama_pengirim
    FROM (
        SELECT * FROM chat WHERE deleted = 0 ORDER BY waktu DESC LIMIT 50
    ) c 
    LEFT JOIN siswa s ON c.role = 'siswa' AND c.id_user = s.id_siswa
    ORDER BY c.waktu ASC
");


while ($chat = mysqli_fetch_assoc($q_chat)) {
    $isMe = ($chat['id_user'] == $my_id && $chat['role'] == $my_role);
    $additionalClass = '';

    if ($chat['role'] === 'admin') {
        $additionalClass = ' admin';
    } else if ($chat['role'] === 'siswa') {
        $additionalClass = ' siswa';
    }

    echo '<div class="chat-line ' . ($isMe ? 'right' : 'left') . $additionalClass . '">';
    
    echo '<small class="chat-sender">' . htmlspecialchars($chat['nama_pengirim']) . '</small>';
    
    echo '<div class="chat-message">';
    echo nl2br(htmlspecialchars($chat['pesan']));
    echo '<span class="chat-timestamp">' . date('H:i', strtotime($chat['waktu'])) . '</span>';
    echo '</div>';

    if ($isMe && (time() - strtotime($chat['waktu']) <= 60)) {
        echo '<br><a href="#" class="delete-chat" data-id="' . $chat['id'] . '" title="Hapus pesan">';
        echo '<i class="fas fa-trash-alt"></i></a>';
    }

    echo '</div>';
}

?>
