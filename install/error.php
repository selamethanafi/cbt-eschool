<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Instalasi Terkunci - CBT eSchool</title>
  <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="../assets/images/icon.png" />
  <style>
    body {
      background-color: #f2f4f7;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .error-container {
      background: white;
      padding: 2rem 3rem;
      border-radius: 1rem;
      box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
      text-align: center;
      max-width: 500px;
    }
    .error-container img {
      width: 60px;
      margin-bottom: 15px;
    }
    .error-container h3 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: #dc3545;
    }
    .error-container p {
      font-size: 1rem;
      color: #555;
    }
    .btn {
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="error-container">
    <img src="../assets/images/codelite.png" alt="CBT Logo">
    <h3>Instalasi Tidak Diperbolehkan</h3>
    <p>Sistem <strong>CBT eSchool</strong> sudah terinstal.</p>
    <p>Jika Anda ingin mengulang proses instalasi, silakan hapus file:</p>
    <code>/koneksi/koneksi.php</code>
    <br>
    <a href="../admin/login.php" class="btn btn-primary">Masuk ke Admin Panel</a>
  </div>
</body>
</html>
