<?php
// Activer l'affichage des erreurs pour le débogage
global $db;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config_bd.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérifier si l'ID de la tâche et le nouveau statut sont passés via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_tache']) && isset($_POST['status'])) {
    $id_tache = $_POST['id_tache'];
    $status = $_POST['status'];

    // Mise à jour du statut de la tâche dans la base de données
    $stmt = $db->prepare("UPDATE taches SET status = :status WHERE id_tache = :id_tache AND user_id = :user_id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id_tache', $id_tache, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirection après la mise à jour
    header('Location: listtasks.php');
    exit();
} else {
    echo "Requête invalide.";
    exit();
}
?>
