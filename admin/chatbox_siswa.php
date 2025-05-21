<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatBox Siswa</title>
    <?php include '../inc/css.php'; ?>
    <style>
.chat-line {
    max-width: 100%;
    margin-bottom: 18px; /* beri jarak antar pesan */
    clear: both;
    padding:15px;
}

.chat-line.right {
    margin-left: auto;
    text-align: right;
}

.chat-line.left {
    margin-right: auto;
    text-align: left;
}

/* Nama pengirim di atas pesan */
.chat-sender {
    display: block;
    font-weight: 600;
    font-size: 13px;
    margin-bottom: 4px;
    color: #d6d6d6;
}

/* Box pesan */
.chat-message {
    display: inline-block;       /* supaya lebarnya hanya sebesar konten */
    max-width: 60%;              /* batasi maksimal lebar */
    background-color: #fff;
    border-radius: 10px;
    padding: 8px 12px;
    position: relative;
    font-size: 16px;
    line-height: 1.4;
    word-wrap: break-word;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    padding-right: 50px; /* space untuk timestamp */
}

.chat-line.right .chat-message {
    background-color: #dcf8c6;
}

/* Timestamp di pojok kanan bawah */
.chat-timestamp {
    position: absolute;
    right: 10px;
    bottom: 4px;
    font-size: 11px;
    color: rgba(0, 0, 0, 0.4);
    user-select: none;
}

/* Style tombol hapus */
.delete-chat {
    font-size: 14px;
    color: #d9534f;
    cursor: pointer;
    margin-top: 4px;
    display: inline-block;
}

form#form-chat {
    display: flex;
    align-items: center;
    gap: 8px;
}

#pesan {
    flex-grow: 1;
    min-width: 0;
    padding: 6px 12px;
    font-size: 16px;
}

.emoji-picker {
    position: relative;
    flex-shrink: 0;
    display: inline-block;
}

.emoji-button {
    background: #f8f9fa;
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 6px 10px;
    cursor: pointer;
    font-size: 18px;
}

.emoji-dropdown {
    display: none; /* default tertutup */
    position: absolute;
    bottom: 40px;
    left: 0;
    width: 220px;
    max-height: 150px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 8px;
    z-index: 1000;
    user-select: none;

    /* Grid layout untuk emoji */
    grid-template-columns: repeat(5, 1fr);
    gap: 8px;
    display: grid;
}

.emoji-dropdown:not(.show) {
    display: none;
}

.emoji-dropdown.show {
    display: grid;
}

.emoji-item {
    font-size: 24px;
    cursor: pointer;
    text-align: center;
    line-height: 1;
    user-select: none;
    padding: 4px 0;
    transition: background-color 0.2s ease;
    border-radius: 6px;
}

.emoji-item:hover {
    background: #e2e6ea;
}
#chat-box {
    min-height: 700px;
    max-height: 700px;     /* batas tinggi tetap */
    overflow-y: auto;  
    background-image: url('../assets/images/bgchat.webp');
    background-size: cover;         /* supaya gambar memenuhi area */
    background-repeat: no-repeat;   /* supaya gambar tidak berulang */
    background-position: center;    /* supaya gambar berada di tengah */
}

/* Pesan dari admin - misal background hijau muda */
.chat-line.admin .chat-message {
    background-color: #d1e7dd; /* hijau muda */
    color: #0f5132;
    border: 1px solid #badbcc;
}

/* Tambahkan icon user untuk admin di nama pengirim */
.chat-line.admin .chat-sender::after {
    content: "\f007"; /* FontAwesome user icon unicode */
    font-family: "Font Awesome 5 Free"; 
    font-weight: 900;
    margin-left: 6px;
    color:rgb(255, 0, 0);
    font-size: 14px;
}

/* Pesan dari siswa - background biru muda */
.chat-line.siswa .chat-message {
    background-color: #cfe2ff; /* biru muda */
    color:rgb(22, 22, 22);
    border: 1px solid #b6d4fe;
}

/* Icon untuk siswa di nama pengirim */
.chat-line.siswa .chat-sender::before {
    content: "\f2bd"; /* FontAwesome user-graduate icon unicode */
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    margin-right: 6px;
    color:rgb(126, 126, 126);
    font-size: 14px;
}

