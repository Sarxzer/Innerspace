<?php
session_start();

$_SESSION['user_id'] ++; // for testing, increment user_id on each upload to simulate different users

require __DIR__ . '/../vendor/autoload.php';

use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\Format;
use Intervention\Image\ImageManager;

$manager = new ImageManager(new Driver());

// 🔒 Basic checks
if (!isset($_FILES['pfp'])) {
    http_response_code(400);
    exit("No file");
}

$file = $_FILES['pfp'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    exit("Upload error");
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);

if (!str_starts_with($mime, 'image/')) {
    http_response_code(400);
    exit("Not an image");
}

// 👤 however you store user id
$userId = $_SESSION['user_id'];

$pfpDir = __DIR__ . "/uploads/pfps/$userId";
if (!is_dir($pfpDir)) {
    mkdir($pfpDir, 0755, true);
}

$outputPath = "$pfpDir/$userId.webp";

try {
    $image = $manager->decodePath($file['tmp_name']);

    // resize and scale the image to 256x256, no cropping
    $image
        ->cover(256, 256)
        ->save($outputPath);

    $image->encodeUsingFormat(Format::WEBP, 80)->save($outputPath);

    echo "PFP updated!";
} catch (Exception $e) {
    http_response_code(400);
    echo "Image processing failed: " . $e->getMessage();
}