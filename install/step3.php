<?php
// install/step3.php

$host = $_POST['host'] ?? '';
$dbname = $_POST['dbname'] ?? '';
$user = $_POST['user'] ?? '';
$pass = $_POST['pass'] ?? '';
$admin_user = $_POST['admin_user'] ?? '';
$nama_admin = $_POST['nama_admin'] ?? '';
$admin_pass = $_POST['admin_pass'] ?? '';

if (!$host || !$dbname || !$user || !$admin_user || !$admin_pass || !$nama_admin) {
    die("Data tidak lengkap. Silakan isi semua form.");
}

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$dbname`");

    // Import file SQL
    $sql = file_get_contents(__DIR__ . '/../db/cbt_db.sql');
    if (!$sql) {
        throw new Exception("File cbt_db.sql tidak ditemukan di folder db");
    }
    $pdo->exec($sql);

    // Insert admin
    $hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO admins (username, nama_admin, password) VALUES (:username, :nama_admin, :password)");
    $stmt->execute([
        ':username' => $admin_user,
        ':nama_admin' => $nama_admin,
        ':password' => $hashed_pass,
    ]);

    // Buat file koneksi.php dari template
    $template = file_get_contents(__DIR__ . '/config-sample.php');
    if (!$template) {
        throw new Exception("File config-sample.php tidak ditemukan");
    }
    $config = str_replace(
        ['{DB_HOST}', '{DB_NAME}', '{DB_USER}', '{DB_PASS}'],
        [$host, $dbname, $user, $pass],
        $template
    );

    if (!is_dir(__DIR__ . '/../koneksi')) {
        mkdir(__DIR__ . '/../koneksi', 0755, true);
    }
    file_put_contents(__DIR__ . '/../koneksi/koneksi.php', $config);

    header("Location: selesai.php");
    exit;
} catch (Exception $e) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Instalasi Gagal</title>
        <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="icon" type="image/png" href="../assets/images/icon.png" />
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f8d7da;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                padding: 20px;
            }
            .card {
                max-width: 480px;
                padding: 2rem;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 0 25px rgba(220,53,69,0.3);
                text-align: center;
            }
            h3 {
                color: #dc3545;
                margin-bottom: 1rem;
            }
            p {
                color: #555;
                margin-bottom: 1.5rem;
            }
            a.btn {
                text-decoration: none;
                padding: 0.5rem 1.5rem;
                background: #dc3545;
                color: white;
                border-radius: 8px;
                font-weight: 600;
            }
            a.btn:hover {
                background: #b02a37;
            }
        </style>
    </head>
    <body>
        <div class="card shadow-sm">
            <h3>❌ Instalasi Gagal</h3>
            <p><?= htmlspecialchars($e->getMessage()) ?></p>
            <a href="step2.php" class="btn">Kembali ke Form</a>
        </div>
    </body>
    </html>
    <?php
    exit;
}
?>
