<?php
session_start(); // ✅ Activation correcte de la session
require 'config.php';

// Récupération des catégories et tags depuis JSON
$data = json_decode(file_get_contents('./datas/categoryandtags.json'), true);
$categories = $data['categories'] ?? [];
$tags = $data['tags'] ?? [];

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouvel article</title>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>

    <link rel="stylesheet" href="./assets/quill.css">
</head>

<body>

    <h1>Créer un nouvel article</h1>

    <div class="container">
        <form action="save_article.php" method="post">
            <div class="form-group">
                <label for="title">Titre :</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="category">Catégories :</label>
                <input name="category" id="category-input" placeholder="Choisir ou ajouter une catégorie...">
            </div>

            <div class="form-group">
                <label for="tags">tags :</label>
                <!-- <input name="tags[]" id="tags-input" placeholder="Ajoute des tags..."> -->
                <input name="tag" id="tags-input" placeholder="Ajoute des tags...">

            </div>

            <div class="form-group">
                <label>Article :</label>
                <div id="editor"></div>
                <input type="hidden" name="content" id="hiddenContent">
            </div>

            <button type="submit">Publier</button>
        </form>
    </div>

</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const existingCategories = <?php echo json_encode($categories); ?>;
        console.log("Catégories disponibles :", existingCategories);

        const categoryInput = document.querySelector("#category-input");

        if (categoryInput && existingCategories.length) {
            new Tagify(categoryInput, {
                enforceWhitelist: false, // Autorise l'ajout de nouvelles catégories
                whitelist: existingCategories,
                dropdown: {
                    enabled: 0, // 🔥 Affiche automatiquement les suggestions
                    fuzzySearch: true,
                    position: "text",
                }
            });
        }

        const existingTags = <?php echo json_encode($tags); ?>;
        console.log("Tags disponibles :", existingTags);

        const tagsInput = document.querySelector("#tags-input");

        if (tagsInput) {
            new Tagify(tagsInput, {
                whitelist: existingTags, // 🔥 Autocomplétion activée !
                dropdown: {
                    enabled: 0, // Affiche automatiquement les suggestions
                    fuzzySearch: true,
                    position: "text",
                }
            });
        }

    });

    // Initialisation de Quill
    const quill = new Quill('#editor', {
        theme: 'snow',
        placeholder: 'Écris ton article ici...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                ['image', 'video', 'code-block']
            ]
        }
    });

    document.querySelector('form').addEventListener('submit', async function(e) {
        e.preventDefault(); // Empêcher la soumission immédiate

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
                    return;
                }
            }
        }

        document.querySelector('#hiddenContent').value = doc.body.innerHTML;
        e.target.submit();
    });
</script>

</html>