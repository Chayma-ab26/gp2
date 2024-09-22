<?php
global $db;
session_start();

// Inclure la connexion à la base de données
include'config_bd.php'; // Remplacez par le chemin correct de votre fichier de connexion// Récupérer le nombre de projets
$sqlProjects = "SELECT COUNT(*) as total_projects FROM projets";
$resultProjects = $db->query($sqlProjects);
$rowProjects = $resultProjects->fetch(PDO::FETCH_ASSOC);
$totalProjects = $rowProjects['total_projects'];

// Récupérer le nombre de tâches en cours
$sqlTasksInProgress = "SELECT COUNT(*) as total_in_progress FROM taches WHERE status = 'in progress'";
$resultTasksInProgress = $db->query($sqlTasksInProgress);
$rowTasksInProgress = $resultTasksInProgress->fetch(PDO::FETCH_ASSOC);
$totalInProgress = $rowTasksInProgress['total_in_progress'];

// Récupérer le nombre de tâches complètes
$sqlTasksCompleted = "SELECT COUNT(*) as total_completed FROM taches WHERE status = 'completed'";
$resultTasksCompleted = $db->query($sqlTasksCompleted);
$rowTasksCompleted = $resultTasksCompleted->fetch(PDO::FETCH_ASSOC);
$totalCompleted = $rowTasksCompleted['total_completed'];


// Calcul du chiffre d'affaires total
$query_revenue = $db->query("SELECT SUM(montant) AS total_revenue FROM factures");
$result_revenue = $query_revenue->fetch(PDO::FETCH_ASSOC);
$total_revenue = $result_revenue['total_revenue'] ?? 0;

// Nombre de factures
$query_invoices = $db->query("SELECT COUNT(*) AS total_invoices FROM factures");
$result_invoices = $query_invoices->fetch(PDO::FETCH_ASSOC);
$total_invoices = $result_invoices['total_invoices'];
// Calcul du chiffre d'affaires en cours (factures en attente de paiement)
$query_pending_revenue = $db->query("SELECT SUM(montant) AS pending_revenue FROM factures WHERE status = 'en attente'");
$result_pending_revenue = $query_pending_revenue->fetch(PDO::FETCH_ASSOC);
$pending_revenue = $result_pending_revenue['pending_revenue'] ?? 0;
// Calcul du chiffre d'affaires payé
$query_paid_revenue = $db->query("SELECT SUM(montant) AS paid_revenue FROM factures WHERE status = 'Payé'");
$result_paid_revenue = $query_paid_revenue->fetch(PDO::FETCH_ASSOC);
$paid_revenue = $result_paid_revenue['paid_revenue'] ?? 0;

// Requête pour obtenir les heures totales travaillées par chaque consultant
$query = "
    SELECT u.firstname, u.lastname, SUM(t.hours) AS total_hours
    FROM users u
    JOIN taches t ON u.user_id = t.user_id
    WHERE u.id_role = 2
    GROUP BY u.user_id, u.firstname, u.lastname
    ORDER BY total_hours DESC
";

try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $consultants = [];
    $hours = [];

    // Traitement des résultats
    foreach ($results as $row) {
        $consultants[] = $row['firstname'] . ' ' . $row['lastname'];
        $hours[] = (int)$row['total_hours'];
    }

    // Encodage en JSON pour l'utilisation dans le script JS
    $data = [
        'consultants' => $consultants,
        'hours' => $hours
    ];
} catch (PDOException $e) {
    die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
}

// Requête pour obtenir le chiffre d'affaires par client
$query_clients_revenue = "
    SELECT c.client_name, SUM(f.montant) AS total_revenue_per_client
    FROM clients c
    JOIN factures f ON c.id_clients = f.id_clients
    GROUP BY c.client_name
    ORDER BY total_revenue_per_client DESC
";

