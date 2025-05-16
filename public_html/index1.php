<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "Test affichage : La Quête du Feu";

require 'config.php';
$req = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");
$articles = $req->fetchAll();
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Conan the Barbarian Blog</title>
    <link rel="icon" href="images/favicon.png" type="image/x-icon">

    <!--  <link rel="stylesheet" href="assets/style.css"> Lien vers ton fichier CSS -->

    <!-- <link rel="stylesheet" href="assets/style1.css?v=2"> -->
    <!-- <link rel="stylesheet" href="assets/article-style.css?v=2"> -->
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">


    <link id="theme-style" rel="stylesheet" href="">



    <!-- Importation de Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Importation de Open Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <!-- Importation de Lato -->
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <!-- Importation de Cinzel -->
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
    <!--livereload pour mettre à jour automatiquement le navigateur-->
    <!-- <script src="assets/reload.js"></script> -->


</head>



<body>
    <button id="change-theme-btn">Changer le style Conan</button>
    <div class="container">
        <header class="header">
            <!-- <img src="/images/bannieres/Conanthebarbarianheader1.png" alt="Conan le Barbare" style="width: 100%; height: auto;"> -->
        </header>
        <nav class="navbar">
            <ul>
                <li><a href="#home">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#services">Services</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
        <div class="main-aside">
            <main class="main"> <!-- ICI S'AFFICHENT LES ARTICLES -->
                <?php foreach ($articles as $article): ?>
                    <article class="post">

                        <h2><?= htmlspecialchars($article['title']) ?></h2>
                        <p class="post-date"><?= date('d/m/Y', strtotime($article['created_at'])) ?></p>


                        <?php if (!empty($article['image'])): ?>
                            <img src="<?= htmlspecialchars($article['image']) ?>" alt="Image de l'article">
                        <?php endif; ?>

                        <p><?= nl2br(htmlspecialchars(substr($article['content'], 0, 200))) ?>...</p>
                        <a href="article.php?id=<?= $article['id'] ?>" class="read-more">Lire la suite</a>
                    </article>
                <?php endforeach; ?>
            </main>
            <aside class="aside">
                <h3>À propos</h3>
                <p>Un blog pour honorer l’univers de Conan, sa force, ses combats et sa légende.</p>
                <h3>Derniers articles</h3>
                <ul>
                    <li><a href="#">La Tour de l’Éléphant</a></li>
                    <li><a href="#">Les Chroniques de l’Hyboria</a></li>
                </ul>
                <h3>Catégories</h3>

                <!-- Inclure le nuage de catégories -->
                <?php include 'category-cloud.php'; ?>
                <!-- <a href="test.html">test</a>-->
                <h3>archives</h3>
                <?php include 'archives.php'; ?>
            </aside>
        </div>
        <footer class="footer">Footer

        </footer>
    </div>


    <!-- <header  id="header">-->
    <!--<img src="/images/article1.png" alt="Image de l'article">-->

    <script src="assets/changetheme.js?v=2"></script>


    <!-- verifie si changement-->
    <script>
        let lastVersion = null;

        async function checkVersion() {
            try {
                const response = await fetch('/version.txt', {
                    cache: 'no-store'
                });
                if (!response.ok) throw new Error('Version check failed');
                const text = await response.text();

                if (lastVersion === null) {
                    lastVersion = text;
                } else if (text !== lastVersion) {
                    console.log('🔁 Nouvelle version détectée. Rechargement...');
                    location.reload();
                }
            } catch (error) {
                console.error('Erreur de vérification de version.txt:', error);
            }
        }

        // Vérifie toutes les 3 secondes
        setInterval(checkVersion, 3000);
    </script>


</body>

</html>