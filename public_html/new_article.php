<?php
/* session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
} */
require 'config.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouvel article</title>
    <!-- <link href="assets/styles.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" />
</head>

<body>

    <h1>Créer un nouvel article</h1>

    <!--     <form action="save_article.php" method="post">
        <div class="form-group">
            <label for="title">Titre :</label><br>
            <input type="text" name="title" id="title" required style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group">
            <label for="editor">Contenu :</label>
 
            <div id="toolbar-container">



            <input type="hidden" name="content" id="hiddenContent">


        </div>

        <button type="submit">Publier</button>
    </form> -->


    <div class="container">
        <form action="save_article.php" method="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input id="title" name="title" type="text">
            </div>
            <div class="form-group">
                <label for="category">catégories</label>
                <input id="category" name="category" type="text">
            </div>
            <div class="form-group">
                <label>Article</label>
                <div id="editor"></div>
            </div>
            <button type="submit">Submit Form</button>
            <input type="hidden" name="content" id="hiddenContent">

        </form>
    </div>






</body>

<script>
    document.querySelector('form').addEventListener('submit', async function(e) {
        e.preventDefault(); // on empêche l’envoi automatique

        const delta = quill.getContents();
        const html = quill.root.innerHTML;

        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const images = doc.querySelectorAll('img');

        for (const img of images) {
            const src = img.getAttribute('src');
            if (src.startsWith('data:image')) {
                const blob = await (await fetch(src)).blob();
                const formData = new FormData();
                formData.append('image', blob, 'image.png');

                const response = await fetch('upload_image.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    img.setAttribute('src', result.url);
                } else {
                    alert("Erreur d'upload : " + result.error);
                    return; // stop tout si erreur
                }
            }
        }

        // Remplacer le contenu dans le champ caché
        document.querySelector('#hiddenContent').value = doc.body.innerHTML;

        // Puis soumettre le formulaire
        e.target.submit();
    });
</script>

<script>
    // Initialisation de Quill
    const quill = new Quill('#editor', {
        modules: {
            toolbar: [
                [{
                    header: [1, 2, false]
                }],
                ['bold', 'italic', 'underline'],
                ['image', 'video', 'code-block'],
            ],
        },
        placeholder: 'Compose an epic...',
        theme: 'snow', // or 'bubble'
    });

    // Écouteur d'événement pour le formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        // Récupérer le contenu de l'éditeur et le stocker dans un champ caché
        var content = quill.root.innerHTML;
        document.querySelector('#hiddenContent').value = content;
    });
</script>

</html>