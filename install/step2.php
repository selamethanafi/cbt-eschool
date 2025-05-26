<?php
session_start();

// Jika sudah terinstal, alihkan ke error
if (file_exists(__DIR__ . '/../koneksi/koneksi.php')) {
    header('Location: error.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['db_host'];
    $user = $_POST['db_user'];
    $pass = $_POST['db_pass'];
    $db   = $_POST['db_name'];

    try {
        $conn = new PDO("mysql:host=$host", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Buat database jika belum ada
        $conn->exec("CREATE DATABASE IF NOT EXISTS `$db`");
        $conn->exec("USE `$db`");

        // Import SQL dari file
        $sqlFile = file_get_contents(__DIR__ . '/../db/cbt_db.sql');
        $conn->exec($sqlFile);

        // Simpan info ke session untuk step3
        $_SESSION['db_host'] = $host;
        $_SESSION['db_user'] = $user;
        $_SESSION['db_pass'] = $pass;
        $_SESSION['db_name'] = $db;

        header('Location: step3.php');
        exit;
    } catch (PDOException $e) {
        $error = "Gagal koneksi atau import database: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Langkah 2 - Konfigurasi Database</title>
  <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/png" href="../assets/images/icon.png" />
  <style>
    body {
      background-color: #f3f5f9;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      max-width: 500px;
      margin-top: 60px;
      padding: 30px;
      background-color: #fff;
      border-radius: 12px;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .logo {
      display: block;
      margin: 0 auto 20px;
      width: 70px;
    }
    .progress-bar {
      height: 10px;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="../assets/images/codelite.png" class="logo" alt="Logo CBT eSchool">
    <h4 class="text-center mb-3">Langkah 2: Konfigurasi Database</h4>

    <!-- Progress -->
    <div class="progress mb-4">
      <div class="progress-bar bg-info" style="width: 66%;"></div>
    </div>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
      <div class="mb-3">
        <label for="db_host" class="form-label">Host Database</label>
        <input type="text" class="form-control" name="db_host" value="localhost" required>
      </div>
      <div class="mb-3">
        <label for="db_user" class="form-label">User Database</label>
        <input type="text" class="form-control" name="db_user" value="root" required>
      </div>
      <div class="mb-3">
        <label for="db_pass" class="form-label">Password Database</label>
        <input type="password" class="form-control" name="db_pass">
      </div>
      <div class="mb-3">
        <label for="db_name" class="form-label">Nama Database</label>
        <input type="text" class="form-control" name="db_name" value="cbt_db" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Lanjut ke Langkah 3</button>
      </div>
    </form>
  </div>
</body>
</html>
