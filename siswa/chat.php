<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';
// Cek apakah chat diblokir
$cek_pengaturan = mysqli_query($koneksi, "SELECT chat FROM pengaturan LIMIT 1");
$data_pengaturan = mysqli_fetch_assoc($cek_pengaturan);

if (isset($data_pengaturan['chat']) && strtolower($data_pengaturan['chat']) === 'blokir') {
$_SESSION['error'] = 'Fitur chat saat ini diblokir oleh admin.';
        header('Location: dashboard.php');
        exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ChatBox</title>
    <?php include '../inc/css.php'; ?>

    <style>
    .chat-line {
    max-width: 100%;
    margin-bottom: 16px;
    clear: both;
    padding: 10px;
}

.chat-line.right {
    margin-left: auto;
    text-align: right;
}

.chat-line.left {
    margin-right: auto;
    text-align: left;
}

.chat-sender {
    display: block;
    font-weight: 500;
    font-size: 13px;
    margin-bottom: 4px;
    color: #888;
    position: relative;
    padding-left: 20px;
}

.chat-message {
    display: inline-block;
    max-width: 65%;
    background-color: #f9f9f9;
    border-radius: 12px;
    padding: 10px 14px 10px 14px;
    position: relative;
    font-size: 15px;
    line-height: 1.5;
    word-wrap: break-word;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    padding-right: 50px;
}

.chat-line.right .chat-message {
    background-color: #d8f3dc;
}

.chat-timestamp {
    position: absolute;
    right: 12px;
    bottom: 6px;
    font-size: 11px;
    color: rgba(0, 0, 0, 0.3);
    user-select: none;
}

.delete-chat {
    font-size: 13px;
    color: #e63946;
    cursor: pointer;
    margin-top: 4px;
    display: inline-block;
}

form#form-chat {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px;
}

#pesan {
    flex-grow: 1;
    min-width: 0;
    padding: 8px 12px;
    font-size: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    outline: none;
    background-color: #fff;
}

.emoji-picker {
    position: relative;
    flex-shrink: 0;
}

.emoji-button {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 6px 10px;
    cursor: pointer;
    font-size: 18px;
    transition: background 0.2s ease;
}

.emoji-button:hover {
    background: #f1f1f1;
}

.emoji-dropdown {
    display: none;
    position: absolute;
    bottom: 40px;
    left: 0;
    width: 220px;
    max-height: 150px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    padding: 8px;
    z-index: 1000;
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
    font-size: 22px;
    cursor: pointer;
    text-align: center;
    border-radius: 6px;
    transition: background-color 0.2s ease;
}

.emoji-item:hover {
    background: #eee;
}

#chat-box {
    min-height: 600px;
    max-height: 600px;
    overflow-y: auto;
    background: #f4f6f8;
    padding: 12px;
    border-radius: 10px;
}

.chat-line.admin .chat-message {
    background-color:rgb(255, 247, 247);
    color:rgb(0, 0, 0);
    box-shadow: none;
    border: 1px solid rgb(255, 0, 0);
}

