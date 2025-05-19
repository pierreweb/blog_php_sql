<?php
require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$tag_id = $_GET['tag_id'] ?? null;
if (!$tag_id) {
    echo "Tag introuvable.";
    exit;
}

// Récupère le nom du tag
$stmt = $pdo->prepare("SELECT name FROM tags WHERE id = ?");
$stmt->execute([$tag_id]);
$tagName = $stmt->fetchColumn();

if (!$tagName) {
    echo "Tag non trouvé.";
    exit;
}

// Récupère les articles liés à ce tag
$stmt = $pdo->prepare("
    SELECT a.id, a.title, a.created_at
    FROM articles a
    JOIN article_tags at ON a.id = at.article_id
    WHERE at.tag_id = ?
    ORDER BY a.created_at DESC
");
$stmt->execute([$tag_id]);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Articles avec le tag « <?= htmlspecialchars($tagName) ?> »</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">

    <script>
        // Applique automatiquement le thème sauvegardé dans localStorage
        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('currentTheme');
            if (savedTheme) {
                const themeLink = document.getElementById('theme-style');
                if (themeLink) {
                    themeLink.href = savedTheme;
                }
            }
        });
    </script>
</head>

<body>
    <!--   <header>
        <h1>Conan Blog</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="page_tags.php">Tags</a></li>
                <li><a href="gallery.php">Galerie</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header> -->

    <main>

        <h1>Articles avec le tag « <?= htmlspecialchars($tagName) ?> »</h1>
        <ul>
            <?php foreach ($articles as $article): ?>
                <li>
                    <a href="article.php?id=<?= $article['id'] ?>">
                        <?= htmlspecialchars($article['title']) ?> (<?= (new DateTime($article['created_at']))->format('d/m/Y') ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </main>
    <p><a href="index.php">Retour à l'accueil</a></p>
    <p><a href="page_tags.php">Retour à la liste des tags</a></p>