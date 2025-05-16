<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}


// require_once __DIR__ . '/config.php'; // Chemin correct vers config.php

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        // Connexion à la base
        // $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer le contenu de l'article
        $stmt = $pdo->prepare("SELECT content FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($article) {
            // Suppression des images insérées (src="/uploads/...")
            if (preg_match_all('/<img[^>]+src=["\'](?:[^"\']*\/)?uploads\/([^"\']+\.(?:jpg|jpeg|png|webp|gif))["\']/i', $article['content'], $matches))
            // preg_match_all('/<img[^>]+src=["\'](?:[^"\']*\/)?uploads\/([^"\']+\.(?:jpg|jpeg|png|webp|gif))["\']/i', $content, $matches);

            {
                foreach ($matches[1] as $imageUrl) {
                    $imageFile = basename($imageUrl); // sécurise le nom de fichier
                    // $imagePath = __DIR__ . '/public_html/uploads/' . $imageFile;


                    $imagePath = '/var/www/html/uploads/' . $imageFile;

                    //var_dump($imagePath); // debug

                    /*     $imagePath = __DIR__ . (str_starts_with(parse_url($imageUrl, PHP_URL_PATH), '/uploads/') 
    ? parse_url($imageUrl, PHP_URL_PATH) 
    : '/uploads/' . basename($imageUrl)); */

                    //$imagePath = __DIR__ . parse_url($imageUrl, PHP_URL_PATH);
                    //  print_r("0");
                    // print_r($imagePath);
                    if (file_exists($imagePath)) {
                        // print_r("1");
                        // print_r($imagePath);
                        unlink($imagePath); // suppression du fichier image
                        // print("2");
                    }
                }
            }

            // Supprimer l'article
            $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
            $stmt->execute([$id]);
        }

        header('Location: index.php'); // retour à la liste
        exit;
    } catch (Exception $e) {
        echo "Erreur lors de la suppression : " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "ID d'article manquant.";
}
