<?php
include '../koneksi/koneksi.php'; // Pastikan koneksi sudah ada
include_once '../inc/encrypt.php'; // berisi $method dan $rahasia

$pengaturan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_aplikasi FROM pengaturan WHERE id = 1"));

// Fungsi untuk pengecekan login user
function check_login($role) {
    global $koneksi;

    // Daftar role yang valid
    $valid_roles = ['admin', 'siswa'];

    // Memastikan role yang diberikan valid
    if (!in_array($role, $valid_roles)) {
        die("Invalid role.");
    }

    // Mengecek apakah pengguna sudah login
    if (!isset($_SESSION[$role . '_logged_in']) || $_SESSION[$role . '_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }

}

// Fungsi untuk autentikasi user pakai mysqli
function authenticate_user($username, $password_input, $role) {
    global $koneksi;

    if (empty($username) || empty($password_input)) {
        return false;
    }

    $table = ($role == 'admin') ? 'admins' : 'siswa';
    $query = "SELECT * FROM $table WHERE username = ?";

    if (!$stmt = mysqli_prepare($koneksi, $query)) {
        die("Database query preparation failed: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $stored_password = $user['password'];

        if ($role === 'admin') {
            if (verify_admin_password($password_input, $stored_password)) {
                $_SESSION[$role . '_logged_in'] = true;
                $_SESSION[$role . '_id'] = $user['id'];
                return true;
            }
        } else {
            // Cek apakah siswa dipaksa logout oleh admin
            if (!empty($user['force_logout'])) {
                // Reset force_logout dan session_token
                mysqli_query($koneksi, "UPDATE siswa SET force_logout = FALSE, session_token = NULL WHERE id_siswa = " . $user['id_siswa']);
                return false;
            }

            $settings = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT login_ganda FROM pengaturan WHERE id = 1"));
            $allow_multiple = ($settings['login_ganda'] == 'izinkan');

            if (!$allow_multiple && !empty($user['session_token'])) {
                return false;
            }

            if (verify_siswa_password($password_input, $stored_password)) {
                $session_token = bin2hex(random_bytes(32));
                $update = mysqli_prepare($koneksi, "UPDATE $table SET session_token = ?, force_logout = FALSE WHERE id_siswa = ?");
                mysqli_stmt_bind_param($update, "si", $session_token, $user['id_siswa']);
                mysqli_stmt_execute($update);

                $_SESSION[$role . '_logged_in'] = true;
                $_SESSION[$role . '_id'] = $user['id_siswa'];
                $_SESSION[$role . '_token'] = $session_token;
                return true;
            }
        }
    }

    return false;
}

// Fungsi untuk memverifikasi password admin
function verify_admin_password($password_input, $stored_password) {
    return password_verify($password_input, $stored_password);
}

// Fungsi untuk memverifikasi password siswa
function verify_siswa_password($password_input, $stored_password) {
    global $method, $rahasia;

    if (empty($password_input) || empty($stored_password)) {
        return false;
    }

    $decoded = base64_decode($stored_password);
    $iv_length = openssl_cipher_iv_length($method);
    $iv = substr($decoded, 0, $iv_length);
    $ciphertext = substr($decoded, $iv_length);
    $decrypted_password = openssl_decrypt($ciphertext, $method, $rahasia, 0, $iv);

    if ($decrypted_password === false) {
        error_log("Failed to decrypt password.");
        return false;
    }

    return ($decrypted_password === $password_input);
}


// Fungsi untuk mendapatkan informasi kredensial yang terenkripsi
function get_encrypted_credit() {
    global $koneksi;

    $query = "SELECT encrypt FROM profil WHERE id = 1";  // Ganti dengan ID yang sesuai
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        return $row['encrypt'];
    }

    return null;
}
function cari_semester()
{
	$tahuny = date("Y");
	$bulany = date("m");
	$tanggaly = date("d");
	if (($bulany=='07') or ($bulany=='08') or ($bulany=='09') or ($bulany=='10') or ($bulany=='11') or ($bulany=='12'))
	{
		$semester= '1';
	}
	else
	{
		$semester= '2';
	}
	//$semester='2';
	return $semester;
}
function cari_thnajaran()
	{

		$tahuny = date("Y");
		$bulany = date("m");
		$tanggaly = date("d");
		if (($bulany=='07') or ($bulany=='08') or ($bulany=='09') or ($bulany=='10') or ($bulany=='11') or ($bulany=='12'))
		{
			$tahuny2 = $tahuny+1;
			$thnajaran = ''.$tahuny;
		}
		else
		{
			$tahuny1 = $tahuny-1;
			$thnajaran = ''.$tahuny1;
		}
		//$thnajaran = '2018/2019';
		return $thnajaran;
	}
function cegah($str) 
	{
	$str = preg_replace("/'/", "xpsijix", $str);
	$str = preg_replace("/`/", "xpiringx", $str);
	$str = preg_replace("/-/", "xdashx", $str);
	$str = preg_replace("/\//", "xmiringx", $str);
	$str = preg_replace("/@/", "xtkeongx", $str);
	$str = preg_replace("/%/", "xpersenx", $str);
	$str = preg_replace("/_/", "xgwahx", $str);
	$str = preg_replace("/1=1/", "x1smdgan1x", $str);
	$str = str_replace("/", "xgmringx", $str);
	$str = preg_replace("/!/", "xpentungx", $str);
	$str = str_replace("<", "xkkirix", $str);
	$str = preg_replace("/>/", "xkkananx", $str);
	$str = preg_replace("/{/", "xkkurix", $str);
	$str = preg_replace("/}/", "xkkurnanx", $str);
	$str = preg_replace("/;/", "xkommax", $str);
	$str = preg_replace("/-/", "xstrix", $str);
	$str = preg_replace("/_/", "xstripbwhx", $str);
	$str = preg_replace("/ /", "xspasix", $str);
	$str = preg_replace("/\(/", "xkubux", $str);
	$str = preg_replace("/\)/", "xkutux", $str);
	$str = preg_replace("/,/", "xkomax", $str);
	return $str;
  	}
function balikin($str) 
	{
	$str = preg_replace("/xpiringx/", "`", $str);
	$str = preg_replace("/xdashx/", "-", $str);
	$str = preg_replace("/xpersenx/", "%", $str);
	$str = preg_replace("/xtkeongx/", "@", $str);
	$str = preg_replace("/xgwahx/", "_", $str);
	$str = preg_replace("/xmiringx/", "/", $str);
	$str = preg_replace("/x1smdgan1x/", "1=1", $str);
	$str = preg_replace("/xgmringx/", "/", $str);
	$str = preg_replace("/xpentungx/", "!", $str);
	$str = preg_replace("/xpsijix/", "'", $str);
	$str = preg_replace("/xkkirix/", "<", $str);
	$str = preg_replace("/xkkananx/", ">", $str);
	$str = preg_replace("/xkkurix/", "{", $str);
	$str = preg_replace("/xkkurnanx/", "}", $str);
	$str = preg_replace("/xkommax/", ";", $str);
	$str = preg_replace("/xstrix/", "-", $str);
	$str = preg_replace("/xstripbwhx/", "_", $str);
	$str = preg_replace("/ koma /", ",", $str);
	$str = preg_replace("/xspasix/", " ", $str);
	$str = preg_replace("/xkubux/", "(", $str);
	$str = preg_replace("/xkutux/", ")", $str);
	$str = preg_replace("/xkomax/", ",", $str);
	$str = str_replace(CHR(13), "", $str);
	$str = str_replace(CHR(10) & CHR(10), "</P><P>", $str);
	$str = str_replace(CHR(10), "<BR>", $str);
	return $str;
  	}  		
// Ambil teks terenkripsi
$encryptedText = get_encrypted_credit();
?>
