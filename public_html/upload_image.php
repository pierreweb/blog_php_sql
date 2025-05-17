<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Créer le dossier upload si nécessaire
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];

    if ($file['error'] === 0) {
        $tmpPath = $file['tmp_name'];
        $originalName = pathinfo($file['name'], PATHINFO_FILENAME);
        $webpName = uniqid($originalName . '_') . '.webp';
        $webpPath = $uploadDir . $webpName;

        // Vérifie et convertit en image WebP
        $imageInfo = getimagesize($tmpPath);
        $mime = $imageInfo['mime'];

        switch ($mime) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($tmpPath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($tmpPath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($tmpPath);
                break;
            default:
                $response = ['success' => false, 'error' => 'Format non supporté'];
                echo json_encode($response);
                exit;
        }

        // Sauvegarde au format WebP
        if (imagewebp($image, $webpPath, 80)) {
            imagedestroy($image);
            $response = [
                'success' => true,
                'url' => $webpPath
            ];
        } else {
            $response = ['success' => false, 'error' => 'Erreur conversion WebP'];
        }
    } else {
        $response = ['success' => false, 'error' => 'Erreur lors de l’envoi du fichier'];
    }
} else {
    $response = ['success' => false, 'error' => 'Aucune image reçue'];
}

header('Content-Type: application/json');
echo json_encode($response);
