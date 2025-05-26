<?php
// install/step2.php
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Instalasi CBT - Step 2</title>
    <link href="../assets/bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="icon" type="image/png" href="../assets/images/icon.png" />
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-top: 50px;
        }
        .card {
            max-width: 480px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
        }
        .card-header {
            background: #007bff;
            color: #fff;
            border-radius: 12px 12px 0 0;
            text-align: center;
            font-weight: 600;
            font-size: 1.3rem;
        }
        .btn-primary {
            width: 100%;
            font-weight: 600;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="card shadow-sm">
        <div class="card-header">Setup Database & Admin CBT</div>
        <div class="card-body">
            <form method="POST" action="step3.php" id="installForm" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="host" class="form-label">Database Host</label>
                    <input type="text" id="host" name="host" class="form-control" placeholder="localhost" value="localhost" required />
                </div>
                <div class="mb-3">
                    <label for="dbname" class="form-label">Nama Database</label>
                    <input type="text" id="dbname" name="dbname" class="form-control" placeholder="cbt_db" required />
                </div>
                <div class="mb-3">
                    <label for="user" class="form-label">Username Database</label>
                    <input type="text" id="user" name="user" class="form-control" placeholder="root" required />
                </div>
                <div class="mb-3">
                    <label for="pass" class="form-label">Password Database</label>
                    <input type="password" id="pass" name="pass" class="form-control" placeholder="Password DB (kosongkan jika tidak ada)" />
                </div>
                <hr />
                <h5 class="mb-3">Buat Akun Admin Panel</h5>
                <div class="mb-3">
                    <label for="admin_user" class="form-label">Username Admin</label>
                    <input type="text" id="admin_user" name="admin_user" class="form-control" placeholder="admin" required />
                </div>
                <div class="mb-3">
                    <label for="nama_admin" class="form-label">Nama Admin</label>
                    <input type="text" id="nama_admin" name="nama_admin" class="form-control" placeholder="Nama lengkap admin" required />
                </div>
                <div class="mb-3">
                    <label for="admin_pass" class="form-label">Password Admin</label>
                    <input type="password" id="admin_pass" name="admin_pass" class="form-control" placeholder="Password admin" required />
                </div>
                <button type="submit" class="btn btn-primary">Mulai Instalasi</button>
            </form>
        </div>
    </div>

    <script>
        function validateForm() {
            const adminUser = document.getElementById('admin_user').value.trim();
            const namaAdmin = document.getElementById('nama_admin').value.trim();
            const adminPass = document.getElementById('admin_pass').value.trim();

            if (adminUser.length < 3) {
                alert('Username admin minimal 3 karakter');
                return false;
            }
            if (namaAdmin.length < 3) {
                alert('Nama admin minimal 3 karakter');
                return false;
            }
            if (adminPass.length < 5) {
                alert('Password admin minimal 5 karakter');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
