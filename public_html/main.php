<?php
require 'config.php'; // 🔥 Connexion à la base

$category = $_GET['category'] ?? null; // 🔄 Récupérer la catégorie
$tag = $_GET['tag'] ?? null; // 🔄 Récupérer le tag

// Pagination
$articlesParPage = 4;
$pageCourante = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($pageCourante - 1) * $articlesParPage;

// 🔥 Construction de la requête SQL
$query = "SELECT DISTINCT a.* FROM articles a";
$params = [];

if ($tag) {
  $query = "SELECT DISTINCT a.* FROM articles a
          JOIN article_tags at ON a.id = at.article_id
          JOIN tags t ON at.tag_id = t.id
          WHERE t.name = ?";

  $params = [$tag];
} elseif ($category) {
  $query .= " WHERE a.category = ?";
  $params[] = $category;
}

// 🔄 Ajout de la condition pour les articles publiés

$query .= " ORDER BY a.created_at DESC LIMIT " . intval($articlesParPage) . " OFFSET " . intval($offset);

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$articles = $stmt->fetchAll();


// 🔄 Affichage des articles
foreach ($articles as $article) {
  $date = (new DateTime($article['created_at']))->format('d/m/Y');
  echo "<article class='article'>";
  echo "<h2>{$article['title']}</h2>";
  echo "<p class='date'>Publié le $date</p>";
  if ($article['imageUrl']) {
    echo "<img src='{$article['imageUrl']}' alt='Image article' class='postimg'>";
  }
  echo "<p>" . substr(strip_tags($article['content']), 0, 150) . "...</p>";
  echo "<a href='article.php?id={$article['id']}'>Lire la suite</a>";
  echo "</article>";
}
if ($tag) { // 🔥 Afficher uniquement si un tag est sélectionné
  echo '<div class="navigation-links">';
  echo '<p><a href="index.php">🏠 Retour à l\'accueil</a></p>';
  echo '<p><a href="page_tags.php">🏷️ Retour à la liste des tags</a></p>';
  echo '</div>';
}




// 🔄 Comptage des articles pour la pagination
$queryCount = "SELECT COUNT(*) FROM articles a";

if ($tag) {
  // 🔥 Requête pour compter les articles associés à un tag spécifique
  $queryCount = "SELECT DISTINCT a.* FROM articles a
          JOIN article_tags at ON a.id = at.article_id
          JOIN tags t ON at.tag_id = t.id
          WHERE t.name = ?";
  $paramsCount = [$tag];
} elseif ($category) {
  $queryCount .= " WHERE a.category = ?";
  $paramsCount = [$category];
} else {
  $paramsCount = [];
}

$stmt = $pdo->prepare($queryCount);
$stmt->execute($paramsCount);
$count = $stmt->fetchColumn();
$totalPages = ceil($count / $articlesParPage);

// 🔥 Gestion de la pagination
if ($totalPages > 1) {
  echo "<div class='pagination'>";

  if ($pageCourante > 1) {
    echo "<a href='?page=1" . ($category ? "&category=$category" : "") . "'>&laquo;</a> ";
    echo "<a href='?page=" . ($pageCourante - 1) . ($category ? "&category=$category" : "") . "'>&lt;</a> ";
  }

  $range = 1; // Nombre de pages autour de la page courante à afficher

  if ($pageCourante > $range + 2) {
    echo "<a href='?page=1" . ($category ? "&category=$category" : "") . "'>1</a> ";
    echo "<span class='dots'>...</span> ";
  }

  for ($i = max(1, $pageCourante - $range); $i <= min($totalPages, $pageCourante + $range); $i++) {
    $active = $i === $pageCourante ? 'class=\"active\"' : '';
    echo "<a href='?page=$i" . ($category ? "&category=$category" : "") . "' $active>$i</a> ";
  }

  if ($pageCourante < $totalPages - $range - 1) {
    echo "<span class='dots'>...</span> ";
    echo "<a href='?page=$totalPages" . ($category ? "&category=$category" : "") . "'>$totalPages</a> ";
  }

  if ($pageCourante < $totalPages) {
    echo "<a href='?page=" . ($pageCourante + 1) . ($category ? "&category=$category" : "") . "'>&gt;</a> ";
    echo "<a href='?page=$totalPages" . ($category ? "&category=$category" : "") . "'>&raquo;</a>";
  }

  echo "</div>";
}
echo '<div class="scroll-top">';
echo '<p></p>';
echo '<p><a href="#top">🔝 Retour en haut</a></p>';
echo '</div>';
