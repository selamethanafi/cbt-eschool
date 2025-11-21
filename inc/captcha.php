<?php
session_start();

$code = substr(str_shuffle('ABCDEFGHJKLMNPQRSTWXYZ123456789'), 0, 5);
$_SESSION['captcha'] = $code;

header("Content-type: image/png");

$width = 200;
$height = 70;
$image = imagecreatetruecolor($width, $height);

// Warna untuk background terang
$bg_color = imagecolorallocate($image, 240, 240, 240);   // background abu sangat terang
$text_color = imagecolorallocate($image, 30, 30, 30);     // teks gelap hampir hitam

imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Garis acak warna abu gelap (40-90)
for ($i = 0; $i < 12; $i++) {
    $line_color = imagecolorallocate($image, rand(40, 90), rand(40, 90), rand(40, 90));
    imageline($image, rand(0, $width), rand(0, $height),
              rand(0, $width), rand(0, $height), $line_color);
}

// Titik noise warna abu gelap (50-100)
for ($i = 0; $i < 200; $i++) {
    $dot_color = imagecolorallocate($image, rand(50, 100), rand(50, 100), rand(50, 100));
    imagesetpixel($image, rand(0, $width), rand(0, $height), $dot_color);
}

$font = __DIR__ . '/../assets/fonts/Roboto-Bold.ttf';
$font_size = 26;
imagettftext($image, $font_size, 0, 30, 50, $text_color, $font, $code);

imagepng($image);
imagedestroy($image);
?>
