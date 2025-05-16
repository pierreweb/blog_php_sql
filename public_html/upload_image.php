
<?php
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$category = preg_replace('/[^a-z0-9_\-]/i', '', $_POST['category'] ?? 'uncategorized');
$date = date('Ymd_His');

if (!empty($_FILES['image']['name'])) {
    $originalName = basename($_FILES['image']['name']);
    $extension = pathinfo($originalName, PATHINFO_EXTENSION);

    // Nouveau nom de fichier
    $fileName = $category . '_' . $date . '.' . $extension;
    $targetFilePath = $targetDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)) {
        echo json_encode(['success' => true, 'url' => $targetFilePath]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Échec du téléversement']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucun fichier']);
}
