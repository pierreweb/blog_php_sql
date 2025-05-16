<?php
// Pagination
$articlesParPage = 4;
$pageCourante = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($pageCourante - 1) * $articlesParPage;

// Requête paginée
$stmt = $pdo->prepare("SELECT * FROM articles ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $articlesParPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$articles = $stmt->fetchAll();

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



/* // Pagination en bas
$count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$totalPages = ceil($count / $articlesParPage);

if ($totalPages > 1) {
echo "<div class='pagination'>";

  // Liens vers les pages numérotées
  for ($i = 1; $i <= $totalPages; $i++) {
    $active=$i===$pageCourante ? 'class="active"' : '' ;
    echo "<a href='?page=$i' $active>$i</a> " ;
    }

    // Lien page suivante
    if ($pageCourante < $totalPages) {
    $nextPage=$pageCourante + 1;
    echo "<a href='?page=$nextPage'>&gt;</a> " ;
    echo "<a href='?page=$totalPages'>&gt;&gt;</a>" ;
    }

    echo "</div>" ;
    } */

/* // Pagination en bas
    $count=$pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    $totalPages = ceil($count / $articlesParPage);

    if ($totalPages > 1) {
    echo "<div class='pagination'>";

      // Lien vers la première page
      if ($pageCourante > 1) {
      echo "<a href='?page=1'>&laquo;</a> "; // <<
        echo "<a href='?page=" . ($pageCourante - 1) . "'>&lt;</a> " ; // <
        }

        // Liens vers les pages numérotées
        for ($i=1; $i <=$totalPages; $i++) {
        $active=$i===$pageCourante ? 'class="active"' : '' ;
        echo "<a href='?page=$i' $active>$i</a> " ;
        }

        // Lien vers la page suivante et dernière page
        if ($pageCourante < $totalPages) {
        echo "<a href='?page=" . ($pageCourante + 1) . "'>&gt;</a> " ; //>
        echo "<a href='?page=$totalPages'>&raquo;</a>"; // >>
        }

        echo "</div>";
    } */
/*
    $count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
    $totalPages = ceil($count / $articlesParPage);

    if ($totalPages > 1) {
    echo "<div class='pagination'>";

      // Liens << et <
        if ($pageCourante> 1) {
        echo "<a href='?page=1'>&laquo;</a> ";
        echo "<a href='?page=" . ($pageCourante - 1) . "'>&lt;</a> ";
        }

        $range = 1; // Nombre de pages autour de la page courante à afficher

        // Affiche toujours la première page
        if ($pageCourante > $range + 2) {
        echo "<a href='?page=1'>1</a> ";
        echo "<span class='dots'>...</span> ";
        }

        // Pages autour de la page courante
        for ($i = max(1, $pageCourante - $range); $i <= min($totalPages, $pageCourante + $range); $i++) {
          $active=$i===$pageCourante ? 'class="active"' : '' ;
          echo "<a href='?page=$i' $active>$i</a> " ;
          }

          // Affiche toujours la dernière page
          if ($pageCourante < $totalPages - $range - 1) {
          echo "<span class='dots'>...</span> " ;
          echo "<a href='?page=$totalPages'>$totalPages</a> " ;
          }

          // Liens> et >>
          if ($pageCourante < $totalPages) {
            echo "<a href='?page=" . ($pageCourante + 1) . "'>&gt;</a> " ;
            echo "<a href='?page=$totalPages'>&raquo;</a>" ;
            }

            echo "</div>" ;
            }
            */
$count = $pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn();
$totalPages = ceil($count / $articlesParPage);

if ($totalPages > 1) {
  echo "<div class='pagination'>";

  // Liens << et <
  if ($pageCourante > 1) {
    echo "<a href='?page=1'>&laquo;</a> ";
    echo "<a href='?page=" . ($pageCourante - 1) . "'>&lt;</a> ";
  }

  $range = 1; // Nombre de pages autour de la page courante à afficher

  // Affiche toujours la première page
  if ($pageCourante > $range + 2) {
    echo "<a href='?page=1'>1</a> ";
    echo "<span class='dots'>...</span> ";
  }

  // Pages autour de la page courante
  for ($i = max(1, $pageCourante - $range); $i <= min($totalPages, $pageCourante + $range); $i++) {
    $active = $i === $pageCourante ? 'class="active"' : '';
    echo "<a href='?page=$i' $active>$i</a> ";
  }

  // Affiche toujours la dernière page
  if ($pageCourante < $totalPages - $range - 1) {
    echo "<span class='dots'>...</span> ";
    echo "<a href='?page=$totalPages'>$totalPages</a> ";
  }

  // Liens> et >>
  if ($pageCourante < $totalPages) {
    echo "<a href='?page=" . ($pageCourante + 1) . "'>&gt;</a> ";
    echo "<a href='?page=$totalPages'>&raquo;</a>";
  }

  echo "</div>";
}
