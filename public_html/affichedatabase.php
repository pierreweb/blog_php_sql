<?php
// Connexion à la base de données
// $host = 'localhost'; // ou 'mysql' si tu es dans Docker
$db   = 'blog_Conan';
// $user = 'root';
// $pass = 'ton_mot_de_passe';
$charset = 'utf8mb4';

$host = 'db';
$dbname = 'blog_Conan';
$user = 'conan';
$pass = 'barbare';


$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Préparer et exécuter la requête
    $stmt = $pdo->query('SELECT * FROM articles');

    // Afficher les résultats
    echo "<h1>Contenu de la table articles</h1>";
    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr>";
    // En-têtes de colonnes
    $firstRow = $stmt->fetch();
    if ($firstRow) {
        foreach ($firstRow as $col => $val) {
            echo "<th>$col</th>";
        }
        echo "</tr><tr>";
        foreach ($firstRow as $val) {
            echo "<td>$val</td>";
        }
        echo "</tr>";

        // Lignes suivantes
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            foreach ($row as $val) {
                echo "<td>$val</td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='100%'>Aucune donnée</td></tr>";
    }

    echo "</table>";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
