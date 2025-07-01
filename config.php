<?php
session_start();

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'boostme');

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("SET NAMES utf8");
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonctions utilitaires
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Obtenir les informations de l'utilisateur connecté
function getCurrentUser($pdo) {
    if (!isLoggedIn()) return null;
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
?>