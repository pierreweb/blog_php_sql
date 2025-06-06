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
// Récupération  tag
$articleId = $article['id'];
$stmt = $pdo->prepare("
    SELECT t.name 
    FROM tags t
    INNER JOIN article_tags at ON t.id = at.tag_id
    WHERE at.article_id = ?
");
$stmt->execute([$articleId]);
$tags = $stmt->fetchAll(PDO::FETCH_COLUMN);




$date = (new DateTime($article['created_at']))->format('d/m/Y');
//$catagory = (new DateTime($article['created_at']))->format('d/m/Y');
$cat = ($article['category']);
$tag = implode(', ', $tags); // Convertit le tableau de tags en chaîne de caractères
//$tag = ($tag);

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

<body class="article">
    <!-- <body class="article-page"> -->

    <div class="container">
        <div class="article_title">
            <h1><?= htmlspecialchars($article['title']) ?></h1>
        </div>
        <div class="article-meta">
            <p class="date">Publié le <?= $date ?> </p>
            <p class="category">Catégorie:<?= $cat ?> </p>
            <p class="tag">Tags:<?= $tag ?> </p>

        </div>

        <div class="article_content">
            <?php
            $content = nl2br($article['content']);
            // Ajout de la classe et de l'appel JS
            $content = str_replace(
                '<img',
                '<img class="postimg" onclick="openLightbox(this.src)"',
                $content
            );
            echo $content;
            ?>
        </div>


        <?php if (isset($_SESSION['admin'])):
        ?>
            <a href="delete_article.php?id=<?= $article['id'] ?>" onclick="return confirm('Supprimer cet article ?');" style="color: red;">Supprimer</a>
        <?php endif;
        ?>
        <p><a href="index.php">← Retour au blog</a></p>
    </div>
    <div id="lightbox-overlay" onclick="this.style.display='none'">
        <img id="lightbox-img" src="" alt="Image agrandie">
    </div>
    <script src="assets/lightbox.js"></script>
</body>



</html>