.chat-line.admin.right .chat-message {
    background-color: #bbdefb;
    color: #083b74;
}
.boxchatnya {
    background: linear-gradient(to bottom, #e3e8f0, #fdfbfb);
    padding: 12px;
    border-radius: 10px;
}
.chat-line.left .chat-sender {
    padding-left: 0 !important;  /* Hilangkan padding kiri nama pengirim */
}

.chat-line.left .chat-sender i.fas.fa-user-circle {
    margin-left: 0 !important;
    padding-left: 0 !important;
    /* Kalau perlu, juga atur ukuran icon */
    font-size: 1em;
    vertical-align: middle;
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
                        <div class="col-12">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-secondary text-white d-flex align-items-center">
                                    <h5 class="mb-0 text-white"><strong><i class="fas fa-comments me-1"></i>
                                            ChatBox</strong></h5>
                                </div>
                                <div class="card-body">
                                        <div class="row g-4">
                                            <div class="boxchatnya col-md-6 col-sm-6 col-lg-6">
                                                <div id="chat-box"></div>
                                                <form id="form-chat"
                                                    style="display: flex; align-items: center; gap: 8px;">
                                                    <div class="emoji-picker"
                                                        style="position: relative; flex-shrink: 0;">
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
                                                    <input type="text" name="pesan" id="pesan" class="form-control"
                                                        placeholder="Tulis pesan..." required autocomplete="off"
                                                        style="flex-grow: 1; min-width: 0;">

                                                    <!-- Emoji picker harus berada di dalam form agar satu baris -->
                                                    

                                                    <button type="submit" class="btn btn-primary">Kirim</button>
                                                </form>
                                            </div>

                                            <div class="col-md-6 col-lg-6 mb-4" id="chatRules">
                                                <div class="card h-100 border-0 shadow-sm bg-light">
                                                    <div class="card-body text-center py-4">
                                                        <div class="icon-wrapper mb-3">
                                                            <i class="fas fa-comments fa-3x text-primary"></i>
                                                        </div>
                                                        <h5 class="card-title fw-bold">ChatBox</h5>
                                                        <p class="text-muted mb-3">Diskusi dengan teman kalian!</p>
                                                        <button class="btn btn-outline-secondary btn-sm mb-3">
                                                            📜 Aturan Chat
                                                        </button>
                                                        <div>
                                                            <div
                                                                class="card card-body border border-secondary bg-white text-start p-3 mt-2">
                                                                <h6 class="text-primary fw-bold mb-2"><i
                                                                        class="fas fa-info-circle me-1"></i> Aturan
                                                                    Penggunaan:</h6>
                                                                <ol>
                                                                    <li>Gunakan bahasa yang sopan dan santun.</li>
                                                                    <li>Dilarang menyebarkan hoaks, provokasi, atau
                                                                        SARA.</li>
                                                                    <li>Jangan spam atau kirim emoji berlebihan.</li>
                                                                    <li>Fokus pada topik edukasi dan pelajaran.</li>
                                                                    <li>Jaga privasi, jangan sebarkan data teman.</li>
                                                                    <li>Pesan lebih dari 1 menit tidak bisa dihapus.</li>
                                                                    <li>Pesan dimoderasi, pelanggaran bisa dikenai
                                                                        sanksi.</li>
                                                                    <li>Chat bisa diblokir jika disalahgunakan.</li>
                                                                </ol>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </main>
    </div>
    </div>

    <?php include 'chatbot.php'; ?>
    <?php include '../inc/js.php'; ?>
    <?php include '../inc/check_activity.php'; ?>
    <script>
    let lastChatCount = 0;

    function loadChat() {
    const chatBox = $('#chat-box');

    // Simpan posisi scroll sebelum reload
    const isAtBottom = chatBox.scrollTop() + chatBox.innerHeight() >= chatBox[0].scrollHeight - 10;
    const oldScrollTop = chatBox.scrollTop();
    const oldScrollHeight = chatBox[0].scrollHeight;

    $.get('../siswa/load_chat.php', function(data) {
        chatBox.html(data);

        const newScrollHeight = chatBox[0].scrollHeight;

        if (isAtBottom) {
            // Jika sebelumnya di bawah, scroll ke paling bawah
            chatBox.scrollTop(chatBox[0].scrollHeight);
        } else {
            // Jika sebelumnya tidak di bawah, pertahankan posisi relatif
            chatBox.scrollTop(oldScrollTop + (newScrollHeight - oldScrollHeight));
        }

        // Notifikasi jika ada chat baru
        const currentCount = $('.chat-line').length;
        if (currentCount > lastChatCount) {
            // document.getElementById('notifSound').play();
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

        $.post('send_chat.php', $(this).serialize())
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
                $.post('delete_chat.php', {
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
        }//someAudioElement.play();
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