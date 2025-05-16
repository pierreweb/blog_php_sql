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
    <link rel="stylesheet" href="assets/style4.css?v=<?= time(); ?>">
    <style>
        .iframe-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 10px;
            background-color: #111;
            border: 2px solid #a52a2a;
            border-radius: 10px;
        }

        iframe {
            width: 100%;
            height: 700px;
            border: none;
        }

        .back-gallery {
            display: block;
            text-align: center;
            margin: 30px;
            color: #ccc;
        }

        .back-gallery:hover {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="iframe-container">
        <iframe src="<?= $url ?>" allowfullscreen></iframe>
    </div>
    <a class="back-gallery" href="gallery.php">← Retour à la galerie</a>
</body>

</html>