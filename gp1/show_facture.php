<?php
// Inclure la connexion à la base de données
include 'config_bd.php';
global $db;

// Vérifier si un ID de facture a été spécifié dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_facture = intval($_GET['id']);

    try {
        // Préparer et exécuter la requête pour récupérer les détails de la facture
        $stmt = $db->prepare("SELECT * FROM factures WHERE id_facture = :id_facture");
        $stmt->bindParam(':id_facture', $id_facture, PDO::PARAM_INT);
        $stmt->execute();
        $facture = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si la facture existe
        if ($facture) {
            // Préparer la requête pour obtenir les détails du projet et du client associés à la facture
            $stmt_projet = $db->prepare("
                SELECT p.projetname, c.client_name, c.company
                FROM projets p
                INNER JOIN clients c ON p.id_clients = c.id_clients
                WHERE p.id_projet = :id_projet
            ");
            $stmt_projet->bindParam(':id_projet', $facture['id_projet'], PDO::PARAM_INT);
            $stmt_projet->execute();
            $projet = $stmt_projet->fetch(PDO::FETCH_ASSOC);

            // Si les détails du projet et du client sont trouvés, les fusionner avec les données de la facture
            if ($projet) {
                $facture = array_merge($facture, $projet);
            }
        } else {
            // Si la facture n'existe pas, rediriger vers la liste des factures avec un message d'erreur
            header("Location: listfactures.php?message=facture_not_found");
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Facture</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }
        .invoice-header {
            background-color: #007bff;
            color: #fff;
            padding: 30px 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .invoice-header h2 {
            margin: 0;
            font-size: 2.5rem;
        }
        .invoice-details {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .invoice-details th {
            background-color: #f8f9fa;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
        }
        .invoice-details td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            border-radius: 8px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.9;
        }
        .invoice-footer {
            text-align: center;
            margin-top: 40px;
            padding: 20px;
            background-color: #ffffff;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="invoice-header">
        <h2>Invoice Details</h2>
    </div>

    <div class="invoice-details">
        <table class="table">
            <tr>
                <th>ID Facture</th>
                <td><?php echo htmlspecialchars($facture['id_facture']); ?></td>
            </tr>
            <tr>
                <th>Projet</th>
                <td><?php echo htmlspecialchars($facture['projetname']); ?></td>
            </tr>
            <tr>
                <th>Client</th>
                <td><?php echo htmlspecialchars($facture['client_name']); ?></td>
            </tr>
            <tr>
                <th>Montant</th>
                <td><?php echo htmlspecialchars($facture['montant']); ?> €</td>
            </tr>
            <tr>
                <th>Date d'Émission</th>
                <td><?php echo htmlspecialchars($facture['date_emission']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars($facture['status']); ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo nl2br(htmlspecialchars($facture['description'])); ?></td>
            </tr>
            <tr>
                <th>Nom de l'entreprise émettrice</th>
                <td><?php echo htmlspecialchars($facture['nomentrepriseemettrice']); ?></td>
            </tr>
            <tr>
                <th>Adresse de l'entreprise émettrice</th>
                <td><?php echo htmlspecialchars($facture['adressentrepriseemettrice']); ?></td>
            </tr>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="listfactures.php" class="btn btn-secondary">Retour à la liste</a>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