try {
    $stmt_clients_revenue = $db->prepare($query_clients_revenue);
    $stmt_clients_revenue->execute();
    $clients_revenue_results = $stmt_clients_revenue->fetchAll(PDO::FETCH_ASSOC);

    $client_names = [];
    $client_revenues = [];

    // Traitement des résultats
    foreach ($clients_revenue_results as $row) {
        $client_names[] = $row['client_name'];
        $client_revenues[] = (float)$row['total_revenue_per_client'];
    }

    // Encodage en JSON pour l'utilisation dans le script JS
    $client_revenue_data = [
        'client_names' => $client_names,
        'client_revenues' => $client_revenues
    ];
} catch (PDOException $e) {
    die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Gestion de Projets</title>
    <!-- Intégration de Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <!-- DataTables CSS -->
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
</head>
<body>
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <?php
        include 'aside_nav.php';
        ?>


<main class="container my-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Summary of Activities</h5>
                    <p class="card-text">Total number of projects : <?php echo $totalProjects; ?></p>
                    <p class="card-text">Tasks in progress : <?php echo $totalInProgress; ?></p>
                    <p class="card-text">Completed tasks : <?php echo $totalCompleted; ?></p>
                </div>
            </div>


        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Rapid Actions</h5>
                    <a href="addprojet.php" class="btn btn-primary">Add project</a>
                    <a href="addfacture.php" class="btn btn-primary">Add invoice</a>
                    <a href="addclient.php" class="btn btn-primary">Add Client</a>
                    <a href="ajoutuser.php" class="btn btn-primary">Add User</a>

                </div>
            </div>
        </div>

        <div class="col-md-10">

            <div class="card mb-4 ">
                <div class="card-body">
                    <h5 class="card-title">Revenue</h5>
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                    <div class="stat mt-4">
                    <p>Total Revenue :</p>
                    <h4><?php echo number_format($total_revenue, 2, ',', ' ') . ' €'; ?>
                    </h4></div>

                    <div class="stat mt-2">
                        <p>Paid Revenue :</p>
                        <h4><?php echo number_format($paid_revenue, 2, ',', ' ') . ' €'; ?>
                        </h4>
                    </div>
                    <div class="stat mt-2">
                        <p>Revenue in Progress :</p>
                        <h4><?php echo number_format($pending_revenue, 2, ',', ' ') . ' €'; ?>
                            </h4>
                    </div>
                    <div class="stat mt-2">
                    <p>Total Number of Invoices :</p><h4><?php echo $total_invoices; ?></h4>
                    </div>
                </div>
            </div>
        <!-- Inclusion de la bibliothèque Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Task Distribution</h5>
                    <canvas id="taskDistributionChart"></canvas>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Consultant Performance</h5>
                    <canvas id="hoursDistributionChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div
    <!-- Inclusion de la bibliothèque Chart.js -->


    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Revenue by Client</h5>
            <canvas id="clientRevenueChart" width="400" height="200"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</main>



            <script>
                // Préparation des données pour le graphique
                const performanceData = <?php echo json_encode($data); ?>;
                const consultants = performanceData.consultants;
                const hours = performanceData.hours;
            </script>
            <script>
                // Préparation des données pour le graphique
                const clientRevenueData = <?php echo json_encode($client_revenue_data); ?>;
                const clientNames = clientRevenueData.client_names;
                const clientRevenues = clientRevenueData.client_revenues;
            </script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique de Chiffre d'Affaires
        const revenueChart = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueChart, {
            type: 'bar', // Type de graphique (barres)data: {
            data: {
                labels: ['Total','Paid' ,'In Progress'], // Labels des barresdatasets: [{
                datasets: [{
                    label: 'Revenue',
                    data: [<?php echo $total_revenue; ?>,<?php echo $paid_revenue; ?>,<?php echo $pending_revenue; ?>], // Données des barresbackgroundColor: ['rgba(75, 192, 192, 0.2)', 'rgba(255, 159, 64, 0.2)'], // Couleurs des barresborderColor: ['rgba(75, 192, 192, 1)', 'rgba(255, 159, 64, 1)'], // Couleur des borduresborderWidth: 1
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Graphique de Répartition des Tâches
        const taskDistributionChartCtx = document.getElementById('taskDistributionChart').getContext('2d');
        new Chart(taskDistributionChartCtx, {
            type: 'pie',
            data: {
                labels: ['InProgress', 'Completed'],
                datasets: [{
                    label: 'Task Distribution',
                    data: [<?php echo $totalInProgress; ?>,<?php echo $totalCompleted; ?>],
                    backgroundColor: ['#FFB6C1','#ADD8E6']

                }]
            }
        });

        // Graphique de Répartition des Heures
        const ctx = document.getElementById('hoursDistributionChart').getContext('2d');
        const hoursDistributionChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: consultants,
                datasets: [{
                    label: 'Hours Worked',
                    data: hours, // Utilise les heures travaillées
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Couleur de fond des barres
                    borderColor: 'rgba(255, 99, 132, 1)', // Couleur des bordures des barres
                    borderWidth: 1
                }]
            }

        });

// Script JavaScript pour afficher le graphique du chiffre d'affaires par client
        const ctxe = document.getElementById('clientRevenueChart').getContext('2d');
        const clientRevenueChart = new Chart(ctxe, {
            type: 'bar',
            data: {
                labels: clientNames, // Noms des clients
                datasets: [{
                    label: 'Revenue by Client(€)',
                    data: clientRevenues, // Chiffre d'affaires par client
                    backgroundColor: 'rgba(153, 102, 255, 0.2)', // Couleur de fond des barres
                    borderColor: 'rgba(153, 102, 255, 1)', // Couleur des bordures des barres
                    borderWidth: 1
                }]
            }
        });

    });
</script>
            <!-- Intégration de Bootstrap JS et des dépendances -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script src="../assets/vendor/libs/jquery/jquery.js"></script>
            <script src="../assets/vendor/libs/popper/popper.js"></script>
            <script src="../assets/vendor/js/bootstrap.js"></script>
            <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
            <script src="../assets/vendor/js/menu.js"></script>
            <script src="../assets/js/main.js"></script>
</body>
</html>
