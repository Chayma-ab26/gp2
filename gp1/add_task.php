<?php
// Activer l'affichage des erreurs pour le débogage
global $db;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config_bd.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    exit;
}

$user_id = $_SESSION['user_id'];

// Vérifier si l'ID du projet est passé via GET
if (!isset($_GET['id_projet']) || empty($_GET['id_projet'])) {
    header('Location: listtasks.php'); // Rediriger si l'ID du projet est manquant
    exit;
}

$id_projet = $_GET['id_projet'];

// Requête pour obtenir les détails du projet
$stmt = $db->prepare("SELECT * FROM projets WHERE id_projet = :id_projet");
$stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
$stmt->execute();
$projet = $stmt->fetch(PDO::FETCH_ASSOC);

// Traitement du formulaire d'ajout de tâche
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_task'])) {
    $nomtache = htmlspecialchars($_POST['nomtache']);
    $descriptiontache = htmlspecialchars($_POST['descriptiontache']);
    $date = $_POST['date'];
    $status = isset($_POST['status']) ? $_POST['status'] : 'in progress'; // Définir une valeur par défaut si 'status' n'est pas défini

    $hours = $_POST['hours']; // Récupérer le nombre d'heures

    try {
        // Insertion de la tâche dans la base de données
        $stmt = $db->prepare("INSERT INTO taches (id_projet, nomtache, descriptiontache, date, status,hours, user_id) 
                              VALUES (:id_projet, :nomtache, :descriptiontache, :date, :status,:hours, :user_id)");
        $stmt->bindParam(':id_projet', $id_projet, PDO::PARAM_INT);
        $stmt->bindParam(':nomtache', $nomtache, PDO::PARAM_STR);
        $stmt->bindParam(':descriptiontache', $descriptiontache, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':hours', $hours, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirection après succès
        header('Location: listprojetassigne.php');
        exit();
    } catch (PDOException $e) {
        $error_message = "Erreur lors de l'ajout de la tâche : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Tâche</title>
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css">
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css">
    <link rel="stylesheet" href="../assets/css/demo.css">
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
                    <h1>Add task</h1>
                    <form action="add_task.php?id_projet=<?php echo htmlspecialchars($id_projet); ?>" method="POST">
                        <div class="mb-3">
                            <label for="nomtache" class="form-label">Task name</label>
                            <input type="text" class="form-control" id="nomtache" name="nomtache" required>
                        </div>
                        <div class="mb-3">
                            <label for="descriptiontache" class="form-label">Task description</label>
                            <textarea class="form-control" id="descriptiontache" name="descriptiontache" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label for="hours" class="form-label">Number of hours</label>
                            <input type="number" class="form-control" id="hours" name="hours" step="0.1" min="0" required>
                        </div>
                        <button type="submit" name="add_task" class="btn btn-primary">Save</button>
                    </form>
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

</body>
</html>
