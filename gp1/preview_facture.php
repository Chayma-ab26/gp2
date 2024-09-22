<?php
global $db;
include 'config_bd.php';

// Vérifier que les données ont été envoyées via GET
if (isset($_GET['id_facture'])) {
    $id_facture = htmlspecialchars($_GET['id_facture']);

    // Récupérer les données de la facture
    $stmt_facture = $db->prepare("SELECT * FROM factures WHERE id_facture = ?");
    $stmt_facture->execute([$id_facture]);
    $facture = $stmt_facture->fetch(PDO::FETCH_ASSOC);

    if (!$facture) {
        echo "<div class='alert alert-danger'>Erreur: Facture non trouvée.</div>";
        exit;
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

    // Récupérer le nom du projet
    $stmt_projet = $db->prepare("SELECT projetname FROM projets WHERE id_projet = ?");
    $stmt_projet->execute([$id_projet]);
    $row_projet = $stmt_projet->fetch(PDO::FETCH_ASSOC);
    $nom_projet = $row_projet['projetname'];

    // Récupérer le nom du client
    $stmt_client = $db->prepare("SELECT client_name FROM clients WHERE id_clients = ?");
    $stmt_client->execute([$id_clients]);
    $row_client = $stmt_client->fetch(PDO::FETCH_ASSOC);
    $nom_client = $row_client['client_name'];

    // Récupérer les tâches associées à cette facture
    $stmt_taches = $db->prepare("SELECT * FROM taches WHERE id_projet = ?");
    $stmt_taches->execute([$id_projet]);
    $taches = $stmt_taches->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<div class='alert alert-danger'>Erreur: Aucune donnée reçue.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévisualisation de la Facture</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <style>
        .invoice {
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            max-width: 800px;
            margin: 0 auto;
        }
        .invoice-header, .invoice-footer {
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
        }
        .invoice-footer {
            border-top: 2px solid #ddd;
            border-bottom: none;
            padding-top: 15px;
            margin-top: 15px;
        }
        .invoice-header h2, .invoice-footer p {
            margin: 0;
        }
        .invoice-details {
            margin-top: 20px;
        }
        .invoice-details p {
            margin: 5px 0;
        }
        .btn-group {
            display: flex;
            justify-content: space-between;
        }
        .tasks-table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="invoice">
        <div class="invoice-header">
            <h2 class="text-center">Facture</h2>
            <p class="text-center"><strong>Facture #<?= htmlspecialchars($numero_facture); ?></strong></p>
        </div>

        <div class="invoice-details">
            <div class="row">
                <div class="col-md-6">
                    <h5>Informations sur le Projet</h5>
                    <p><strong>Projet :</strong> <?= htmlspecialchars($nom_projet); ?></p>
                    <p><strong>Date d'émission :</strong> <?= htmlspecialchars($date_emission); ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <h5>Informations sur le Client</h5>
                    <p><strong>Client :</strong> <?= htmlspecialchars($nom_client); ?></p>
                    <p><strong>Nom de l'Entreprise :</strong> <?= htmlspecialchars($nom_entreprise); ?></p>
                    <p><strong>Adresse :</strong> <?= htmlspecialchars($adresse_entreprise); ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5>Entreprise Émettrice</h5>
                    <p><strong>Nom :</strong> <?= htmlspecialchars($nomentrepriseemettrice); ?></p>
                    <p><strong>Adresse :</strong> <?= htmlspecialchars($adressentrepriseemettrice); ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h5>Détails de la Facture</h5>
                    <p><strong>Montant :</strong> <?= htmlspecialchars($montant); ?> €</p>
                    <p><strong>Status :</strong> <?= htmlspecialchars($status); ?></p>
                    <p><strong>Description :</strong> <?= htmlspecialchars($description); ?></p>
                </div>
                <div class="col-md-6 text-right">
                    <p><strong>Taxes :</strong> <?= htmlspecialchars($taxes); ?> %</p>
                    <p><strong>Remises :</strong> <?= htmlspecialchars($remises); ?> €</p>
                    <p><strong>Montant Total :</strong> <?= htmlspecialchars($montant_total); ?> €</p>
                </div>
            </div>
        </div>

        <!-- Affichage des tâches associées -->
        <div class="tasks-table">
            <h4>Tâches Associées</h4>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la Tâche</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Description</th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($taches) > 0): ?>
                    <?php foreach ($taches as $tache): ?>
                        <tr>
                            <td><?= htmlspecialchars($tache['id_tache']); ?></td>
                            <td><?= htmlspecialchars($tache['nomtache']); ?></td>
                            <td><?= htmlspecialchars($tache['date']); ?></td>
                            <td><?= htmlspecialchars($tache['status']); ?></td>
                            <td><?= htmlspecialchars($tache['descriptiontache']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Aucune tâche trouvée pour ce projet.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="invoice-footer text-center">
            <div class="btn-group">
                <button onclick="window.print()" class="btn btn-secondary">Imprimer la Facture</button>
                <a href="generate_pdf.php?id_facture=<?= urlencode($id_facture); ?>" class="btn btn-success">Télécharger en PDF</a>
            </div>
            <a href="listfactures.php" class="btn btn-secondary mt-3">Retour à la Liste des Factures</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
</body>
</html>
