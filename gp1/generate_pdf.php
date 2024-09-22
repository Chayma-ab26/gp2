<?php
// Inclure Dompdf
require_once 'Dompdf/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Connexion à la base de données
global $db;
include 'config_bd.php';

// Vérifier que l'ID de la facture est passé en paramètre
if (!isset($_GET['id_facture'])) {
    die('ID de facture non spécifié.');
}

$id_facture = htmlspecialchars($_GET['id_facture']);

// Requête pour obtenir les détails de la facture
$stmt_facture = $db->prepare("SELECT * FROM factures WHERE id_facture = ?");
$stmt_facture->execute([$id_facture]);
$facture = $stmt_facture->fetch(PDO::FETCH_ASSOC);

if (!$facture) {
    die('Facture non trouvée.');
}

// Extraire les données de la facture
$id_projet = $facture['id_projet'];
$id_clients = $facture['id_clients'];
$numero_facture = $facture['numero_facture'];
$date_emission = $facture['date_emission'];
$montant = $facture['montant'];
$status = $facture['status'];
$description = $facture['description'];
$nom_entreprise = $facture['nom_entreprise'];
$adresse_entreprise = $facture['adresse_entreprise'];
$taxes = $facture['taxes'];
$remises = $facture['remises'];
$montant_total = $facture['montant_total'];
$nomentrepriseemettrice = $facture['nomentrepriseemettrice'];
$adressentrepriseemettrice = $facture['adressentrepriseemettrice'];

// Requête pour obtenir le nom du projet
$stmt_projet = $db->prepare("SELECT projetname FROM projets WHERE id_projet = ?");
$stmt_projet->execute([$id_projet]);
$row_projet = $stmt_projet->fetch(PDO::FETCH_ASSOC);
$nom_projet = $row_projet['projetname'];

// Requête pour obtenir le nom du client
$stmt_client = $db->prepare("SELECT client_name FROM clients WHERE id_clients = ?");
$stmt_client->execute([$id_clients]);
$row_client = $stmt_client->fetch(PDO::FETCH_ASSOC);
$nom_client = $row_client['client_name'];

// Requête pour obtenir les tâches associées
$stmt_taches = $db->prepare("SELECT * FROM taches WHERE id_projet = ?");
$stmt_taches->execute([$id_projet]);
$taches = $stmt_taches->fetchAll(PDO::FETCH_ASSOC);

// Création du contenu HTML pour le PDF
$html = '
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture PDF</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .invoice { padding: 30px; border: 1px solid #ddd; border-radius: 8px; max-width: 800px; margin: 20px auto; background: #f9f9f9; }
        .invoice-header { border-bottom: 2px solid #0044cc; padding-bottom: 15px; margin-bottom: 20px; text-align: center; }
        .invoice-header h2 { color: #0044cc; margin: 0; font-size: 24px; }
        .invoice-footer { border-top: 2px solid #0044cc; padding-top: 15px; margin-top: 20px; text-align: center; }
        .invoice-details { margin-top: 20px; }
        .invoice-details h4 { border-bottom: 1px solid #0044cc; padding-bottom: 5px; color: #0044cc; }
        .invoice-details p { margin: 5px 0; line-height: 1.6; }
        .invoice-details .col { width: 48%; display: inline-block; vertical-align: top; }
        .invoice-details .col-right { text-align: right; }
        .tasks-table { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px; text-align: left; }
        th { background-color: #0044cc; color: #fff; }
        .invoice-footer p { margin: 0; color: #0044cc; font-size: 14px; }
        .footer-note { font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <h2>Facture</h2>
            <p><strong>Facture #'. htmlspecialchars($numero_facture) .'</strong></p>
        </div>

        <div class="invoice-details">
            <div class="col">
                <h4>Informations sur le Projet</h4>
                <p><strong>Projet :</strong> '. htmlspecialchars($nom_projet) .'</p>
                <p><strong>Date d\'émission :</strong> '. htmlspecialchars($date_emission) .'</p>
            </div>
            <div class="col col-right">
                <h4>Informations sur le Client</h4>
                <p><strong>Client :</strong> '. htmlspecialchars($nom_client) .'</p>
                <p><strong>Nom de l\'Entreprise :</strong> '. htmlspecialchars($nom_entreprise) .'</p>
                <p><strong>Adresse :</strong> '. htmlspecialchars($adresse_entreprise) .'</p>
            </div>
            <div class="col">
                <h4>Entreprise Émettrice</h4>
                <p><strong>Nom :</strong> '. htmlspecialchars($nomentrepriseemettrice) .'</p>
                <p><strong>Adresse :</strong> '. htmlspecialchars($adressentrepriseemettrice) .'</p>
            </div>
            <div class="col col-right">
                <h4>Détails de la Facture</h4>
                <p><strong>Montant :</strong> '. htmlspecialchars($montant) .' €</p>
                <p><strong>Status :</strong> '. htmlspecialchars($status) .'</p>
                <p><strong>Description :</strong> '. htmlspecialchars($description) .'</p>
                <p><strong>Taxes :</strong> '. htmlspecialchars($taxes) .' %</p>
                <p><strong>Remises :</strong> '. htmlspecialchars($remises) .' €</p>
                <p><strong>Montant Total :</strong> '. htmlspecialchars($montant_total) .' €</p>
            </div>
        </div>

        <!-- Affichage des tâches associées -->
        <div class="tasks-table">
            <h4>Tâches Associées</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom de la Tâche</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>';

foreach ($taches as $tache) {
    $html .= '<tr>
                  <td>'. htmlspecialchars($tache['id_tache']) .'</td>
                  <td>'. htmlspecialchars($tache['nomtache']) .'</td>
                  <td>'. htmlspecialchars($tache['date']) .'</td>
                  <td>'. htmlspecialchars($tache['status']) .'</td>
                  <td>'. htmlspecialchars($tache['descriptiontache']) .'</td>
              </tr>';
}

$html .= '    </tbody>
            </table>
        </div>

        <div class="invoice-footer">
            <p class="footer-note">Merci pour votre confiance. Veuillez nous contacter si vous avez des questions concernant cette facture.</p>
        </div>
    </div>
</body>
</html>';

// Instanciation et configuration de DomPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');

// Génération du PDF
$dompdf->render();
$dompdf->stream('facture_' . $id_facture . '.pdf', ['Attachment' => 1]);
?>
