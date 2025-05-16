<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$erreur = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    // Mot de passe en clair (tu peux le hasher si tu veux)
    $motDePasseCorrect = 'conan1'; // à modifier !

    if ($password === $motDePasseCorrect) {
        $_SESSION['admin'] = true;
        header('Location: index.php');
        exit;
    } else {
        $erreur = 'Mot de passe incorrect.';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion admin</title>
    <link rel="stylesheet" href="assets/style4.css">
    <style>
        body {
            text-align: center;
            margin-top: 100px;
        }

        form {
            display: inline-block;
            background: rgba(0, 0, 0, 0.3);
            padding: 30px;
            border-radius: 8px;
        }

        input[type="password"] {
            padding: 10px;
            font-size: 1rem;
            width: 200px;
        }

        input[type="submit"] {
            margin-top: 10px;
            padding: 10px 20px;
        }

        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Connexion administrateur</h1>
    <form method="post">
        <input type="password" name="password" placeholder="Mot de passe" required>
        <br>
        <input type="submit" value="Se connecter">
        <?php if ($erreur): ?>
            <p class="error"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>
    </form>
</body>

</html>