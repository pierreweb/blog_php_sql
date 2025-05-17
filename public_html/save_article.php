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
    // $category = trim($_POST['category'] ?? '');
    $tag = trim($_POST['tag'] ?? '');
    // $tags = $_POST['tags'] ?? [];

    // 🔄 Charger le fichier JSON (catégories + tags)
    $json = json_decode(file_get_contents('./datas/categoryandtags.json'), true) ?? [];
    $categories = $json['categories'] ?? [];
    $tags = $json['tags'] ?? [];

    // 🔥 Vérifier et enregistrer la catégorie

    // Récupère la "category" envoyée par Tagify (JSON string)
    $categoryRaw = trim($_POST['category'] ?? '');
    // On décode le JSON string envoyé par Tagify
    $decodedCategory = json_decode($categoryRaw, true);
    // Si le format est correct, on extrait la vraie valeur
    if (is_array($decodedCategory) && isset($decodedCategory[0]['value'])) {
        $category = trim($decodedCategory[0]['value']);
    } else {
        $category = ''; // ou une valeur par défaut
    }
    // Ajouter uniquement si la catégorie est nouvelle
    if (!in_array($category, $categories)) {
        $categories[] = $category;
        //sort($categories); // Optionnel
        // 🔠 Trie les catégories par ordre alphabétique
        sort($categories, SORT_NATURAL | SORT_FLAG_CASE);
        $json['categories'] = $categories;
        file_put_contents('./datas/categoryandtags.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // 🔥 Vérifier et enregistrer le tag
    // Récupère le "tag" envoyée par Tagify (JSON string)
    $tagRaw = trim($_POST['tag'] ?? '');
    // On décode le JSON string envoyé par Tagify
    $decodedTag = json_decode($tagRaw, true);
    // Si le format est correct, on extrait la vraie valeur
    if (is_array($decodedTag) && isset($decodedTag[0]['value'])) {
        $tag = trim($decodedTag[0]['value']);
    } else {
        $tag = ''; // ou une valeur par défaut
    }
    // Ajouter uniquement si la catégorie est nouvelle
    if (!in_array($tag, $tags)) {
        $tags[] = $tag;
        //sort($categories); // Optionnel
        // 🔠 Trie les tags par ordre alphabétique
        sort($tags, SORT_NATURAL | SORT_FLAG_CASE);
        $json['tags'] = $tags;
        file_put_contents('./datas/categoryandtags.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    // if (!in_array($tag, $tags)) {
    //     $decodedTags = json_decode($tag, true);
    //     if (is_array($decodedTags) && isset($decodedTags[0]['value'])) {
    //         $tag = $decodedTags[0]['value'];
    //     }

    //     $tags[] = $tag;
    //     $json['tags'] = $tags;
    //     file_put_contents('./datas/categoryandtags.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    // }

    // 🔥 Vérifier et formater les tags correctement
    // if (!is_array($tags)) {
    //     $tags = json_decode($tags, true) ?? [];
    // }

    // $cleanTags = [];
    // foreach ($tags as $tag) {
    //     if (is_array($tag) && isset($tag['value'])) {
    //         $cleanTags[] = trim($tag['value']);
    //     } else {
    //         $cleanTags[] = trim($tag);
    //     }
    // }


    // // $tagsString = implode(', ', $cleanTags); // 🔥 Transforme les tags en texte
    // $json['tags'] = array_unique(array_merge($existingTags, $cleanTags));

    // file_put_contents('./datas/categoryandtags.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));


    // 💾 Enregistrement article
    $content = trim($_POST['content'] ?? '');
    $createdAt = date('Y-m-d H:i:s');

    // 🖼️ Gestion de l’image principale
    $imageUrl = trim($_POST['image_url'] ?? '');
    if (empty($imageUrl)) {
        if (preg_match('/<img[^>]+src="([^"]+)"/', $content, $matches)) {
            $imageUrl = $matches[1];
        }
    }

    // 💾 Enregistrement article
    $stmt = $pdo->prepare("INSERT INTO articles (title, category, content, imageUrl, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $category, $content, $imageUrl, $createdAt]);
    $articleId = $pdo->lastInsertId();

    // 🏷️ Enregistrement des tags et association à l'article
    // foreach ($cleanTags as $tagName) {
    //     $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?) ON DUPLICATE KEY UPDATE name=name");
    //     $stmt->execute([$tagName]);

    //     $stmt = $pdo->prepare("SELECT id FROM tags WHERE name = ?");
    //     $stmt->execute([$tagName]);
    //     $tagId = $stmt->fetchColumn();

    //     // 🔥 Vérifier avant insertion si le lien `article_id - tag_id` existe déjà
    //     $stmt = $pdo->prepare("SELECT COUNT(*) FROM article_tags WHERE article_id = ? AND tag_id = ?");
    //     $stmt->execute([$articleId, $tagId]);
    //     $exists = $stmt->fetchColumn();

    //     if ($exists == 0) {
    //         $stmt = $pdo->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
    //         $stmt->execute([$articleId, $tagId]);
    //     }
    // }


    // 🏷️ Enregistrement des tags et association à l'article
    if (!empty($_POST['tag'])) {
        // 💡 Cas où Tagify envoie un JSON stringifié
        $decodedTags = json_decode($_POST['tag'], true);
        $cleanTags = [];

        foreach ($decodedTags as $tagObj) {
            if (isset($tagObj['value'])) {
                $cleanTags[] = trim($tagObj['value']);
            }
        }

        // 🔁 Traitement de chaque tag
        foreach ($cleanTags as $tagName) {
            if ($tagName === '') continue;

            // 🔄 Insertion ou récupération du tag
            $stmt = $pdo->prepare("INSERT INTO tags (name) VALUES (?) ON DUPLICATE KEY UPDATE name = name");
            $stmt->execute([$tagName]);

            // 🔍 Récupère l'ID du tag
            $stmt = $pdo->prepare("SELECT id FROM tags WHERE name = ?");
            $stmt->execute([$tagName]);
            $tagId = $stmt->fetchColumn();

            // ✅ Vérifie si l'association article-tag existe déjà
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM article_tags WHERE article_id = ? AND tag_id = ?");
            $stmt->execute([$articleId, $tagId]);
            $exists = $stmt->fetchColumn();

            // ➕ Sinon, crée l'association
            if ($exists == 0) {
                $stmt = $pdo->prepare("INSERT INTO article_tags (article_id, tag_id) VALUES (?, ?)");
                $stmt->execute([$articleId, $tagId]);
            }
        }
    }


    // 🔄 Redirection vers la page d'accueil
    header('Location: index.php');
    exit;
}
