<?php
global $db;
session_start();
include 'config_bd.php';

// Vérifier si une ID est passée et est un entier valide
if (isset($_POST['id']) && filter_var($_POST['id'], FILTER_VALIDATE_INT)) {
    $client_id = $_POST['id'];

    try {
        // Préparer et exécuter la requête de suppression
        $stmt = $db->prepare("DELETE FROM clients WHERE id_clients = :id_clients");
        $stmt->bindParam(':id_clients', $client_id, PDO::PARAM_INT);
        $stmt->execute();

        // Renvoi d'une réponse pour indiquer le succès
        echo 'success';
    } catch (PDOException $e) {
        // Gestion des erreurs et retour d'un message d'erreur
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid ID';
}
?>
