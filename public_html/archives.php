<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config.php';

$req = $pdo->query("
    SELECT 
        YEAR(created_at) AS annee, 
        MONTH(created_at) AS mois, 
        COUNT(*) AS nb_articles
    FROM articles
    GROUP BY annee, mois
    ORDER BY annee DESC, mois DESC
");

$archives = [];
while ($row = $req->fetch()) {
    $archives[$row['annee']][] = [
        'mois' => $row['mois'],
        'nb' => $row['nb_articles']
    ];
}

// Création du formateur de date en français
$formatter = new \IntlDateFormatter(
    'fr_FR',
    \IntlDateFormatter::LONG,
    \IntlDateFormatter::NONE,
    null,
    null,
    'LLLL'
);

echo "<div class='archives'>";
foreach ($archives as $annee => $moisList) {
    echo "<details>";
    echo "<summary><p>$annee</p></summary>";
    echo "<ul>";
    foreach ($moisList as $m) {
        $date = new DateTime("{$annee}-{$m['mois']}-01");
        $moisNom = ucfirst($formatter->format($date)); // Ex : Mars
        echo "<li><a href='?annee=$annee&mois={$m['mois']}'>$moisNom ({$m['nb']})</a></li>";
    }
    echo "</ul></details>";
}
echo "</div>";
