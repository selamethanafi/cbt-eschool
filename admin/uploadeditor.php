<?php
if ($_FILES['file']['name']) {
    $allowedMimeTypes = ['image/gif', 'image/jpeg', 'image/png'];
    $allowedExtensions = ['gif', 'jpg', 'jpeg', 'png'];

    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = mime_content_type($fileTmpName);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (in_array($fileType, $allowedMimeTypes) && in_array($fileExt, $allowedExtensions) && $fileSize <= 1024 * 1024) {
        $uploadDir = '../gambar/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $newFileName = uniqid() . '.' . $fileExt;
        $filePath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Resize image max 3000px
            list($width, $height) = getimagesize($filePath);
            $maxDim = 5000;

            if ($width > $maxDim || $height > $maxDim) {
                if ($width > $height) {
                    $newWidth = $maxDim;
                    $newHeight = $height * ($maxDim / $width);
                } else {
                    $newHeight = $maxDim;
                    $newWidth = $width * ($maxDim / $height);
                }

                $thumb = imagecreatetruecolor($newWidth, $newHeight);

                switch ($fileType) {
                    case 'image/jpeg':
                        $source = imagecreatefromjpeg($filePath);
                        break;
                    case 'image/png':
                        $source = imagecreatefrompng($filePath);
                        break;
                    case 'image/gif':
                        $source = imagecreatefromgif($filePath);
                        break;
                    default:
                        echo json_encode(['error' => 'Unsupported image type']);
                        exit;
                }

                imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                switch ($fileType) {
                    case 'image/jpeg':
                        imagejpeg($thumb, $filePath);
                        break;
                    case 'image/png':
                        imagepng($thumb, $filePath);
                        break;
                    case 'image/gif':
                        imagegif($thumb, $filePath);
                        break;
                }

                imagedestroy($thumb);
                imagedestroy($source);
            }
//$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
//$host = $_SERVER['HTTP_HOST'];
//$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
//$basePath = rtrim(dirname($scriptPath, 1), '/');
// Return correct URL path for browser
//$publicPath = $protocol . $host . $basePath . '/gambar/' . $newFileName;
//$imgTag = '<img id="gbrsoal" src="' . $publicPath . '">';
//echo json_encode(['img' => $imgTag, 'url' => $publicPath]);
$relativePath = '../gambar/' . $newFileName;
$imgTag = '<img id="gbrsoal" src="' . $relativePath . '" style="width: 100%;">';
echo json_encode(['img' => $imgTag, 'url' => $relativePath]);
        } else {
            echo json_encode(['error' => 'Failed to move uploaded file']);
        }
    } else {
        echo json_encode(['error' => 'Invalid file type or size']);
    }
} else {
    echo json_encode(['error' => 'No file uploaded']);
}
?>