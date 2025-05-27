<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Galerie - Conan le Barbare</title>
    <!-- <link rel="stylesheet" href="assets/style4.css?v=<?= time(); ?>"> -->
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">

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
    <div class="container">
        <div class="main-aside">
            <div class="gallery-container">
                <?php
                $galeries = [
                    ['id' => 'w4CkH2aFk0lfjVqtQ3p0', 'title' => 'Dracula', 'thumb' => 'https://ahp.li/ff77afef2fa967b627ef.jpg'],
                    ['id' => 'fDnMdkE1ozCrdJh34rdE', 'title' => 'King Kong', 'thumb' => 'https://ahp.li/411d0217e731cc1bf234.jpg'],
                    ['id' => '5cwTosprArxqjrpfvb2Z', 'title' => 'Personnages masculins', 'thumb' => 'https://ahp.li/4f7151c0027ea7368881.png'],
                    ['id' => 'Eww5uJxhglhhne1j00uk', 'title' => 'Frazetta Art', 'thumb' => 'https://ahp.li/m/2/25b399f19d5a802f88f6.jpg'],
                    ['id' => 'bhmrb68ipPj9iIE6xx8p', 'title' => 'Horreur', 'thumb' => 'https://ahp.li/ae2232bd51b8d00dd1da.jpg'],
                    ['id' => 'Qy4ziFunjwvmZp1phc0V', 'title' => 'Frankenstein', 'thumb' => 'https://ahp.li/a2d72df419c6381d83b5.jpg'],
                    ['id' => 'wyh41dP9ayb1mzddBlZ8', 'title' => 'jules Verne Art', 'thumb' => 'https://ahp.li/d3e7bb32068420654584.jpg'],
                    ['id' => 'nBcrek9YTi1Kvn4kt48g', 'title' => 'brocal Remohi Art', 'thumb' => 'https://ahp.li/ead85214977edd987de6.jpg'],
                    ['id' => 'bhmrb68ipPj9iIE6xx8p', 'title' => 'Horreur', 'thumb' => 'https://ahp.li/ae2232bd51b8d00dd1da.jpg'],
                    ['id' => 'Qy4ziFunjwvmZp1phc0V', 'title' => 'Frankenstein', 'thumb' => 'https://ahp.li/a2d72df419c6381d83b5.jpg'],
                    ['id' => '0iqEx1h4893m3j1zra7y', 'title' => 'ken Kelly Art', 'thumb' => 'https://ahp.li/170115fff074c6914c7c.jpg'],
                    ['id' => 'X09H5jniuyEEnzp9wZxa', 'title' => 'boris Vallero Art', 'thumb' => 'https://ahp.li/77c13abc88a1cb887baf.jpg'],

                ];

                // Trier le tableau par titre
                usort($galeries, function ($a, $b) {
                    return strcmp($a['title'], $b['title']);
                });


                foreach ($galeries as $g) {
                    echo "<a class='gallery-item' href='view_gallery.php?id={$g['id']}'>";
                    echo "<div class='gallery-item-title'><h4>{$g['title']}</h4></div>";
                    echo "<img src='{$g['thumb']}' alt='Aperçu'>";

                    echo "</a>";
                }
                ?>
            </div>
        </div>


        <a href="index.php" class="back-home">← Retour au blog</a>
    </div>
</body>

</html>