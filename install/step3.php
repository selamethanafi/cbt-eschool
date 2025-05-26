<?php
// --- Cleanup folder install ---
$dir = __DIR__;
$files = scandir($dir);
foreach ($files as $file) {
    $path = $dir . DIRECTORY_SEPARATOR . $file;
    if ($file !== '.' && $file !== '..' && $file !== basename(__FILE__)) {
        if (is_dir($path)) {
            deleteFolder($path);
        } else {
            unlink($path);
        }
    }
}
register_shutdown_function(function() use ($dir) {
    @rmdir($dir); // akan terhapus otomatis jika kosong
});
function deleteFolder($folder) {
    $files = array_diff(scandir($folder), ['.', '..']);
    foreach ($files as $file) {
        $path = "$folder/$file";
        if (is_dir($path)) {
            deleteFolder($path);
        } else {
            unlink($path);
        }
    }
    return rmdir($folder);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Instalasi CBT - Selesai</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
  <img src="../assets/images/codelite.png" alt="Logo CBT eSchool" class="installer-logo" />
  <style>
    body {
      background-color: #f2f4f7;
      font-family: 'Segoe UI', sans-serif;
    }
    .install-container {
      max-width: 520px;
      margin: 60px auto;
    }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }
    .card-header {
      background-color: white;
      border-bottom: none;
      text-align: center;
      padding: 30px 20px 10px;
    }
    .card-header img {
      width: 70px;
      margin-bottom: 10px;
    }
    .progress-steps {
      display: flex;
      justify-content: space-between;
      margin-bottom: 25px;
    }
    .progress-steps .step {
      flex: 1;
      text-align: center;
      position: relative;
      color: #6c757d;
      font-weight: 500;
    }
    .progress-steps .step.active {
      color: #0d6efd;
    }
    .progress-steps .step::before {
      content: attr(data-step);
      display: inline-block;
      background-color: #dee2e6;
      color: #6c757d;
      border-radius: 50%;
      width: 28px;
      height: 28px;
      line-height: 28px;
      text-align: center;
      margin-bottom: 6px;
      font-weight: 600;
    }
    .progress-steps .step.active::before {
      background-color: #0d6efd;
      color: #fff;
    }
    .checkmark {
      font-size: 72px;
      color: #28a745;
      animation: pop 0.6s ease;
    }
    @keyframes pop {
      0% { transform: scale(0.6); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="install-container">
    <div class="card">
      <div class="card-header">
        <img src="../assets/images/codelite.png" alt="Logo CBT">
        <h4>Instalasi Aplikasi CBT</h4>
        <small class="text-muted">Langkah 3: Selesai</small>
        <div class="progress-steps mt-4">
          <div class="step" data-step="1">Persiapan</div>
          <div class="step" data-step="2">Konfigurasi</div>
          <div class="step active" data-step="3">Selesai</div>
        </div>
      </div>
      <div class="card-body text-center">
        <div class="checkmark">✔</div>
        <h5 class="mt-3">Instalasi Berhasil!</h5>
        <p class="text-muted">Aplikasi CBT Anda sudah siap digunakan.</p>
        <div class="alert alert-success mt-3" role="alert">
          Folder <code>install/</code> telah dihapus otomatis untuk alasan keamanan.
        </div>
        <div class="d-grid mt-4">
          <a href="../admin/login.php" class="btn btn-success">Masuk ke Panel Admin</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
