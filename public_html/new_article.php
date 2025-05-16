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

    <form action="save_article.php" method="post">
        <div class="form-group">
            <label for="title">Titre :</label><br>
            <input type="text" name="title" id="title" required style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group">
            <label for="editor">Contenu :</label>
            <!-- <div id="editor"></div> -->

            <!-- Create the editor container -->
            <div id="toolbar-container">
                <span class="ql-formats">
                    <select class="ql-font"></select>
                    <select class="ql-size"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>
                    <button class="ql-strike"></button>
                </span>
                <span class="ql-formats">
                    <select class="ql-color"></select>
                    <select class="ql-background"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-script" value="sub"></button>
                    <button class="ql-script" value="super"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-header" value="1"></button>
                    <button class="ql-header" value="2"></button>
                    <button class="ql-blockquote"></button>
                    <button class="ql-code-block"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-list" value="ordered"></button>
                    <button class="ql-list" value="bullet"></button>
                    <button class="ql-indent" value="-1"></button>
                    <button class="ql-indent" value="+1"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-direction" value="rtl"></button>
                    <select class="ql-align"></select>
                </span>
                <span class="ql-formats">
                    <button class="ql-link"></button>
                    <button class="ql-image"></button>
                    <button class="ql-video"></button>
                    <button class="ql-formula"></button>
                </span>
                <span class="ql-formats">
                    <button class="ql-clean"></button>
                </span>
            </div>
            <!-- Sélecteur de catégorie en dehors de la barre d’outils -->
            <div class="form-group">
                <label for="category">Catégorie :</label><br>
                <select name="category" id="category">
                    <option value="illustration">illustration</option>
                    <option value="scénario">scénario</option>
                    <option value="horreur">horreur</option>
                </select>
            </div>
            <div id="editor">
            </div>


            <input type="hidden" name="content" id="hiddenContent">
        </div>

        <button type="submit">Publier</button>
    </form>



</body>


<script>
    // Initialisation de Quill 
    var quill = new Quill('#editor', {
        placeholder: 'Écris ton article ici...',
        theme: 'snow',
        modules: {
            syntax: true,
            toolbar: {
                container: '#toolbar-container',
                handlers: {
                    image: imageHandler
                }
            }
        }
    });

    function imageHandler() {
        // alert("imageHandler");
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('image', file);

                fetch('upload_image.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const range = quill.getSelection();
                            quill.insertEmbed(range.index, 'image', data.url);
                        } else {
                            alert('Erreur : ' + data.error);
                        }
                    })
                    .catch(err => {
                        alert('Téléversement échoué.');
                        console.error(err);
                    });
            }
        };
    }

    // Envoie du contenu dans le champ caché lors de la soumission
    document.querySelector('form').addEventListener('submit', function() {
        document.querySelector('#hiddenContent').value = quill.root.innerHTML;
    });
</script>

</html>