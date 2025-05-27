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
    <title>3D</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">

</head>

<body>
    <div class="container">
        <div class="main-3d">
            <div class="iframe-container">
                <!-- <iframe src="<= $url ?>" allowfullscreen></iframe> -->
                <iframe src="https://ahp.li/659f1f64e60466142fe6.html" allowfullscreen></iframe>
                <!-- <iframe src="https://ahp.li/659f1f64e60466142fe6.html" allowfullscreen style="background-color:#ff0000"></iframe> -->
            </div>

            <a class="back-gallery" href="index.php">← Retour à home</a>
        </div>
    </div>
</body>

</html>