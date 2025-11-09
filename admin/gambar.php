<?php
set_time_limit(0);
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');
ignore_user_abort(true);
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('admin');
include '../inc/dataadmin.php';


?>
<!DOCTYPE html>
<html>
<head>
<title>Progress Kompres Folder</title>
<style>
#progress-container {
    width: 100%;
    background: #ddd;
    border-radius: 5px;
    height: 25px;
    margin-top: 20px;
}
#progress-bar {
    width: 0%;
    height: 100%;
    background: #4caf50;
    border-radius: 5px;
    text-align: center;
    color: white;
    line-height: 25px;
}
</style>
</head>
<body>

<h2>Kompres Folder menjadi .tar.gz</h2>
<button onclick="startProcess()">Mulai Kompres</button>

<div id="progress-container">
    <div id="progress-bar">0%</div>
</div>

<script>
function startProcess() {
    // jalankan proses kompres
    fetch("kompres_gambar.php");

    // mulai update progress tiap 500ms
    let timer = setInterval(() => {
        fetch("progress.php")
        .then(r => r.text())
        .then(progress => {
            progress = parseFloat(progress);

            let bar = document.getElementById("progress-bar");
            bar.style.width = progress + "%";
            bar.textContent = progress + "%";

            if (progress >= 100) {
                clearInterval(timer);
                 window.location.href = "penyiapan.php";
            }
        });
    }, 500);
}
</script>

</body>
</html>

