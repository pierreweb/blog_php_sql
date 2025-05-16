<!--     <style>
        .container {
            max-width: 800px;
            margin: 40px auto;
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
        }

        label {
            display: block;
            margin: 15px 0 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #999;
            background-color: #fdfdfd;
            color: #222;
        }

        input[type="submit"] {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #a52a2a;
            color: white;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        a.return-link {
            display: inline-block;
            margin-top: 20px;
            color: #ccc;
            text-decoration: underline;
        }
    </style>
</head>

<body class="article-page">

    <div class="container">
        <h1>Rédiger un nouvel article</h1>
        <form action="save_article.php" method="post" enctype="multipart/form-data">

            <label for="title">Titre :</label>
            <input type="text" id="title" name="title" required>

            <label for="image">Image :</label>
            <input type="file" id="image" name="image">

            <label for="content">Contenu :</label>
            <textarea id="content" name="content" rows="10" required></textarea>

            <input type="submit" value="Publier l'article">
        </form>

        <a class="return-link" href="index.php">← Retour au blog</a>
    </div>
 -->


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
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- Include stylesheet -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        #editor {
            height: 300px;
            background-color: rgba(202, 200, 175, 1);
        }

        .ql-toolbar.ql-snow {
            border-radius: 5px 5px 0 0;
            background-color: rgba(202, 200, 175, 1);
        }

        .ql-container.ql-snow {
            border-radius: 0 0 5px 5px;
        }
    </style>
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <link id="theme-style" rel="stylesheet" href="">
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
            <div id="editor">
                <p>Hello World!</p>
                <p>Some initial <strong>bold</strong> text</p>
                <p><br /></p>
            </div>


            <input type="hidden" name="content" id="hiddenContent">
        </div>

        <button type="submit">Publier</button>
    </form>


    <!-- Create the editor container -->
    <!--  <div id="editor">
        <p>Hello World!</p>
        <p>Some initial <strong>bold</strong> text</p>
        <p><br /></p>
    </div> -->

    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <!-- Initialize Quill editor -->
    <!-- <script>
        const quill = new Quill('#editor', {
            theme: 'snow'
        }); 
    </script> -->


    <!-- Quill JS -->
    <!-- <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        // Initialisation de Quill
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Écris ton article ici...',
            modules: {
                toolbar: [
                    [{
                        header: [1, 2, false]
                    }],
                    ['bold', 'italic', 'underline'],
                    ['link', 'image'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['clean']
                ]
            }
        });

        // Transfert du contenu HTML dans le champ caché lors de la soumission
        document.querySelector('form').addEventListener('submit', function(e) {
            document.querySelector('#hiddenContent').value = quill.root.innerHTML;
        });
    </script> -->

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
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();
            input.onchange = () => {
                const file = input.files[0];
                if (file) {
                    const formData = new FormData();
                    formData.append('image', file);
                    formData.append('category', document.querySelector('#category').value); // ← nouveau 
                    fetch('upload_image.php', {
                            method: 'POST',
                            body: formData
                        }).then(res => res.text()) // 🔥 Voir la réponse brute avant de la parser 
                        .then(text => {
                            console.log("Réponse brute du serveur :", text); // 🔥 Vérifie la console du navigateur 
                            return JSON.parse(text); // Ensuite, transformer en JSON 
                        })
                        .then(res => res.json()).then(data => {
                            if (data.success) {
                                const range = quill.getSelection();
                                quill.insertEmbed(range.index, 'image', data.url);
                            } else {
                                alert('Erreur : ' + data.error);
                            }
                        }).catch(err => {
                            alert('Téléversement échoué.');
                            console.error(err);
                        });
                }
            };
        }
        // Transfert du contenu HTML dans le champ caché lors de la soumission 
        document.querySelector('form').addEventListener('submit', function(e) {
            const imgs = quill.root.querySelectorAll('img');
            let hasBase64 = false;

            imgs.forEach(img => {
                if (img.src.startsWith('data:')) {
                    hasBase64 = true;
                }
            });

            if (hasBase64) {
                alert("Une image est encore en cours de traitement. Veuillez patienter quelques secondes.");
                e.preventDefault();
                return;
            }

            document.querySelector('#hiddenContent').value = quill.root.innerHTML;
        });
    </script>

</body>

</html>