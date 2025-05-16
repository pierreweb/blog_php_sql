<?php
header('Content-Type: application/json');

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No valid image uploaded']);
    exit;
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$fileType = mime_content_type($_FILES['image']['tmp_name']);

if (!in_array($fileType, $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file type']);
    exit;
}

$uploadDir = __DIR__ . '/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
$filename = uniqid('img_', true) . '.' . $ext;
$destination = $uploadDir . $filename;

if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
    $url = '/uploads/' . $filename;
    echo json_encode(['success' => true, 'url' => $url]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save image']);
}
