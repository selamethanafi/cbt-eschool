<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

// Tambah FAQ
if (isset($_POST['tambah'])) {
    $q = mysqli_real_escape_string($koneksi, $_POST['question']);
    $a = mysqli_real_escape_string($koneksi, $_POST['answer']);
    if(mysqli_query($koneksi, "INSERT INTO faq (question, answer) VALUES ('$q', '$a')")) {
        header("Location: ".$_SERVER['PHP_SELF']."?alert=success&msg=FAQ%20berhasil%20ditambahkan");
    } else {
        header("Location: ".$_SERVER['PHP_SELF']."?alert=error&msg=Gagal%20menambahkan%20FAQ");
    }
    exit();
}

// Edit FAQ
if (isset($_POST['edit'])) {
    $id = (int) $_POST['id'];
    $q = mysqli_real_escape_string($koneksi, $_POST['question']);
    $a = mysqli_real_escape_string($koneksi, $_POST['answer']);
    if(mysqli_query($koneksi, "UPDATE faq SET question='$q', answer='$a' WHERE id=$id")) {
        header("Location: ".$_SERVER['PHP_SELF']."?alert=success&msg=FAQ%20berhasil%20diperbarui");
    } else {
        header("Location: ".$_SERVER['PHP_SELF']."?alert=error&msg=Gagal%20memperbarui%20FAQ");
    }
    exit();
}

// Hapus FAQ
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    if(mysqli_query($koneksi, "DELETE FROM faq WHERE id=$id")) {
        header("Location: ".$_SERVER['PHP_SELF']."?alert=success&msg=FAQ%20berhasil%20dihapus");
    } else {
        header("Location: ".$_SERVER['PHP_SELF']."?alert=error&msg=Gagal%20menghapus%20FAQ");
    }
    exit();
}

// Ambil semua data FAQ
$faq = mysqli_query($koneksi, "SELECT * FROM faq ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ</title>
    <?php include '../inc/css.php'; ?>
    <link href="../assets/summernote/summernote-bs5.css" rel="stylesheet">
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
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">FAQ</h5>
                            </div>
                            <div class="card-body">
                                <!-- Form Tambah -->
                                <div class="card mb-4">
                                    <div class="card-header bg-secondary text-white">
                                        <i class="fa fa-plus-circle"></i> Tambah FAQ
                                    </div>
                                    <div class="card-body">
                                        <form method="post">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label>Pertanyaan</label>
                                                        <textarea name="question" id="question_add" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label>Jawaban</label>
                                                        <textarea name="answer" id="answer_add" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="submit" name="tambah" class="btn btn-primary">
                                                <i class="fa fa-plus"></i> Tambah
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Daftar FAQ -->
                                <?php while($row = mysqli_fetch_assoc($faq)): ?>
                                    <div class="card mb-3 shadow-sm">
                                        <div class="card-body">
                                            <form method="post">
                                                <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label>Pertanyaan</label>
                                                            <textarea name="question" class="form-control summernote"><?= htmlspecialchars_decode($row['question']); ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mb-2">
                                                            <label>Jawaban</label>
                                                            <textarea name="answer" class="form-control summernote"><?= htmlspecialchars_decode($row['answer']); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <button type="submit" name="edit" class="btn btn-success btn-sm">
                                                        <i class="fa fa-save"></i> Simpan
                                                    </button>
                                                    <a href="?hapus=<?= $row['id']; ?>" class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id']; ?>">
                                                        <i class="fa fa-trash"></i> Hapus
                                                    </a>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endwhile; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../inc/js.php'; ?>
<script src="../assets/summernote/summernote-bs5.js"></script>
<script>
$(document).ready(function () {
    const configEditor = {
        height: 80,
        toolbar: false,
        callbacks: {
            onPaste: function (e) {
                e.preventDefault();
                const clipboardData = e.originalEvent.clipboardData || window.clipboardData;
                const text = clipboardData.getData('text/plain');
                document.execCommand("insertText", false, text);
            }
        }
    };

    $('#answer_add, #question_add, .summernote').summernote(configEditor);

    // SweetAlert from URL
    const urlParams = new URLSearchParams(window.location.search);
    const alertType = urlParams.get('alert');
    const alertMsg = urlParams.get('msg');

    if (alertType && alertMsg) {
        Swal.fire({
            icon: alertType,
            title: alertType === 'success' ? 'Sukses!' : 'Error!',
            text: decodeURIComponent(alertMsg),
            timer: 3000,
            showConfirmButton: false
        });

        // Hapus parameter dari URL
        if (window.history.replaceState) {
            const cleanUrl = window.location.origin + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    // SweetAlert delete confirm
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
});
</script>
</body>
</html>
