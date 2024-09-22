<?php
global $db;
session_start();
include 'config_bd.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier si l'ID de la tâche est passé en paramètre
if (isset($_GET['id_tache'])) {
    $id_tache = $_GET['id_tache'];

    // Préparer et exécuter la requête de suppression
    $stmt = $db->prepare("DELETE FROM taches WHERE id_tache = :id_tache");
    $stmt->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header('Location: listtasks.php'); // Rediriger vers la liste des tâches après suppression
        exit;
    } else {
        echo "Erreur lors de la suppression de la tâche.";
    }
} else {
    echo "ID de la tâche non spécifié.";
}
?>
