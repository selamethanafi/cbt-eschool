<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');

function decrypt_data($data, $key) {
    $data = base64_decode($data);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}

$decrypted_sql = '';
$error = '';

if (isset($_POST['preview']) && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    if ($file['size'] > 2 * 1024 * 1024) { // 2MB batas
    $error = "Ukuran file terlalu besar. Maksimal 2MB.";
}

    if ($file['error'] === 0 && pathinfo($file['name'], PATHINFO_EXTENSION) === 'dbk') {
        $encrypted = file_get_contents($file['tmp_name']);
        $decrypted = decrypt_data($encrypted, $key);

        if ($decrypted !== false) {
            $decrypted_sql = $decrypted;
        } else {
            $error = "Gagal mendekripsi file. Kunci mungkin salah atau file rusak.";
        }
    } else {
        $error = "File tidak valid. Pastikan memilih file .dbk.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Preview File .dbk</title>
    <?php include '../inc/css.php'; ?>
</head>
<body>
<div class="wrapper">
    <?php include 'sidebar.php'; ?>
    <div class="main">
        <?php include 'navbar.php'; ?>
        <main class="content">
            <div class="container-fluid p-0">
                <h3 class="mb-4">Preview File Backup (.dbk)</h3>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih file backup (.dbk)</label>
                        <input type="file" name="file" id="file" accept=".dbk" class="form-control" required>
                    </div>
                    <button type="submit" name="preview" class="btn btn-info">
                        <i class="fa fa-eye"></i> Tampilkan Isi SQL
                    </button>
                </form>

                <?php if ($decrypted_sql): ?>
                    <div class="mt-4">
                        <label for="sqlPreview" class="form-label">Isi SQL (Setelah Dekripsi)</label>
                        <textarea class="form-control" rows="15" readonly><?= htmlspecialchars($decrypted_sql) ?></textarea>
                    </div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger mt-4"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<?php include '../inc/js.php'; ?>
</body>
</html>
