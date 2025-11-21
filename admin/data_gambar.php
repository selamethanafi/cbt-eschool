<?php
$directory = "../gambar/";
$files = array_diff(scandir($directory), array('..', '.')); // Ambil file selain '.' dan '..'

// Daftar ekstensi file gambar yang valid
$validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// Filter hanya file gambar
$images = array_filter($files, function($file) use ($validExtensions, $directory) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($ext, $validExtensions) && is_file($directory . $file);
});

// Urutkan berdasarkan waktu modifikasi (filemtime) terbaru
usort($images, function($a, $b) use ($directory) {
    return filemtime($directory . $b) - filemtime($directory . $a); // Urutkan terbaru di atas
});

// Ambil data gambar dan format sebagai JSON
$data = [];
foreach ($images as $image) {
    $timestamp = filemtime($directory . $image);
    $uploadDate = date("d-m-Y H:i:s", $timestamp); // Format tanggal: dd-mm-yyyy hh:mm:ss
    $data[] = [
        'checkbox' => "<input type='checkbox' class='checkbox-delete' value='$image'>",
        'preview' => "<img src='../gambar/$image' width='100' alt='$image'>",
        'upload_date' => $uploadDate
    ];
}

// Mengembalikan data dalam format JSON dengan kolom yang sesuai
echo json_encode(['data' => $data]);
?>
