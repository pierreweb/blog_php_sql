<?php
$confirmation = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if ($nom && $email && $message) {
        $to = 'tonemail@example.com'; // ← Remplace par ton adresse
        $subject = "Message du blog Conan de $nom";
        $body = "Nom: $nom\nEmail: $email\n\nMessage:\n$message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            $confirmation = "Merci, $nom. Ton message a bien été envoyé !";
        } else {
            $confirmation = "Erreur lors de l'envoi du message. Essaie plus tard.";
        }
    } else {
        $confirmation = "Veuillez remplir tous les champs correctement.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Contact - Conan Blog</title>
    <link rel="stylesheet" href="assets/style.css?v=<?= time(); ?>">
    <!--     <style>
        .contact-form {
            max-width: 600px;
            margin: auto;
            padding: 2em;
            background: #f4f4f4;
            border-radius: 8px;
        }

        .contact-form h2 {
            font-family: 'Cinzel', serif;
            text-align: center;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 0.8em;
            margin-bottom: 1em;
            border: 1px solid #999;
            border-radius: 5px;
        }

        .contact-form button {
            background: darkred;
            color: white;
            padding: 0.8em 2em;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .confirmation {
            text-align: center;
            color: green;
            font-weight: bold;
        }
    </style> -->
</head>

<body>
    <div class="contact-form">
        <h2>Contacte Conan</h2>
        <?php if ($confirmation): ?>
            <p class="confirmation"><?= $confirmation ?></p>
        <?php endif; ?>
        <form method="POST" action="contact.php">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required>

            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>

            <label for="message">Message :</label>
            <textarea name="message" id="message" rows="6" required></textarea>

            <button type="submit">Envoyer 🗡️</button>
        </form>
        <p><a href="index.php">← Retour au blog</a></p>
    </div>

</body>

</html>