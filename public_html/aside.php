<h3>À propos</h3>
<p>Un blog pour honorer l’univers de Conan, sa force, ses combats et sa légende.</p>
<?php
$visites = file_exists('compteur.txt') ? file_get_contents('compteur.txt') : 0;
?>

<div class="compteur-container">
   <!-- <span class="compteur-icon">👁️</span>
   <span class="compteur-icon">⚔️</span> 
<span class="compteur-icon">🛡️</span> 
<span class="compteur-icon">🪓</span> 
<span class="compteur-icon">🏹</span> 
<span class="compteur-icon">🔥</span> 
<span class="compteur-icon">🦴</span> 
<span class="compteur-icon">🗡️</span> 
<span class="compteur-icon">🧝‍♂️</span> 
<span class="compteur-icon">🧙‍♂️</span> 
<span class="compteur-icon">🦁</span> 
<span class="compteur-icon">🐉</span> 
<span class="compteur-icon">🏔️</span> 
<span class="compteur-icon">🏕️</span> 
<span class="compteur-icon">🛶</span> 
<span class="compteur-icon">🪵</span> 
<span class="compteur-icon">💀</span> 
<span class="compteur-icon">⛓️</span> 
<span class="compteur-icon">🧟‍♂️</span> 
<span class="compteur-icon">🧛‍♂️</span> 
<span class="compteur-icon">👣</span> -->
   <span class="compteur-icon">👁️</span>

   <span class="compteur-texte">Visiteurs depuis la création: <strong><?= $visites ?></strong></span>
</div>
<h3>contactez l'auteur</h3>
<a href='contact.php'>formulaire contact</a>

<h3>Derniers articles</h3>
<ul>
   <li><a href="#">La Tour de l’Éléphant</a></li>
   <li><a href="#">Les Chroniques de l’Hyboria</a></li>
</ul>
<h3>Catégories</h3>

<!-- Inclure le nuage de catégories -->
<?php include 'category-cloud.php'; ?>
<!-- <a href="test.html">test</a>-->
<h3>tags</h3>
<!-- <?php /* include 'page_tags.php';  */ ?> -->
<a href="page_tags.php">liste tags</a>
<h3>galerie</h3>
<a href='gallery.php'>galerie</a>
<h3>archives</h3>
<?php include 'archives.php'; ?>