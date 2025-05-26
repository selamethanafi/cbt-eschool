<?php
// Hapus folder install dan semua isinya
function deleteFolder($folder) {
    if (!is_dir($folder)) return;
    $files = array_diff(scandir($folder), ['.', '..']);
    foreach ($files as $file) {
        $path = "$folder/$file";
        is_dir($path) ? deleteFolder($path) : unlink($path);
    }
    rmdir($folder);
}

$installFolder = __DIR__;
deleteFolder($installFolder);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Instalasi Selesai</title>
<link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet" />
<link rel="icon" type="image/png" href="../assets/images/icon.png" />
<style>
  body {
    background: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    flex-direction: column;
    text-align:center;
    color:#212529;
  }
  h1 {
    margin-bottom: 1rem;
  }
  a.btn {
    margin-top: 1rem;
  }
</style>
</head>
<body>
  <h1>Instalasi Berhasil!</h1>
  <p>Folder <code>install</code> sudah dihapus demi keamanan.</p>
  <a href="../admin/login.php" class="btn btn-success">Masuk ke Admin Panel</a>
</body>
</html>
