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
    const quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: {
                container: [
                    [{
                        'header': [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['image', 'code-block', 'video'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    ['clean']
                ],
                handlers: {
                    image: imageHandler
                }
            }
        }
    });

    // 1️⃣ Quand on clique sur le bouton image de Quill
    function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                uploadImage(file)
                    .then(url => {
                        const range = quill.getSelection();
                        quill.insertEmbed(range.index, 'image', url);
                    })
                    .catch(err => {
                        alert('Erreur : ' + err.message);
                        console.error(err);
                    });
            }
        };
    }

    // 2️⃣ Pour coller ou glisser-déposer une image en base64
    function handleBase64Images(e) {
        setTimeout(() => {
            const imgs = quill.root.querySelectorAll('img');
            imgs.forEach(img => {
                if (img.src.startsWith('data:image/')) {
                    fetch(img.src)
                        .then(res => res.blob())
                        .then(blob => {
                            const file = new File([blob], "image.png", {
                                type: blob.type
                            });
                            return uploadImage(file);
                        })
                        .then(url => {
                            img.setAttribute('src', url); // remplace le base64 par l’URL
                        })
                        .catch(err => {
                            console.error("Échec de l'upload :", err);
                        });
                }
            });
        }, 100); // petit délai pour laisser Quill insérer l’image collée
    }

    // 3️⃣ Envoie l’image au serveur via upload_image.php
    async function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);

        const res = await fetch('upload_image.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();
        if (!data.success) throw new Error(data.error || "Upload failed");
        return data.url;
    }

    // 4️⃣ Nettoyage avant envoi
    document.querySelector('form').addEventListener('submit', function() {
        document.querySelector('#hiddenContent').value = quill.root.innerHTML;
    });

    // 5️⃣ Attache paste & drop
    quill.root.addEventListener('paste', handleBase64Images);
    quill.root.addEventListener('drop', handleBase64Images);
</script>


</html>