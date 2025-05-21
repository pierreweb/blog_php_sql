<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

// Récupération des catégories et tags depuis JSON
$data = json_decode(file_get_contents('./datas/categoryandtags.json'), true);
$categories = $data['categories'];
$tags = $data['tags'];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Nouvel article</title>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.min.js"></script>

</head>

<body>

    <h1>Créer un nouvel article</h1>

    <form action="save_article.php" method="post">
        <div class="form-group">
            <label for="title">Titre :</label><br>
            <input type="text" name="title" id="title" required style="width: 100%; padding: 8px;">
        </div>

        <div class="form-group">
            <label for="category">Catégorie :</label><br>
            <select name="category" id="category">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tags-input">Tags :</label><br>
            <input name="tags[]" id="tags-input" placeholder="Ajoute des tags..." />
        </div>

        <div class="form-group">
            <label for="editor">Contenu :</label>
            <div id="editor"></div>
            <input type="hidden" name="content" id="hiddenContent">
            <input type="hidden" name="image_url" id="image_url">

        </div>

        <button type="submit">Publier</button>
    </form>

</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const existingTags = <?php echo json_encode($tags); ?>;
        const input = document.querySelector("#tags-input");

        if (input) {
            new Tagify(input, {
                whitelist: existingTags,
                dropdown: {
                    enabled: 1,
                    fuzzySearch: true,
                    position: "all",
                }
            });
        }
    });
</script>

<script>
    // Fonction pour gérer l'upload d'image
    // Handler image modifié
    function imageHandler() {
        print_r('imageHandler called');
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                imageUploading = true;
                submitButton.disabled = true;

                const formData = new FormData();
                formData.append('image', file);
                formData.append('category', document.querySelector('#category').value);

                fetch('upload_image.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json()) // directement ici
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
    // Initialisation de Quill
    console.log(Quill); // Devrait afficher l'objet Quill dans la console


    const toolbarOptions = [
        ['bold', 'italic'],
        ['blockquote', 'code-block'],
        ['link', 'image', 'video']
    ];
    const quill = new Quill('#editor', {
        modules: {
            toolbar: toolbarOptions,
            handlers: {
                image: imageHandler
            }
        },
        theme: 'snow'
    });


    let imageUploading = false;
    const submitButton = document.querySelector('button[type="submit"]');



    // Lors de la soumission du formulaire
    document.querySelector('form').addEventListener('submit', function(e) {
        // Nettoyer les images base64 avant d’envoyer le contenu
        const imgs = quill.root.querySelectorAll('img');
        imgs.forEach(img => {
            if (img.src.startsWith('data:')) {
                img.remove(); // supprime les images inline non uploadées
            }
        });

        // Transférer le contenu Quill nettoyé
        document.querySelector('#hiddenContent').value = quill.root.innerHTML;
    });






    /*     document.querySelector("#tags-input").addEventListener("change", function() {
            document.querySelector("#image_url").value = this.value;
        }); */
</script>

</html>