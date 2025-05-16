<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<?php if (isset($_SESSION['admin'])): ?>
    Connecté en tant que <strong>admin</strong>
    | <a href="logout.php">Se déconnecter</a>
<?php else: ?>
    <a href="login.php">Se connecter</a>
<?php endif; ?>