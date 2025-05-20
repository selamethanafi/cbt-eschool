<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';
check_login('siswa');
include '../inc/datasiswa.php';

$query = "SELECT * FROM faq";
$result = mysqli_query($koneksi, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard Siswa</title>
    <?php include '../inc/css.php'; ?>

    <style>
        /* Tabel responsive sederhana */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            min-width: 320px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
            vertical-align: middle;
        }
        th {
            background-color: #f4f4f4;
        }
        /* Style status online/offline */
        .status-online {
            color: #28a745;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .status-offline {
            color: #dc3545;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
        .dashboard-header {
      padding: 30px 0;
      text-align: center;
    }

    .dashboard-header h4 {
      font-weight: 600;
    }

    .card-minimal {
      border: none;
      border-radius: 14px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
      transition: all 0.2s ease-in-out;
      background: #ffffff;
    }

    .card-minimal:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
    }

    .card-minimal .card-body {
      text-align: center;
      padding: 30px 20px;
    }

    .dashboard-icon {
      font-size: 36px;
      color:rgba(31, 31, 31, 0.46);
      margin-bottom: 15px;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .kartu-ujian-box {
      text-align: left;
      background: #f1f5f9;
      border-radius: 10px;
      padding: 15px;
      font-size: 14px;
      margin-top: 15px;
      color: #444;
    }

    @media (max-width: 768px) {
      .dashboard-icon {
        font-size: 28px;
      }
    }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'sidebar.php'; ?>
        <div class="main">
            <?php include 'navbar.php'; ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm mb-4">
                                <div class="card-header bg-secondary text-white d-flex align-items-center">
                                    <h5 class="mb-0 text-white"><strong>Detail Perangkat</strong></h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <th>Status Koneksi</th>
                                                    <td id="status">Memeriksa...</td>
                                                </tr>
                                                <tr>
                                                    <th>IP Address</th>
                                                    <td id="ip">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>OS & Browser</th>
                                                    <td id="userAgent">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>RAM (perkiraan)</th>
                                                    <td id="ram">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Resolusi Layar</th>
                                                    <td id="resolusi">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Ukuran Viewport</th>
                                                    <td id="viewport">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Bahasa Sistem</th>
                                                    <td id="bahasa">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Waktu Lokal</th>
                                                    <td id="waktu">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Zona Waktu</th>
                                                    <td id="zona">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Platform</th>
                                                    <td id="platform">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Cookie Diaktifkan</th>
                                                    <td id="cookie">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Jenis Perangkat</th>
                                                    <td id="deviceType">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Touch Support</th>
                                                    <td id="touch">Memuat...</td>
                                                </tr>
                                                <tr>
                                                    <th>Status Baterai</th>
                                                    <td id="baterai">Memuat...</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <?php include 'chatbot.php'; ?>
    <?php include '../inc/js.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        function $(id) { return document.getElementById(id); }

        // Update status koneksi dengan kelas hijau/merah
        function updateOnlineStatus() {
            if(navigator.onLine){
                $('status').textContent = "Online";
                $('status').className = "status-online";
            } else {
                $('status').textContent = "Offline";
                $('status').className = "status-offline";
            }
        }
        updateOnlineStatus();
        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);

        // IP Address via API eksternal
        fetch("https://api.ipify.org?format=json")
            .then(res => res.json())
            .then(data => {
                $('ip').textContent = data.ip;
                $('ip').className = "";
            })
            .catch(() => {
                $('ip').textContent = "Tidak tersedia";
                $('ip').className = "";
            });

        // OS & Browser (User Agent)
        $('userAgent').textContent = navigator.userAgent;
        $('userAgent').className = "";

        // RAM (perkiraan)
        if (navigator.deviceMemory) {
            $('ram').textContent = navigator.deviceMemory + " GB";
            $('ram').className = "";
        } else {
            $('ram').textContent = "Tidak tersedia";
            $('ram').className = "";
        }

        // Resolusi layar
        $('resolusi').textContent = screen.width + " x " + screen.height;
        $('resolusi').className = "";

        // Ukuran viewport
        $('viewport').textContent = window.innerWidth + " x " + window.innerHeight;
        $('viewport').className = "";

        // Bahasa Sistem
        $('bahasa').textContent = navigator.language || navigator.userLanguage;
        $('bahasa').className = "";

        // Waktu Lokal
        $('waktu').textContent = new Date().toLocaleString();
        $('waktu').className = "";

        // Zona Waktu
        $('zona').textContent = Intl.DateTimeFormat().resolvedOptions().timeZone || "Tidak tersedia";
        $('zona').className = "";

        // Platform
        $('platform').textContent = navigator.platform || "Tidak tersedia";
        $('platform').className = "";

        // Cookie diaktifkan
        $('cookie').textContent = navigator.cookieEnabled ? "Aktif" : "Nonaktif";
        $('cookie').className = "";

        // Jenis perangkat
        let ua = navigator.userAgent.toLowerCase();
        let deviceType = /mobile|android|iphone|ipad/.test(ua) ? "Mobile/Tablet" : "Desktop";
        $('deviceType').textContent = deviceType;
        $('deviceType').className = "";

        // Touch support
        $('touch').textContent = ('ontouchstart' in window || navigator.maxTouchPoints > 0) ? "Touchscreen" : "Non-Touch";
        $('touch').className = "";

        // Status baterai (jika didukung)
        if ('getBattery' in navigator) {
            navigator.getBattery().then(function(battery) {
                let level = Math.round(battery.level * 100) + "%";
                $('baterai').textContent = level + (battery.charging ? " (Charging)" : "");
                $('baterai').className = "";

                // Update saat status baterai berubah
                battery.addEventListener('levelchange', () => {
                    $('baterai').textContent = Math.round(battery.level * 100) + "%";
                });
                battery.addEventListener('chargingchange', () => {
                    $('baterai').textContent = Math.round(battery.level * 100) + "% " + (battery.charging ? "(Charging)" : "");
                });
            });
        } else {
            $('baterai').textContent = "Tidak didukung";
            $('baterai').className = "";
        }
    });
    </script>

    <?php include '../inc/check_activity.php'; ?>
</body>
</html>
