<?php
// Connexion à la base de données
global $db;
include 'config_bd.php';

// Vérifier si l'ID de la facture est passé en paramètre GET ou POST
$id_facture = isset($_GET['id_facture']) ? $_GET['id_facture'] : (isset($_POST['id_facture']) ? $_POST['id_facture'] : null);

if (!$id_facture) {
    echo "<div class='alert alert-danger'>ID de facture non spécifié.</div>";
    exit;
}

// Récupérer les informations de la facture
$facture_stmt = $db->prepare("SELECT * FROM factures WHERE id_facture = ?");
$facture_stmt->execute([$id_facture]);
$facture = $facture_stmt->fetch(PDO::FETCH_ASSOC);

if (!$facture) {
    echo "<div class='alert alert-danger'>Facture non trouvée.</div>";
    exit;
}

// Récupérer la liste des projets et des clients
$projets_stmt = $db->query("SELECT p.id_projet, p.projetname, c.id_clients, c.client_name, c.company, c.Email, c.phone, c.adress 
                            FROM projets p 
                            JOIN clients c ON p.id_clients = c.id_clients");
$projets = $projets_stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement de la mise à jour du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Nettoyage des données entrées par l'utilisateur
        $id_projet = htmlspecialchars($_POST['id_projet']);
        $id_clients = htmlspecialchars($_POST['id_clients']);
        $numero_facture = htmlspecialchars($_POST['numero_facture']);
        $date_emission = htmlspecialchars($_POST['date_emission']);
        $montant = htmlspecialchars($_POST['montant']);
        $status = htmlspecialchars($_POST['status']);
        $description = htmlspecialchars($_POST['description']);
        $nom_entreprise = htmlspecialchars($_POST['nom_entreprise']);
        $adresse_entreprise = htmlspecialchars($_POST['adresse_entreprise']);
        $taxes = htmlspecialchars($_POST['taxes']);
        $remises = htmlspecialchars($_POST['remises']);
        $nomentrepriseemettrice = htmlspecialchars($_POST['nomentrepriseemettrice']);
        $adressentrepriseemettrice = htmlspecialchars($_POST['adressentrepriseemettrice']);

        // Vérifier si le client existe
        $client_stmt = $db->prepare("SELECT id_clients FROM clients WHERE id_clients = ?");
        $client_stmt->execute([$id_clients]);
        if (!$client_stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='alert alert-danger'>Le client sélectionné n'existe pas.</div>";
            exit;
        }

        // Vérifier si le projet existe
        $projet_stmt = $db->prepare("SELECT id_projet FROM projets WHERE id_projet = ?");
        $projet_stmt->execute([$id_projet]);
        if (!$projet_stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='alert alert-danger'>Le projet sélectionné n'existe pas.</div>";
            exit;
        }

        // Calcul du montant total
        $montant_total = $montant + ($montant * ($taxes / 100)) - $remises;

        // Mise à jour de la facture dans la base de données
        $stmt = $db->prepare("UPDATE factures SET id_projet = ?, id_clients = ?, numero_facture = ?, date_emission = ?, montant = ?, status = ?, description = ?, nom_entreprise = ?, adresse_entreprise = ?, taxes = ?, remises = ?, montant_total = ?, nomentrepriseemettrice = ?, adressentrepriseemettrice = ? WHERE id_facture = ?");
        $stmt->execute([$id_projet, $id_clients, $numero_facture, $date_emission, $montant, $status, $description, $nom_entreprise, $adresse_entreprise, $taxes, $remises, $montant_total, $nomentrepriseemettrice, $adressentrepriseemettrice, $id_facture]);

        // Redirection après mise à jour
        header("Location: edit_facture.php?id_facture=" . urlencode($id_facture));
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur lors de la mise à jour de la facture : " . htmlspecialchars($e->getMessage()) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice</title>
    <!-- Include your CSS files here -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css">
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css">
    <link rel="stylesheet" href="../assets/css/demo.css">
</head>

<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

        <!-- Sidebar -->
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <!-- Include your sidebar code here -->
        </aside>
        <!-- / Sidebar -->

        <div class="layout-page">
            <!-- Navbar -->
            <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">
                <!-- Include your navbar code here -->
            </nav>
            <!-- / Navbar -->

            <!-- Content -->
            <div class="container mt-5">
                <h2>Edit Invoice</h2>
                <form method="POST" action="edit_facture.php" class="form">
                    <input type="hidden" name="id_facture" value="<?php echo htmlspecialchars($id_facture); ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_projet" class="form-label">Project</label>
                            <select id="id_projet" name="id_projet" class="form-select" onchange="updateClientInfo()" required>
                                <option value="">Select a Project</option>
                                <?php foreach ($projets as $projet) : ?>
                                    <option value="<?php echo htmlspecialchars($projet['id_projet']); ?>"
                                            data-client-id="<?php echo htmlspecialchars($projet['id_clients']); ?>"
                                            data-client-name="<?php echo htmlspecialchars($projet['client_name']); ?>"
                                            data-company="<?php echo htmlspecialchars($projet['company']); ?>"
                                            data-address="<?php echo htmlspecialchars($projet['adress']); ?>"
                                        <?php echo $projet['id_projet'] == $facture['id_projet'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($projet['projetname']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="client_name" class="form-label">Client</label>
                            <input type="text" id="client_name" name="client_name" class="form-control" value="<?php echo htmlspecialchars($projet['client_name']); ?>" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero_facture" class="form-label">Invoice Number</label>
                            <input type="text" id="numero_facture" name="numero_facture" class="form-control" value="<?php echo htmlspecialchars($facture['numero_facture']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="date_emission" class="form-label">Issue Date</label>
                            <input type="date" id="date_emission" name="date_emission" class="form-control" value="<?php echo htmlspecialchars($facture['date_emission']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="montant" class="form-label">Amount</label>
                            <input type="number" step="0.01" id="montant" name="montant" class="form-control" value="<?php echo htmlspecialchars($facture['montant']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="Pending" <?php echo $facture['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Paid" <?php echo $facture['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($facture['description']); ?></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nom_entreprise" class="form-label">Issuing Company Name</label>
                            <input type="text" id="nom_entreprise" name="nom_entreprise" class="form-control" value="<?php echo htmlspecialchars($facture['nom_entreprise']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="adresse_entreprise" class="form-label">Issuing Company Address</label>
                            <input type="text" id="adresse_entreprise" name="adresse_entreprise" class="form-control" value="<?php echo htmlspecialchars($facture['adresse_entreprise']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="taxes" class="form-label">Taxes (%)</label>
                            <input type="number" step="0.01" id="taxes" name="taxes" class="form-control" value="<?php echo htmlspecialchars($facture['taxes']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="remises" class="form-label">Discounts</label>
                            <input type="number" step="0.01" id="remises" name="remises" class="form-control" value="<?php echo htmlspecialchars($facture['remises']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="nomentrepriseemettrice" class="form-label">Issuer's Company Name</label>
                            <input type="text" id="nomentrepriseemettrice" name="nomentrepriseemettrice" class="form-control" value="<?php echo htmlspecialchars($facture['nomentrepriseemettrice']); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="adressentrepriseemettrice" class="form-label">Issuer's Company Address</label>
                            <input type="text" id="adressentrepriseemettrice" name="adressentrepriseemettrice" class="form-control" value="<?php echo htmlspecialchars($facture['adressentrepriseemettrice']); ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Invoice</button>
                </form>
            </div>
            <!-- / Content -->
        </div>
    </div>
</div>

<script>
    // Function to update client information when a project is selected
    function updateClientInfo() {
        var projectSelect = document.getElementById('id_projet');
        var clientNameInput = document.getElementById('client_name');
        var selectedOption = projectSelect.options[projectSelect.selectedIndex];
        var clientName = selectedOption.getAttribute('data-client-name');

        clientNameInput.value = clientName;
    }

    // Call updateClientInfo when the page loads to set the initial values
    document.addEventListener('DOMContentLoaded', function() {
        updateClientInfo();
    });
</script>
</body>
</html>
