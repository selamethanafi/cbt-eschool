<?php
if (file_exists(__DIR__ . '/../koneksi/koneksi.php')) {
    header('Location: error.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Installer CBT eSchool</title>
<link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet" />
<link rel="icon" type="image/png" href="../assets/images/icon.png" />
<style>
  body {
    background: #f8f9fa;
    display:flex;
    flex-direction:column;
    align-items:center;
    justify-content:center;
    height:100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .logo {
    width: 150px;
    margin-bottom: 1.5rem;
  }
  .btn-install {
    font-weight: 600;
    padding: 0.75rem 2rem;
  }
</style>
</head>
<body>
  <img src="../assets/images/codelite.png" alt="Logo CBT eSchool" class="logo" />
  <a href="step2.php" class="btn btn-outline-secondary btn-install">Mulai Instalasi</a>
</body>
</html>
