<?php
if ($_FILES['file']['name']) {
    $allowedMimeTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/GIF', 'image/JPG', 'image/PNG', 'image/JPEG'];
    $allowedExtensions = ['gif', 'jpg', 'jpeg', 'png', 'GIF', 'JPG', 'JPEG', 'PNG'];

    // Get file info
    $fileName = $_FILES['file']['name'];
    $fileTmpName = $_FILES['file']['tmp_name'];
    $fileSize = $_FILES['file']['size'];
    $fileType = mime_content_type($fileTmpName);
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Validate file type and size
    if (in_array($fileType, $allowedMimeTypes) && in_array($fileExt, $allowedExtensions) && $fileSize <= 3024 * 3024) {
        $uploadDir = '../gambar/';
        $filePath = $uploadDir . uniqid() . '.' . $fileExt;

        // Move file to upload directory
        if (move_uploaded_file($fileTmpName, $filePath)) {
            // Resize image to fit within 200x200 while maintaining aspect ratio
            list($width, $height) = getimagesize($filePath);
            $maxDim = 3000;

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
                case 'image/JPEG':
                case 'image/jpg':
                case 'image/JPG':
                    $source = imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                case 'image/PNG':
                    $source = imagecreatefrompng($filePath);
                    break;
                case 'image/gif':
                case 'image/GIF':
                    $source = imagecreatefromgif($filePath);
                    break;
                default:
                    $source = imagecreatefromjpeg($filePath);
                    break;
            }

            imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            switch ($fileType) {
                case 'image/jpeg':
                case 'image/JPEG':
                case 'image/jpg':
                case 'image/JPG':
                    imagejpeg($thumb, $filePath);
                    break;
                case 'image/png':
                case 'image/PNG':
                    imagepng($thumb, $filePath);
                    break;
                case 'image/gif':
                case 'image/GIF':
                    imagegif($thumb, $filePath);
                    break;
            }

            imagedestroy($thumb);
            imagedestroy($source);

            // Return the file URL
            echo json_encode(['url' => $filePath]);
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