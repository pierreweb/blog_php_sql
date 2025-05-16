<?php
$host = 'db';
$dbname = 'blog_Conan';
$user = 'conan';
$pass = 'barbare';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->exec("SET NAMES utf8mb4");
    $pdo->exec("SET CHARACTER SET utf8mb4");
} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}
