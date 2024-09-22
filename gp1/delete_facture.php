<?php
// Inclure la connexion à la base de données
include 'config_bd.php';
global $db;

// Vérifier si un ID de facture a été spécifié dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_facture = intval($_GET['id']);

    try {
        // Préparer la requête de suppression
        $stmt = $db->prepare("DELETE FROM factures WHERE id_facture = :id_facture");
        $stmt->bindParam(':id_facture', $id_facture, PDO::PARAM_INT);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Si la suppression est réussie, rediriger vers la liste des factures avec un message de succès
            header("Location: listfactures.php?message=success");
            exit();
        } else {
            // Si la suppression échoue, rediriger vers la liste des factures avec un message d'erreur
            header("Location: listfactures.php?message=error");
            exit();
        }
    } catch (PDOException $e) {
        // En cas d'erreur, rediriger vers la liste des factures avec un message d'erreur
        header("Location: listfactures.php?message=error&error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Si l'ID de facture n'est pas spécifié, rediriger vers la liste des factures avec un message d'erreur
    header("Location: listfactures.php?message=missing_id");
    exit();
}
