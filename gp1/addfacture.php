<?php
global $db;
include 'config_bd.php';

// Récupérer la liste des projets avec les informations des clients
$projets_stmt = $db->query("SELECT p.id_projet, p.projetname, c.id_clients, c.client_name, c.company, c.Email, c.phone, c.adress 
                             FROM projets p 
                             JOIN clients c ON p.id_clients = c.id_clients");
$projets = $projets_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $id_projet = $_POST['id_projet'];
        $id_clients = $_POST['id_clients'];
        $numero_facture = $_POST['numero_facture'];
        $date_emission = $_POST['date_emission'];
        $montant = $_POST['montant'];
        $status = $_POST['status'];
        $description = $_POST['description'];
        $nom_entreprise = $_POST['nom_entreprise'];
        $adresse_entreprise = $_POST['adresse_entreprise'];
        $taxes = $_POST['taxes'];
        $remises = $_POST['remises'];
        $nomentrepriseemettrice = $_POST['nomentrepriseemettrice'];
        $adressentrepriseemettrice = $_POST['adressentrepriseemettrice'];

        // Calcul du montant total
        $montant_total = $montant + ($montant * ($taxes / 100)) - $remises;

        // Insertion de la facture dans la base de données
        $stmt = $db->prepare("INSERT INTO factures (id_projet, id_clients, numero_facture, date_emission, montant, status, description, nom_entreprise, adresse_entreprise, taxes, remises, montant_total, nomentrepriseemettrice, adressentrepriseemettrice) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_projet, $id_clients, $numero_facture, $date_emission, $montant, $status, $description, $nom_entreprise, $adresse_entreprise, $taxes, $remises, $montant_total, $nomentrepriseemettrice, $adressentrepriseemettrice]);

        // Récupérer l'ID de la facture insérée
        $facture_id = $db->lastInsertId();

        // Insérer les tâches sélectionnées dans la base de données
        if (!empty($_POST['taches'])) {
            foreach ($_POST['taches'] as $tache_id) {
                $stmt_tache = $db->prepare("SELECT * FROM taches WHERE id_tache = ?");
                $stmt_tache->execute([$tache_id]);
                $tache = $stmt_tache->fetch(PDO::FETCH_ASSOC);

                if ($tache) {
                    $stmt_insert_tache = $db->prepare("INSERT INTO facture_taches (id_facture, nomtache, descriptiontache, date, status) 
                                                       VALUES (?, ?, ?, ?, ?)");
                    $stmt_insert_tache->execute([$facture_id, $tache['nomtache'], $tache['descriptiontache'], $tache['date'], $tache['status']]);
                }
            }
        }

        header("Location: preview_facture.php?id_facture=" . urlencode($facture_id));
        exit;
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Erreur: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Facture</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
    <script>
        function updateClientInfo() {
            var select = document.getElementById('id_projet');
            var selectedOption = select.options[select.selectedIndex];
            document.getElementById('id_clients').value = selectedOption.getAttribute('data-client');
            document.getElementById('client_name').value = selectedOption.getAttribute('data-client-name');
            document.getElementById('adresse_entreprise').value = selectedOption.getAttribute('data-adress');
            document.getElementById('nom_entreprise').value = selectedOption.getAttribute('data-company');

            // Charger automatiquement les tâches liées au projet sélectionné
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'get_tasks.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('taches').innerHTML = xhr.responseText;
                }
            };
            xhr.send('id_projet=' + encodeURIComponent(selectedOption.value));
        }

        function validateForm() {
            var montant = parseFloat(document.querySelector('input[name="montant"]').value);
            var taxes = parseFloat(document.querySelector('input[name="taxes"]').value);
            var remises = parseFloat(document.querySelector('input[name="remises"]').value);
            if (isNaN(montant) || montant < 0) {
                alert("Le montant doit être un nombre positif.");
                return false;
            }
            if (isNaN(taxes) || taxes < 0) {
                alert("Les taxes doivent être un nombre positif.");
                return false;
            }
            if (isNaN(remises) || remises < 0) {
                alert("Les remises doivent être un nombre positif.");
                return false;
            }
            return true;
        }
    </script>
</head>


<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <?php
        include 'aside_nav.php';
        ?>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Create a New Invoice</h2>


    <form action="addfacture.php" method="POST" onsubmit="return validateForm()">
        <div class="row">
            <!-- Sélection du Projet -->
            <div class="form-group col-md-6 mb-3">
                <label for="id_projet">Project</label>
                <select name="id_projet" id="id_projet" class="form-control" onchange="updateClientInfo()" required>
                    <option value="">Select a Project</option>
                    <?php foreach ($projets as $projet): ?>
                        <option value="<?= htmlspecialchars($projet['id_projet']); ?>"
                                data-client="<?= htmlspecialchars($projet['id_clients']); ?>"
                                data-client-name="<?= htmlspecialchars($projet['client_name']); ?>"
                                data-company="<?= htmlspecialchars($projet['company']); ?>"
                                data-email="<?= htmlspecialchars($projet['Email']); ?>"
                                data-phone="<?= htmlspecialchars($projet['phone']); ?>"
                                data-adress="<?= htmlspecialchars($projet['adress']); ?>">
                            <?= htmlspecialchars($projet['projetname']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tâches associées au projet sélectionné -->
            <div class="form-group col-md-6 mb-3">
                <label for="taches">Tasks of the Selected Project</label>
                <div id="taches"></div>
            </div>
        </div>

        <div class="row">
            <!-- Sélection du Client (automatique) -->
            <div class="form-group col-md-6 mb-3">
                <label for="client_name">Client</label>
                <input type="text" id="client_name" name="client_name" class="form-control" readonly>
                <input type="hidden" name="id_clients" id="id_clients" required>
            </div>

            <!-- Numéro de Facture -->
            <div class="form-group col-md-6 mb-3">
                <label for="numero_facture">Invoice Number</label>
                <input type="text" name="numero_facture" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <!-- Date d'émission -->
            <div class="form-group col-md-6 mb-3">
                <label for="date_emission">Issue Date</label>
                <input type="date" name="date_emission" class="form-control" required>
            </div>

            <!-- Montant Total -->
            <div class="form-group col-md-6 mb-3">
                <label for="montant">Amount (€)</label>
                <input type="number" name="montant" class="form-control" step="0.01" required>
            </div>
        </div>

        <div class="row">
            <!-- Statut de la Facture -->
            <div class="form-group col-md-6 mb-3">
                <label for="status">Status</label>
                <select name="status" class="form-control" required>
                    <option value="Payé">Paid</option>
                    <option value="En attente">Pending</option>
                    <option value="Annulé">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="row">
            <!-- Nom de l'Entreprise (Client) -->
            <div class="form-group col-md-6 mb-3">
                <label for="nom_entreprise">Company Name (Client)</label>
                <input type="text" name="nom_entreprise" id="nom_entreprise" class="form-control" readonly>
            </div>

            <!-- Adresse de l'Entreprise (Client) -->
            <div class="form-group col-md-6 mb-3">
                <label for="adresse_entreprise">Company Address (Client)</label>
                <input type="text" name="adresse_entreprise" id="adresse_entreprise" class="form-control" readonly>
            </div>
        </div>

        <div class="row">
            <!-- Nom de l'Entreprise Émettrice -->
            <div class="form-group col-md-6 mb-3">
                <label for="nomentrepriseemettrice">Issuing Company Name</label>
                <input type="text" name="nomentrepriseemettrice" id="nomentrepriseemettrice" class="form-control" required>
            </div>

            <!-- Adresse de l'Entreprise Émettrice -->
            <div class="form-group col-md-6 mb-3">
                <label for="adressentrepriseemettrice">Issuing Company Address</label>
                <input type="text" name="adressentrepriseemettrice" id="adressentrepriseemettrice" class="form-control" required>
            </div>
        </div>

        <div class="row">
            <!-- Taxes -->
            <div class="form-group col-md-6 mb-3">
                <label for="taxes">Taxes (%)</label>
                <input type="number" name="taxes" class="form-control" step="0.01" required>
            </div>

            <!-- Remises -->
            <div class="form-group col-md-6 mb-3">
                <label for="remises">Discounts (€)</label>
                <input type="number" name="remises" class="form-control" step="0.01" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Invoice</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
