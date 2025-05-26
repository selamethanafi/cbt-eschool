<?php
// Cegah instalasi ulang jika sudah ada koneksi
if (file_exists(__DIR__ . '/../koneksi/koneksi.php')) {
    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Instalasi Dibatalkan</title>
        <img src="../assets/images/codelite.png" alt="Logo CBT">
        <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body { background-color: #f8f9fa; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: sans-serif; }
            .box { max-width: 500px; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); text-align: center; }
        </style>
    </head>
    <body>
        <div class="box">
            <h4 class="text-danger">Instalasi Dibatalkan</h4>
            <p>Aplikasi sudah terinstal.</p>
            <p>File <code>koneksi/koneksi.php</code> telah ditemukan.</p>
            <a href="../admin/login.php" class="btn btn-success mt-3">Masuk ke Panel Admin</a>
        </div>
    </body>
    </html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Instalasi CBT - Step 2</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <img src="../assets/images/codelite.png" alt="Logo CBT">
  <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f2f4f7;
      font-family: 'Segoe UI', sans-serif;
    }
    .install-container {
      max-width: 540px;
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
    .progress-steps .line {
      position: absolute;
      top: 14px;
      left: 50%;
      width: 100%;
      height: 2px;
      background-color: #dee2e6;
      z-index: -1;
    }
    .progress-steps .step:not(:first-child)::after {
      content: '';
      position: absolute;
      top: 14px;
      left: -50%;
      width: 100%;
      height: 2px;
      background-color: #0d6efd;
      z-index: -1;
    }
  </style>
</head>
<body>
  <div class="install-container">
    <div class="card">
      <div class="card-header">
        <img src="../assets/images/codelite.png" alt="Logo CBT">
        <h4>Instalasi Aplikasi CBT</h4>
        <small class="text-muted">Langkah 2: Konfigurasi Database & Admin</small>
        <div class="progress-steps mt-4">
          <div class="step" data-step="1">Persiapan</div>
          <div class="step active" data-step="2">Konfigurasi</div>
          <div class="step" data-step="3">Selesai</div>
        </div>
      </div>
      <div class="card-body">
        <form method="POST" action="step3.php" onsubmit="return handleSubmit()" id="installForm">
          <div class="mb-3">
            <label for="host" class="form-label">Host Database</label>
            <input type="text" id="host" name="host" class="form-control" value="localhost" required>
          </div>
          <div class="mb-3">
            <label for="dbname" class="form-label">Nama Database</label>
            <input type="text" id="dbname" name="dbname" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="user" class="form-label">Username Database</label>
            <input type="text" id="user" name="user" class="form-control" value="root" required>
          </div>
          <div class="mb-3">
            <label for="pass" class="form-label">Password Database</label>
            <input type="password" id="pass" name="pass" class="form-control">
          </div>
          <hr>
          <div class="mb-3">
            <label for="admin_user" class="form-label">Username Admin</label>
            <input type="text" id="admin_user" name="admin_user" class="form-control" minlength="3" required>
            <div class="form-text text-danger d-none" id="userError">Minimal 3 karakter</div>
          </div>
          <div class="mb-3">
            <label for="nama_admin" class="form-label">Nama Lengkap Admin</label>
            <input type="text" id="nama_admin" name="nama_admin" class="form-control" minlength="3" required>
            <div class="form-text text-danger d-none" id="namaError">Minimal 3 karakter</div>
          </div>
          <div class="mb-3">
            <label for="admin_pass" class="form-label">Password Admin</label>
            <input type="password" id="admin_pass" name="admin_pass" class="form-control" minlength="5" required>
            <div class="form-text text-danger d-none" id="passError">Minimal 5 karakter</div>
          </div>
          <div class="d-grid mt-4">
            <button type="submit" class="btn btn-primary" id="submitBtn">
              <span id="submitText">Lanjutkan Instalasi</span>
              <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner" role="status" aria-hidden="true"></span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    const adminUser = document.getElementById('admin_user');
    const namaAdmin = document.getElementById('nama_admin');
    const adminPass = document.getElementById('admin_pass');

    adminUser.addEventListener('input', () => {
      document.getElementById('userError').classList.toggle('d-none', adminUser.value.length >= 3);
    });
    namaAdmin.addEventListener('input', () => {
      document.getElementById('namaError').classList.toggle('d-none', namaAdmin.value.length >= 3);
    });
    adminPass.addEventListener('input', () => {
      document.getElementById('passError').classList.toggle('d-none', adminPass.value.length >= 5);
    });

    function handleSubmit() {
      const isValid = adminUser.value.length >= 3 && namaAdmin.value.length >= 3 && adminPass.value.length >= 5;
      if (!isValid) {
        alert('Pastikan semua data admin minimal terisi sesuai ketentuan.');
        return false;
      }
      // Show loading spinner
      document.getElementById('submitText').classList.add('d-none');
      document.getElementById('loadingSpinner').classList.remove('d-none');
      return true;
    }
  </script>
</body>
</html>
