<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['visite_comptabilisee'])) {
    $compteurFichier = 'compteur.txt';

    if (file_exists($compteurFichier)) {
        $visites = (int)file_get_contents($compteurFichier);
    } else {
        $visites = 0;
    }

    $visites++;
    file_put_contents($compteurFichier, $visites);

    $_SESSION['visite_comptabilisee'] = true;
}
?>

<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conan the Barbarian Blog</title>
    <link rel="icon" href="images/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">

    <!-- Polices -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">
</head>

<body>
    <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px;">
        <div>
            <?php include 'connexion_admin.php'; ?>

        </div>

        <button id="change-theme-btn">Changer le style Conan</button>
    </div>




    <div class="container">
        <header class="header">
            <?php include 'header.php'; ?>
        </header>

        <nav class="navbar">
            <?php include 'navbar.php'; ?>
        </nav>

        <div class="main-aside">
            <main class="main">
                <?php include 'main.php'; ?>
            </main>
            <aside class="aside">
                <?php include 'aside.php'; ?>
            </aside>
        </div>

        <footer class="footer">
            <?php include 'footer.php'; ?>
        </footer>
    </div>

    <script src="/assets/changetheme.js"></script>
</body>

</html>