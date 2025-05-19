<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'config.php';

// Récupération des tags et du nombre d'articles associés
$stmt = $pdo->query("
    SELECT tags.id, tags.name, COUNT(article_tags.article_id) AS article_count
    FROM tags
    LEFT JOIN article_tags ON tags.id = article_tags.tag_id
    GROUP BY tags.id, tags.name
    ORDER BY article_count DESC, tags.name ASC
");
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Trouve le max pour le calcul de taille
$maxCount = 0;
foreach ($tags as $tag) {
    if ($tag['article_count'] > $maxCount) {
        $maxCount = $tag['article_count'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des tags</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="assets/tags.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">

    <script>
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
    <main>
        <div style="text-align: center; margin: 20px;">
            <h1>Liste des tags</h1>
        </div>

        <p>Voici la liste de tous les tags disponibles sur le blog :</p>

        <div class="tag-cloud">
            <?php
            $minSize = 0.8;
            $maxSize = 2.0;
            foreach ($tags as $tag):
                $ratio = $maxCount > 0 ? $tag['article_count'] / $maxCount : 0;
                $fontSize = $minSize + ($maxSize - $minSize) * $ratio;
            ?>
                <a href="tag.php?tag=<?= urlencode($tag['name']) ?>" style="font-size: <?= round($fontSize, 2) ?>em;">
                    <?= htmlspecialchars($tag['name']) ?> (<?= $tag['article_count'] ?>)


                <?php endforeach; ?>
        </div>
    </main>

    <p><a href="index.php">Retour à l'accueil</a></p>
</body>

</html>