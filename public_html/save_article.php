<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $tags = $_POST['tags'] ?? [];
    $createdAt = date('Y-m-d H:i:s');

    // Essaye de récupérer image_url du formulaire
    $imageUrl = trim($_POST['image_url'] ?? '');

    // Sinon, extrait la première image du contenu Quill
    if (empty($imageUrl)) {
        if (preg_match('/<img[^>]+src="([^"]+)"/', $content, $matches)) {
            $imageUrl = $matches[1];
        }
    }

    // Insertion de l’article
    $stmt = $pdo->prepare("INSERT INTO articles (title, category, content, imageUrl, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $category, $content, $imageUrl, $createdAt]);
    $articleId = $pdo->lastInsertId();

    // Traitement des tags
    foreach ($tags as $tag) {
        $tag = trim($tag);
        if ($tag !== '') {
            // Vérifie si le tag existe déjà
            $stmt = $pdo->prepare("SELECT id FROM tags WHERE name = ?");
            $stmt->execute([$tag]);
            $tagId = $stmt->fetchColumn();

            if (!$tagId) {
                // Si le tag n’existe pas, on l’ajoute
                $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
                $stmt->execute([$tag]);
                $tagId = $pdo->lastInsertId();
            }

            // Lier le tag à l’article
            $stmt = $pdo->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$articleId, $tagId]);
        }
    }

    header('Location: index.php');
    exit;
} // 👈 CE } MANQUAIT !
