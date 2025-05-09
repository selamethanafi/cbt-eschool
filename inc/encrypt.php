<?php 
// Enkripsi dan dekripsi simetris dengan openssl
$rahasia = "@dmin12345"; 
$method = "AES-256-CBC";
//$plaintext = "password";
//$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
//$encrypted = openssl_encrypt($plaintext, $method, $rahasia, 0, $iv);
//$final = base64_encode($iv . $encrypted);  //simpan ke database

//$encoded = $final; // Ambil dari database
//$decoded = base64_decode($encoded);
//$iv_length = openssl_cipher_iv_length($method);
//$iv2 = substr($decoded, 0, $iv_length);
//$encrypted_data = substr($decoded, $iv_length);
//$decrypted = openssl_decrypt($encrypted_data, $method, $rahasia, 0, $iv2);
?>