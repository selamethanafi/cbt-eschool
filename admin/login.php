<?php
session_start();
include '../koneksi/koneksi.php';
include '../inc/functions.php';

$error = '';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if (empty($_SESSION['captcha_question'])) {
    $a = rand(1, 9);
    $b = rand(1, 9);
    $_SESSION['captcha_question'] = "$a + $b";
    $_SESSION['captcha_answer'] = $a + $b;
}
// Redirect jika sudah login
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $captcha_input = $_POST['captcha'] ?? '';

    // Validasi CAPTCHA terlebih dahulu
    if ((int)$captcha_input !== $_SESSION['captcha_answer']) {
        $error = 'Captcha salah!';
    } else {
        // CAPTCHA benar, lanjutkan cek username dan password
        if (authenticate_user($username, $password, 'admin')) {
            unset($_SESSION['captcha_question'], $_SESSION['captcha_answer']);
            header("Location: dashboard.php");
            exit;
        } else {
            $error = 'Username atau password salah!';
        }

        // Regenerasi captcha setelah proses login selesai
        $a = rand(1, 9);
        $b = rand(1, 9);
        $_SESSION['captcha_question'] = "$a + $b";
        $_SESSION['captcha_answer'] = $a + $b;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Login</title>
  <?php include '../inc/css.php'; ?>
  <style>
    body {
        background: #121212;
    }
    .glass-card {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 15px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: white;
    padding: 2rem;
    max-width: 100%;
    margin: auto;
}
    input, input::placeholder {
        color: white !important;
    }
    input {
        background: transparent !important;
        border: none;
        border-bottom: 1px solid grey;
        border-radius: 0;
    }
    input:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: grey !important;
    }
    label {
        color: white;
    }
    @media (max-width: 576px) {
    .glass-card {
        padding: 1.5rem;
    }

    .glass-card input {
        font-size: 14px;
    }
}
  </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="height: 100vh;">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4"> 
      <div class="position-relative">
    <!-- Pita label -->
    <div style="
        position: absolute;
        top: -12px;
        left: -12px;
        background-color:rgb(253, 129, 13);
        color: white;
        padding: 6px 12px;
        font-weight: bold;
        border-radius: 5px 0 5px 0;
        font-size: 13px;
        z-index: 10;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    ">
        Login Admin
    </div>
        <div class="card shadow p-4 glass-card">
          <center><img src="../assets/images/codelite2.png" width="200" height="auto"></center>
          <?php if (!empty($error)): ?>
            <div id="customAlert" class="text-danger text-center my-3" role="alert" style="font-weight: bold;">
              <?php echo htmlspecialchars($error); ?>
            </div>
          <?php endif; ?>
          <form action="" method="POST" class="mt-3" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="mb-3">
              <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3 position-relative">
              <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
              <span class="position-absolute top-50 end-0 translate-middle-y me-2" style="cursor:pointer;" onclick="togglePassword()">
                <i style="color:grey;" class="fa fa-eye" id="togglePasswordIcon"></i>
              </span>
            </div>
            <div class="mb-3">
              <label for="captcha" class="form-label">
                Berapa hasil dari: <b><?php echo $_SESSION['captcha_question']; ?></b> ?
              </label>
              <input type="number" class="form-control" id="captcha" name="captcha" placeholder="Jawaban" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="loginButton">Login <i class="fa fa-sign-in"></i></button>
          </form><br>
          <div id="enc" style="font-size:13px;">
            <p></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- JavaScript -->
  <script src="../assets/bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
  <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    setTimeout(() => {
        const alert = document.getElementById('customAlert');
        if (alert) {
            alert.style.transition = "opacity 0.5s ease-out";
            alert.style.opacity = 0;
            setTimeout(() => alert.remove(), 500);
        }
    }, 4000);

    document.addEventListener("DOMContentLoaded", function() {
      var base64Text = "<?php echo $encryptedText; ?>"; 
      if(base64Text) {
        var decodedText = atob(base64Text); 
        document.getElementById("enc").innerHTML = decodedText; 
      }
    });

    function checkIfEncDeleted() {
        var encElement = document.getElementById("enc");

        if (!encElement) {
            var loginButton = document.getElementById("loginButton");
            loginButton.disabled = true;  
            loginButton.style.cursor = "not-allowed";  
            loginButton.style.opacity = "0.6";  

            window.location.href = "../error_page.php";  
        }
    }

    setInterval(checkIfEncDeleted, 500);

    document.getElementById("loginForm").addEventListener("submit", function(event) {
        var loginButton = document.getElementById("loginButton");
        if (loginButton.disabled) {
            event.preventDefault(); 
        }
    });
  </script>
</body>
</html>