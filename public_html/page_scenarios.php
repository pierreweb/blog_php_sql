<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>
        <>Liens Conan le barbare blog
    </title>

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
        <h1>liens sur Conan le Barbare</h1>


        <p><a href="index.php">← Retour au blog</a></p>
    </div>
</body>

</html>