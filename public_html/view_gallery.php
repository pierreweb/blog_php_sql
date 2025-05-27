<?php
if (!isset($_GET['id'])) {
    echo "Galerie introuvable.";
    exit;
}
$id = htmlspecialchars($_GET['id']);
// $url = "https://galerie.archive-host.com/n/index.php?id=$id";
$url = "http://diaporama.archive-host.com/g/fullscreen.php?id=$id";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Galerie</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">

</head>

<body>
    <div class="container">
        <div class="main-view-gallery">
            <div class="iframe-container">
                <iframe src="<?= $url ?>" allowfullscreen></iframe>
            </div>

            <a class="back-gallery" href="gallery.php">← Retour à la galerie</a>
        </div>
    </div>
</body>

</html>