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
            // Supprimer les tags associés à l'article
            $stmt = $pdo->prepare("DELETE FROM tags WHERE id NOT IN (SELECT tag_id FROM article_tags)");
            $stmt->execute();

            // 🔄 Mettre à jour le JSON en ne gardant que les tags existants
            // 🔄 Charger les données du fichier JSON
            $json = json_decode(file_get_contents('./datas/categoryandtags.json'), true) ?? [];
            // 🔥 Assurer que `tags` existe dans le JSON
            $existingTags = isset($json['tags']) ? $json['tags'] : [];
            // 🔄 Récupérer les tags encore utilisés dans la base SQL
            $stmt = $pdo->prepare("SELECT name FROM tags");
            $stmt->execute();
            $tagsInDb = $stmt->fetchAll(PDO::FETCH_COLUMN);
            // 🔥 Vérifier que `$tagsInDb` est bien défini avant `array_intersect()`
            if (!empty($existingTags) && !empty($tagsInDb)) {
                $json['tags'] = array_values(array_intersect($existingTags, $tagsInDb));
            } else {
                $json['tags'] = []; // 🔄 Si aucun tag valide, on vide la liste
            }
            // 🔄 Mettre à jour `categoryandtags.json`
            file_put_contents('./datas/categoryandtags.json', json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        header('Location: index.php'); // retour à la liste
        exit;
    } catch (Exception $e) {
        echo "Erreur lors de la suppression : " . htmlspecialchars($e->getMessage());
    }
} else {
    echo "ID d'article manquant.";
}