/* Pesan saya (admin sendiri) di kanan */
.chat-line.right {
    text-align: right;
}
.card-body {
    background: linear-gradient(to bottom, #c3cfe2,rgb(4, 5, 7));
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
                        <div class="col-lg-6">
                            <div class="card shadow-sm">
                                <div class="card-header bg-secondary text-white d-flex align-items-center">
                                    <strong><i class="fas fa-comments me-1"></i> ChatBox</strong>

                                </div>
                                <div class="card-body">
                                    <div id="chat-box"></div>
                                    <form id="form-chat" style="display: flex; align-items: center; gap: 8px;">
                                        <input type="text" name="pesan" id="pesan" class="form-control"
                                            placeholder="Tulis pesan..." required autocomplete="off"
                                            style="flex-grow: 1; min-width: 0;">

                                        <!-- Emoji picker harus berada di dalam form agar satu baris -->
                                        <div class="emoji-picker" style="position: relative; flex-shrink: 0;">
                                            <button type="button" class="emoji-button"
                                                id="toggleEmojiPicker">😀</button>
                                            <div class="emoji-dropdown" id="emojiDropdown">
                                                <div class="emoji-item">😊</div>
                                                <div class="emoji-item">😂</div>
                                                <div class="emoji-item">😍</div>
                                                <div class="emoji-item">🔥</div>
                                                <div class="emoji-item">👍</div>
                                                <div class="emoji-item">🎉</div>
                                                <div class="emoji-item">😎</div>
                                                <div class="emoji-item">🤔</div>
                                                <div class="emoji-item">😢</div>
                                                <div class="emoji-item">💡</div>
                                                <div class="emoji-item">😡</div>
                                                <div class="emoji-item">🥳</div>
                                                <div class="emoji-item">🙏</div>
                                                <div class="emoji-item">🚀</div>
                                                <div class="emoji-item">🌟</div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Kirim</button>
                                    </form>

                                </div>
                                <!--<audio id="notifSound" src="notif.mp3" preload="auto"></audio>-->
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>
    <?php include '../inc/js.php'; ?>
    <script>
    let lastChatCount = 0;

    function loadChat() {
        $.get('../siswa/load_chat.php', function(data) {
            $('#chat-box').html(data);
            $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);

            const currentCount = $('.chat-line').length;
            if (currentCount > lastChatCount) {
                //document.getElementById('notifSound').play();
            }
            lastChatCount = currentCount;
        });
    }

    setInterval(loadChat, 5000);
    loadChat();

    $('#form-chat').on('submit', function(e) {
        e.preventDefault();

        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true);

        $.post('../siswa/send_chat.php', $(this).serialize())
            .done(function() {
                $('#pesan').val('');
                loadChat();

                // Reset tombol aktif jika server izinkan
                setTimeout(() => {
                    btn.prop('disabled', false);
                }, 10000);
            })
            .fail(function(xhr) {
                let message = xhr.responseText || 'Tolong tunggu sebelum mengirim pesan lagi.';
                let delay = parseInt(xhr.getResponseHeader('X-Delay-Remaining')) || 10;

                // Tampilkan dengan SweetAlert2
                Swal.fire({
                    icon: 'warning',
                    title: 'Upsss.',
                    text: message,
                    timer: 3000,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    btn.prop('disabled', false);
                }, delay * 1000);
            });
    });

    $(document).on('click', '.delete-chat', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
            title: 'Hapus pesan ini?',
            text: "Pesan tidak dapat dikembalikan setelah dihapus.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post('../siswa/delete_chat.php', {
                    id: id
                }, function() {
                    loadChat(); // reload pesan setelah hapus
                });
            }
        });
    });


    function tambahEmoji(emoji) {
        const input = document.getElementById('pesan');
        input.value += emoji;
        input.focus();
    }

    const toggleBtn = document.getElementById('toggleEmojiPicker');
    const emojiDropdown = document.getElementById('emojiDropdown');
    const inputChat = document.getElementById('pesan'); // Pastikan input chat id="pesan"

    toggleBtn.addEventListener('click', () => {
        emojiDropdown.classList.toggle('show');
    });

    // Insert emoji ke input saat diklik
    emojiDropdown.addEventListener('click', e => {
        if (e.target.classList.contains('emoji-item')) {
            const emoji = e.target.textContent;
            const startPos = inputChat.selectionStart;
            const endPos = inputChat.selectionEnd;
            const text = inputChat.value;

            // Sisipkan emoji di posisi kursor input
            inputChat.value = text.substring(0, startPos) + emoji + text.substring(endPos);
            // Pindahkan cursor setelah emoji
            inputChat.selectionStart = inputChat.selectionEnd = startPos + emoji.length;
            inputChat.focus();

            emojiDropdown.classList.remove('show');
        }
    });

    // Klik di luar dropdown tutup dropdown
    document.addEventListener('click', e => {
        if (!toggleBtn.contains(e.target) && !emojiDropdown.contains(e.target)) {
            emojiDropdown.classList.remove('show');
        }
    });
    </script>
</body>

</html>