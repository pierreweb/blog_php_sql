<?php
header('Content-Type: application/json');
// Taille max : 5 Mo
$maxSize = 5 * 1024 * 1024; // 5 Mo

if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'Fichier non valide']);
    exit;
}


print_r("upload");

$category = $_POST['category'] ?? 'autre';
$tmpName = $_FILES['image']['tmp_name'];
$originalName = $_FILES['image']['name'];
$fileSize = $_FILES['image']['size'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $tmpName);
finfo_close($finfo);

// Vérifie taille
if ($fileSize > $maxSize) {
    echo json_encode(['success' => false, 'error' => 'Fichier trop lourd (max 5 Mo)']);
    exit;
}

// Vérifie le type MIME
$allowedTypes = ['image/jpeg', 'image/png'];
if (!in_array($mime, $allowedTypes)) {
    echo json_encode(['success' => false, 'error' => 'Type de fichier non supporté']);
    exit;
}

// Crée le répertoire si besoin
$uploadDir = __DIR__ . '/uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Nouveau nom basé sur catégorie et date
$timestamp = date('Ymd_His');
$filename = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $category)) . "_$timestamp.webp";
$destination = "$uploadDir/$filename";

// Création de l’image GD et conversion WebP
switch ($mime) {
    case 'image/jpeg':
        $image = imagecreatefromjpeg($tmpName);
        break;
    case 'image/png':
        $image = imagecreatefrompng($tmpName);
        break;
    default:
        echo json_encode(['success' => false, 'error' => 'Erreur de format']);
        exit;
}

if (!$image) {
    echo json_encode(['success' => false, 'error' => 'Échec de lecture de l’image']);
    exit;
}

// Conversion en WebP
if (!imagewebp($image, $destination, 80)) {
    echo json_encode(['success' => false, 'error' => 'Échec de conversion en WebP']);
    exit;
}
imagedestroy($image);

// Retourne l'URL accessible
$imageUrl = '/uploads/' . $filename;
echo json_encode(['success' => true, 'url' => $imageUrl]);
print_r($imageUrl);
