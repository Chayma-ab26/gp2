<?php
// finalize_facture.php : Traitement final de la facture

// Connexion à la base de données
global $conn;
include 'config_bd.php';

// Vérifier que la méthode de la requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_projet = isset($_POST['id_projet']) ? htmlspecialchars($_POST['id_projet']) : '';
    $id_clients = isset($_POST['id_clients']) ? htmlspecialchars($_POST['id_clients']) : '';
    $numero_facture = isset($_POST['numero_facture']) ? htmlspecialchars($_POST['numero_facture']) : '';
    $date_emission = isset($_POST['date_emission']) ? htmlspecialchars($_POST['date_emission']) : '';
    $montant = isset($_POST['montant']) ? htmlspecialchars($_POST['montant']) : '';
    $status = isset($_POST['status']) ? htmlspecialchars($_POST['status']) : '';
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '';
    $nom_entreprise = isset($_POST['nom_entreprise']) ? htmlspecialchars($_POST['nom_entreprise']) : '';
    $adresse_entreprise = isset($_POST['adresse_entreprise']) ? htmlspecialchars($_POST['adresse_entreprise']) : '';
    $taxes = isset($_POST['taxes']) ? htmlspecialchars($_POST['taxes']) : '';
    $remises = isset($_POST['remises']) ? htmlspecialchars($_POST['remises']) : '';
    $montant_total = isset($_POST['montant_total']) ? htmlspecialchars($_POST['montant_total']) : '';

    // Vérifier que toutes les variables nécessaires sont présentes et non vides
    if (empty($id_projet) || empty($id_clients) || empty($numero_facture) || empty($date_emission) || empty($montant) || empty($status) || empty($description) || empty($nom_entreprise) || empty($adresse_entreprise) || empty($taxes) || empty($remises) || empty($montant_total)) {
        echo "<div class='alert alert-danger'>Erreur: Données manquantes.</div>";
        exit;
    }

    // Insérer les données dans la table des factures
    $sql = "INSERT INTO factures (id_projet, id_clients, numero_facture, date_emission, montant, status, description, nom_entreprise, adresse_entreprise, taxes, remises, montant_total) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssssssss", $id_projet, $id_clients, $numero_facture, $date_emission, $montant, $status, $description, $nom_entreprise, $adresse_entreprise, $taxes, $remises, $montant_total);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Facture créée avec succès !</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur lors de la création de la facture: " . $conn->error . "</div>";
    }

    $stmt->close();
    $conn->close();

} else {
    // Si la méthode n'est pas POST, afficher un message d'erreur
    echo "<div class='alert alert-danger'>Erreur: Aucune donnée reçue.</div>";
    exit;
}
?>
