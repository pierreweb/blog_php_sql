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

    //$title = $_POST['title'] ?? '';
    $category = $_POST['category'] ?? '';
    // $content = $_POST['content'] ?? '';
    $tags = $_POST['tags'] ?? [];


    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $createdAt = date('Y-m-d H:i:s');
    $imageUrl = trim($_POST['image_url'] ?? ''); // Définit une valeur par défaut si l’image est absente


    // Insérer l'article


    if ($imageUrl) {
        $content .= "<img src='$imageUrl' alt='Image de l\'article'>";
    }

    $stmt = $pdo->prepare("INSERT INTO articles (title, category, content, imageUrl) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $category, $content, $imageUrl]);
    $articleId = $pdo->lastInsertId();





    // Insérer les tags et les lier à l'article
    foreach ($tags as $tag) {
        $tag = trim($tag);
        if ($tag !== '') {
            // Vérifier si le tag existe déjà
            $stmt = $pdo->prepare("INSERT INTO articles (title, category, content, imageUrl) VALUES (?, ?, ?, ?)");
            $stmt->execute([$title, $category, $content, $imageUrl]);

            $tagId = $stmt->fetchColumn();

            if (!$tagId) {
                // Insérer le nouveau tag
                $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?)");
                $stmt->execute([$tag]);
                $tagId = $pdo->lastInsertId();
            }

            // Lier le tag à l'article
            $stmt = $pdo->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$articleId, $tagId]);
        }
    }

    //header("Location: articles.php");
    header('Location: index.php');
    exit;
}
