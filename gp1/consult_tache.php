<?php
// Activer l'affichage des erreurs pour le débogage
global $db;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config_bd.php';


// Vérifier si l'utilisateur est connecté et s'il est administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté ou n'est pas admin
    exit;
}

$user_id = $_SESSION['user_id'];

// Initialiser les filtres
$project_filter = isset($_GET['project']) ? $_GET['project'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$consultant_filter = isset($_GET['consultant']) ? $_GET['consultant'] : '';
$month_filter = isset($_GET['month']) ? $_GET['month'] : '';
$year_filter = isset($_GET['year']) ? $_GET['year'] : '';

// Requête pour obtenir les tâches en fonction des filtres
$sql = "SELECT taches.*, projets.projetname, users.firstname, users.lastname 
        FROM taches 
        INNER JOIN projets ON taches.id_projet = projets.id_projet 
        INNER JOIN users ON taches.user_id = users.user_id
        WHERE 1=1";

$params = [];

if ($project_filter) {
    $sql .= " AND projets.projetname LIKE :project";
    $params[':project'] = "%$project_filter%";
}

if ($status_filter) {
    $sql .= " AND taches.status = :status";
    $params[':status'] = $status_filter;
}

if ($consultant_filter) {
    $sql .= " AND users.user_id = :consultant";
    $params[':consultant'] = $consultant_filter;
}

if ($month_filter) {
    $sql .= " AND MONTH(taches.date) = :month";
    $params[':month'] = $month_filter;
}

if ($year_filter) {
    $sql .= " AND YEAR(taches.date) = :year";
    $params[':year'] = $year_filter;
}

$stmt = $db->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->execute();
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtenir la liste des projets pour le filtrage
$projects_stmt = $db->query("SELECT DISTINCT projetname FROM projets");
$projects = $projects_stmt->fetchAll(PDO::FETCH_COLUMN);

// Obtenir la liste des consultants pour le filtrage
$consultants_stmt = $db->query("SELECT user_id, firstname, lastname FROM users WHERE id_role = '2'");
$consultants = $consultants_stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtenir les mois et années disponibles pour le filtrage
$months = range(1, 12);
$years_stmt = $db->query("SELECT DISTINCT YEAR(date) AS year FROM taches ORDER BY year DESC");
$years = $years_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation des Tâches</title>
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css">
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css">
    <link rel="stylesheet" href="../assets/css/demo.css">
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        .status-in-progress {
            background-color: #ea8116; /* Jaune clair */
            color: #000; /* Texte noir pour contraste */
        }
        .status-completed {
            background-color: #4caf50; /* Vert */
            color: black;
        }
        .status-default {
            background-color: #f5f5f5; /* Gris clair */
        }
    </style>
</head>
<body>

<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
<?php
        include 'aside_nav.php';
?>
            <!-- / Navbar -->

            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4">Task List</h4>

                    <!-- Filtre -->
                    <form method="get" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="project">Project</label>
                                    <select id="project" name="project" class="form-control">
                                        <option value="">All Projects</option>
                                        <?php foreach ($projects as $project) : ?>
                                            <option value="<?php echo htmlspecialchars($project); ?>" <?php echo $project_filter == $project ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($project); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="form-control">
                                        <option value="">All Statuses</option>
                                        <option value="in progress" <?php echo $status_filter == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                                        <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="consultant">Consultant</label>
                                    <select id="consultant" name="consultant" class="form-control">
                                        <option value="">All Consultants</option>
                                        <?php foreach ($consultants as $consultant) : ?>
                                            <option value="<?php echo htmlspecialchars($consultant['user_id']); ?>" <?php echo $consultant_filter == $consultant['user_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($consultant['firstname'] . ' ' . $consultant['lastname']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="month">Month</label>
                                    <select id="month" name="month" class="form-control">
                                        <option value="">All Months</option>
                                        <?php foreach ($months as $month) : ?>
                                            <option value="<?php echo htmlspecialchars($month); ?>" <?php echo $month_filter == $month ? 'selected' : ''; ?>>
                                                <?php echo date('F', mktime(0, 0, 0, $month, 1)); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="year">Year</label>
                                    <select id="year" name="year" class="form-control">
                                        <option value="">All Years</option>
                                        <?php foreach ($years as $year) : ?>
                                            <option value="<?php echo htmlspecialchars($year); ?>" <?php echo $year_filter == $year ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($year); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mt-3">Filter</button>
                                <a href="export.php?export=excel" class="btn btn-success ml-6  mt-3">Export to Excel</a>
                            </div>
                        </div>
                    </form>

                    <!-- Tableau des tâches -->
                    <div class="card">
                        <h5 class="card-header">Task List</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Project</th>
                                    <th>Task Name</th>
                                    <th>Consultant</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($taches as $tache) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($tache['id_tache']); ?></td>
                                        <td><?php echo htmlspecialchars($tache['projetname']); ?></td>
                                        <td><?php echo htmlspecialchars($tache['nomtache']); ?></td>
                                        <td><?php echo htmlspecialchars($tache['firstname'] . ' ' . $tache['lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($tache['date']); ?></td>
                                        <td class="<?php echo $tache['status'] === 'completed' ? 'status-completed' : 'status-in_progress'; ?>">
                                            <?php echo htmlspecialchars(ucfirst($tache['status'])); ?>
                                        </td>

                                        <td><?php echo htmlspecialchars($tache['descriptiontache']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- / Content -->
            </div>
            <!-- / Content wrapper -->
        </div>
    </div>
</div>

<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
