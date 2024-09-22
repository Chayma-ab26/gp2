<?php
// Définir les paramètres de connexion à la base de données
$host = 'localhost'; // Ajoutez des guillemets autour de localhost
$dbname = 'gestionprojet'; // Remplacez par le nom de votre base de données
$username = 'root'; // Remplacez par votre nom d'utilisateur de la base de données
$password = ''; // Remplacez par votre mot de passe de la base de données

try {
    // Créer une nouvelle connexion PDO
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Définir le mode d'erreur de PDO sur Exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Afficher l'erreur en cas de problème de connexion
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>
