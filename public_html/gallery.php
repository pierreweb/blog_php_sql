<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Galerie - Conan le Barbare</title>
    <!-- <link rel="stylesheet" href="assets/style4.css?v=<?= time(); ?>"> -->
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">

</head>

<body>
    <div class="gallery-container">
        <?php
        $galeries = [
            ['id' => 'xgYJ099wlYep4a8uujuY', 'title' => 'Aventures 1', 'thumb' => 'https://archive-host.com/thumb1.jpg'],
            ['id' => 'fDnMdkE1ozCrdJh34rdE', 'title' => 'King Kong', 'thumb' => 'https://ahp.li/411d0217e731cc1bf234.jpg'],
            ['id' => '5cwTosprArxqjrpfvb2Z', 'title' => 'Personnages masculins', 'thumb' => 'https://ahp.li/4f7151c0027ea7368881.png'],
            ['id' => 'ghi789', 'title' => 'Combat épique', 'thumb' => 'https://archive-host.com/thumb4.jpg'],
            ['id' => 'jkl999', 'title' => 'Paysages Hyboriens', 'thumb' => 'https://archive-host.com/thumb5.jpg'],
            ['id' => 'mno777', 'title' => 'Bestiaire barbare', 'thumb' => 'https://archive-host.com/thumb6.jpg'],
        ];

        foreach ($galeries as $g) {
            echo "<a class='gallery-item' href='view_gallery.php?id={$g['id']}'>";
            echo "<div class='gallery-item-title'><h4>{$g['title']}</h4></div>";
            echo "<img src='{$g['thumb']}' alt='Aperçu'>";

            echo "</a>";
        }
        ?>
    </div>


    <a href="index.php" class="back-home">← Retour au blog</a>
</body>

</html>