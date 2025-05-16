<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

//$req = $pdo->query("SELECT * FROM articles ORDER BY created_at DESC");

//$articles = $req->fetchAll();
?>












<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Conan the Barbarian Blog categorie cloud</title>
    <!--<link rel="stylesheet" href="assets/style-category-cloud.css?v=2">-->
    <link rel="stylesheet" href="assets/style-category-cloud.css">


   <!-- <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&display=swap" rel="stylesheet">-->

</head>
<body>
<?php
// Connexion à la base de données
try {
   // $pdo = new PDO('mysql:host=localhost;dbname=nom_base_de_donnees', 'nom_utilisateur', 'mot_de_passe');
    //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour récupérer les catégories
    $req = $pdo->query("SELECT category, COUNT(*) AS popularite FROM articles GROUP BY category ORDER BY popularite DESC");
    $result = $req->fetchAll();

    // Génération du nuage de catégories
    if (count($result) > 0) {
        echo '<div class="category-cloud">';
        foreach ($result as $row) {
            $size = $row["popularite"] > 2 ? "large" : ($row["popularite"] > 5 ? "medium" : "small");
            echo '<a href="lien_' . htmlspecialchars($row["category"] ?? 'inconnue') . '.html" class="' . $size . '">' . htmlspecialchars($row["category"] ?? 'Inconnue') . '</a>';
        }
        echo '</div>';
    } else {
        echo '<p>Aucune catégorie trouvée.</p>';
    }
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!--<div  class="category-cloud">
    <a href="#" class="large">Cuisine</a>
    <a href="#" class="medium">Voyage</a>
    <a href="#" class="small">Technologie</a>
</div>-->


</body>
</html>


