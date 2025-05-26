<?php
if (file_exists("../koneksi/koneksih.php")) {
    header("Location: ../admin/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Instalasi CBT eSchool</title>
    <link rel="icon" type="image/png" href="../assets/images/icon.png" />
    <!-- Bootstrap 5 -->
    <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .installer-card {
            max-width: 420px;
            width: 100%;
            padding: 2.5rem 2rem;
            background: #ffffff;
            box-shadow: 0 0 20px rgb(0 0 0 / 0.1);
            border-radius: 12px;
            text-align: center;
        }
        .installer-logo {
            max-width: 120px;
            margin-bottom: 1.5rem;
        }
        h2 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #212529;
        }
        p.lead {
            color: #495057;
            margin-bottom: 2rem;
        }
        button.btn-primary {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.1rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="installer-card">
        <img src="../assets/images/codelite.png" alt="Logo CBT eSchool" class="installer-logo" />
        <h2>Selamat Datang di Instalasi CBT eSchool</h2>
        <p class="lead">Klik tombol di bawah untuk memulai proses instalasi aplikasi.</p>
        <form method="post" action="step2.php" novalidate>
            <button type="submit" class="btn btn-primary">Mulai Instalasi</button>
        </form>
        <p style="font-size:12px;">© 2025 Gludug codelite</p>
    </div>
</body>
</html>
