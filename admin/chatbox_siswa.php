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
    }

    .chat-message {
        display: inline-block;
        max-width: 65%;
        background-color: #f4f4f4;
        border-radius: 12px;
        padding: 10px 14px;
        position: relative;
        font-size: 15px;
        line-height: 1.5;
        word-wrap: break-word;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        padding-right: 50px;
    }

    .chat-line.right .chat-message {
        background-color: #d1f7c4;
    }

    .chat-timestamp {
        position: absolute;
        right: 12px;
        bottom: 6px;
        font-size: 11px;
        color: rgba(0, 0, 0, 0.35);
        user-select: none;
    }

    .delete-chat {
        font-size: 13px;
        color: #e63946;
        cursor: pointer;
        margin-top: 4px;
    }

    form#form-chat {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px;
    }

    #pesan {
        flex-grow: 1;
        min-width: 0;
        padding: 8px 12px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        outline: none;
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
        background: #f9f9f9;
        padding: 12px;
        border-radius: 8px;
    }

    /* Admin message */
    .chat-line.admin .chat-message {
        background-color: rgb(255, 247, 247);
        color: rgb(0, 0, 0);
        box-shadow: none;
        border: 1px solid rgb(255, 0, 0);
    }

    /* Admin icon */
    .chat-line.admin .chat-sender::after {
        font-weight: 900;
        margin-left: 6px;
        color: #2e7d32;
        font-size: 14px;
    }

    /* Siswa message */
    .chat-line.siswa .chat-message {
        background-color: #e3f2fd;
        color: #0d47a1;
        border: 1px solid #bbdefb;
    }

    /* Siswa icon */
    .chat-line.siswa .chat-sender::before {
        font-weight: 900;
        margin-right: 6px;
        color: #6c757d;
        font-size: 14px;
    }

    .card-body {
        background: linear-gradient(to bottom right, #e8eaf6, #fdfbfb);
        border-radius: 12px;
        padding: 16px;
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
                                <div class="card-header d-flex justify-content-between">
                                    <strong><i class="fas fa-comments me-1"></i> ChatBox</strong>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="deleteAllChats()"><i
                                            class="fas fa-history"></i> Reset Chat</button>
                                </div>
                                <div class="card-body">
                                    <div id="chat-box"></div>
                                    <form id="form-chat" style="display: flex; align-items: center; gap: 8px;">
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

                                        <input type="text" name="pesan" id="pesan" class="form-control"
                                            placeholder="Tulis pesan..." required autocomplete="off"
                                            style="flex-grow: 1; min-width: 0;">


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
                }, delay * 3000);
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
<script>
function deleteAllChats() {
  Swal.fire({
    title: 'Konfirmasi Hapus',
    html: 'Ketik <strong>HAPUS</strong> untuk menghapus semua chat.',
    input: 'text',
    inputPlaceholder: 'Ketik HAPUS di sini',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Hapus',
    confirmButtonColor: '#d33',
    cancelButtonText: 'Batal',
    preConfirm: (inputValue) => {
      if (inputValue !== 'HAPUS') {
        Swal.showValidationMessage('Anda harus mengetik "HAPUS" dengan benar');
      }
      return inputValue;
    }
  }).then((result) => {
    if (result.isConfirmed && result.value === 'HAPUS') {
      // Kirim ke server tanpa password
      fetch('delete_all_chats.php', {
        method: 'POST'
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'ok') {
          Swal.fire({
            title: 'Berhasil!',
            text: data.message || 'Semua chat telah dihapus.',
            icon: 'success'
          }).then(() => location.reload());
        } else {
          Swal.fire({
            title: 'Gagal',
            text: data.message || 'Penghapusan gagal.',
            icon: 'error'
          });
        }
      })
      .catch(error => {
        Swal.fire({
          title: 'Kesalahan',
          text: 'Terjadi kesalahan saat menghapus.',
          icon: 'error'
        });
        console.error(error);
      });
    }
  });
}
</script>

</body>

</html>