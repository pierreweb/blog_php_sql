<?php
/* if (!isset($_GET['id'])) {
    echo "Galerie introuvable.";
    exit;
} */
/* $id = htmlspecialchars($_GET['id']);
// $url = "https://galerie.archive-host.com/n/index.php?id=$id";
$url = "http://diaporama.archive-host.com/g/fullscreen.php?id=$id"; */
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>conan the barbarian and the black pyramid 3D</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">

</head>

<body>
    <div class="container">
        <div class="main-3d">
            <div class="iframe-container">
                <!-- <iframe src="<= $url ?>" allowfullscreen></iframe> -->
                <!-- <iframe src="https://ahp.li/3f0b81b3beebfdb0ff9b.html" allowfullscreen></iframe> -->
                <iframe src="https://ahp.li/3f0b81b3beebfdb0ff9b.html" allowfullscreen style="background-color: var(--colorbackgroundcontainer)"></iframe>
            </div>

            <a class="back-gallery" href="index.php">← Retour à home</a>
        </div>
    </div>
</body>

</html>