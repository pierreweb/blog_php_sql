<?php
$jsonFile = './datas/categoryandtags.json';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTag = trim($_POST['tag'] ?? '');

    if ($newTag !== '') {
        $data = json_decode(file_get_contents($jsonFile), true);
        if (!in_array($newTag, $data['tags'])) {
            $data['tags'][] = $newTag;
            file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Tag déjà présent']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Tag vide']);
    }
}
