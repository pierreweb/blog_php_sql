<?php
require 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Article non trouvé.";
    exit;
}

$id = (int) $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
$stmt->execute(['id' => $id]);
$article = $stmt->fetch();

if (!$article) {
    echo "Article introuvable.";
    exit;
}

$date = (new DateTime($article['created_at']))->format('d/m/Y');
$catagory = (new DateTime($article['created_at']))->format('d/m/Y');
$cat = ($article['category']);
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['title']) ?> - Conan Blog</title>

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

<body class="article-page">

    <div class="container">
        <h1><?= htmlspecialchars($article['title']) ?></h1>
        <div class="article-meta">
            <p class="date">Publié le <?= $date ?> </p>
            <p class="date">Catégorie:<?= $cat ?> </p>
            <p class="date">Tags:<?= $cat ?> </p>

        </div>
        <!-- <p class="date">Publié le <?= $date ?></p> -->
        <!-- <?php if ($article['imageUrl']) : ?>
            <img src="<?= htmlspecialchars($article['imageUrl']) ?>" alt="Image article" class="postimg">
        <?php endif; ?> -->
        <div class="content">
            <?= nl2br($article['content']) ?>
        </div>
        <?php if (isset($_SESSION['admin'])):
        ?>
            <a href="delete_article.php?id=<?= $article['id'] ?>" onclick="return confirm('Supprimer cet article ?');" style="color: red;">Supprimer</a>
        <?php endif;
        ?>
        <p><a href="index.php">← Retour au blog</a></p>
    </div>
</body>

</html>