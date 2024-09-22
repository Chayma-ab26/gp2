<?php
// Activer l'affichage des erreurs pour le débogage
global $db;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config_bd.php';

// Vérifier si l'utilisateur est connecté et obtenir son ID
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$user_id = $_SESSION['user_id'];

// Requête pour obtenir les projets assignés au consultant connecté
$stmt = $db->prepare("
    SELECT taches.*, projets.projetname 
    FROM taches 
    INNER JOIN projets ON taches.id_projet = projets.id_projet 
    WHERE taches.user_id = :user_id
");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$taches = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Tâches</title>
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
            color: white;
        }
        .status-default {
            background-color: #f5f5f5; /* Gris clair */
        }
        .select-in-progress {
            background-color: #ea8116; /* Jaune clair */
        }
        .select-completed {
            background-color: #4caf50; /* Vert */
            color: white;
        }
    </style>
</head>
<body>
<!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <!-- Menu -->
        <div class="layout-wrapper layout-content-navbar">
            <div class="layout-container">
                <!-- Menu -->
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    <div class="app-brand demo">
                        <a href="home.php" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <!-- SVG logo code here -->
                    </span>
                            <span class="app-brand-text demo menu-text fw-bolder ms-2">Dyna-Projects</span>
                        </a>
                        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                            <i class="bx bx-chevron-left bx-sm align-middle"></i>
                        </a>
                    </div>
                    <div class="menu-inner-shadow"></div>
                    <ul class="menu-inner py-1">
                        <li class="menu-item">
                            <a href="home.php" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Analytics">Home</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="listtasks.php" class="menu-link">
                                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                                <div data-i18n="Analytics">Tasks list</div>
                            </a>
                        </li>

                        <li class="menu-item">
                            <a href="javascript:void(0)" class="menu-link menu-toggle">
                                <i class="menu-icon tf-icons bx bx-copy"></i>
                                <div data-i18n="Extended UI">Project </div>
                            </a>
                            <ul class="menu-sub">
                                <li class="menu-item">
                                    <a href="listprojetassigne.php" class="menu-link">
                                        <div data-i18n="Perfect Scrollbar">List project assigned</div>
                                    </a>
                                </li>


                            </ul>
                        </li>

                    </ul>
                    </li>
                    </ul>
                </aside>
                <!-- Layout container -->
                <div class="layout-page">
                    <!-- Navbar -->
                    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="bx bx-menu bx-sm"></i>
                            </a>
                        </div>
                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item d-flex align-items-center">
                                    <!-- Search bar or other navbar content here -->
                                </div>
                            </div>
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <li class="nav-item lh-1 me-3"></li>
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <p>Profil</p>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span></span>
                                                        <span class="fw-semibold d-block">
                                                    <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                                                </span>
                                                        <small class="text-muted"><?php echo htmlspecialchars($_SESSION['role']); ?></small>
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="myprofile.php">
                                                <i class="bx bx-user me-2"></i>
                                                <span class="align-middle">My Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="logout.php">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </nav>
            <!-- Content wrapper -->
            <div class="content-wrapper">
                <!-- Content -->
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h1>Tasks List</h1>
                    <?php if (!empty($taches)): ?>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID Tâche</th>
                                <th>Projet Name</th>
                                <th>Task name</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($taches as $tache): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($tache['id_tache']); ?></td>
                                    <td><?php echo htmlspecialchars($tache['projetname']); ?></td>
                                    <td><?php echo htmlspecialchars($tache['nomtache']); ?></td>
                                    <td><?php echo htmlspecialchars($tache['descriptiontache']); ?></td>
                                    <td><?php echo htmlspecialchars($tache['date']); ?></td>
                                    <td>
                                        <form action="edit_status.php" method="POST" class="d-inline">
                                            <input type="hidden" name="id_tache" value="<?php echo htmlspecialchars($tache['id_tache']); ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="in progress" <?php echo ($tache['status'] === 'in progress') ? 'selected' : ''; ?>>In Progress</option>
                                                <option value="completed" <?php echo ($tache['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="edit_task.php?id_tache=<?php echo htmlspecialchars($tache['id_tache']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="deletetask.php?id_tache=<?php echo htmlspecialchars($tache['id_tache']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Aucune tâche trouvée.</p>
                    <?php endif; ?>
                </div>
                <!-- / Content -->

                <!-- Footer -->
                <!-- / Footer -->
            </div>
            <!-- / Content wrapper -->
        </div>
        <!-- / Layout container -->
    </div>
    <!-- / Layout wrapper -->
</div>


        <script src="../assets/vendor/libs/jquery/jquery.js"></script>
        <script src="../assets/vendor/libs/popper/popper.js"></script>
        <script src="../assets/vendor/js/bootstrap.js"></script>
        <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
        <script src="../assets/vendor/js/menu.js"></script>
        <script src="../assets/js/main.js"></script>
        <script async defer src="https://buttons.github.io/buttons.js"></script>

<script>
    function updateSelectStyle(selectElement) {
        var status = selectElement.value;
        if (status === 'in progress') {
            selectElement.classList.add('select-in-progress');
            selectElement.classList.remove('select-completed');
        } else if (status === 'completed') {
            selectElement.classList.add('select-completed');
            selectElement.classList.remove('select-in-progress');
        } else {
            selectElement.classList.remove('select-in-progress', 'select-completed');
        }
    }

    document.querySelectorAll('.status-select').forEach(function(select) {
        updateSelectStyle(select);
    });
</script>
</body>
</html>