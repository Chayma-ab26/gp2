<?php
session_start();

// Inclure le fichier de configuration de la base de données
global $db;
include 'config_bd.php';

try {
    // Récupérer les clients existants
    $clientStmt = $db->query("SELECT id_clients, client_name FROM clients");
    $clients = $clientStmt->fetchAll(PDO::FETCH_ASSOC);

    // Récupérer les utilisateurs existants avec id_role = 2
    $userStmt = $db->prepare("SELECT user_id, firstname, lastname FROM users WHERE id_role = 2");
    $userStmt->execute(); // Ajouter cette ligne pour exécuter la requête préparée
    $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Failed to retrieve data: ' . $e->getMessage();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['projetname'])) {
    // Récupérer les données du formulaire
    $projetname = $_POST['projetname'];
    $id_clients = $_POST['id_clients'];
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Insérer les données dans la base de données
    try {
        $stmt = $db->prepare("INSERT INTO projets (projetname, id_clients, user_id, description, start_date, end_date) VALUES (:projetname, :id_clients, :user_id, :description, :start_date, :end_date)");
        $stmt->bindParam(':projetname', $projetname);
        $stmt->bindParam(':id_clients', $id_clients);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);

        $stmt->execute();

        echo '<div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Form submitted successfully!</p>
                </div>
            </div>';
    } catch (PDOException $e) {
        echo '<div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Form submission failed: ' . $e->getMessage() . '</p>
                </div>
            </div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Add Project</title>
    <meta name="description" content="" />
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
                    <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light">Projects /</span> Add Project
                    </h4>

                    <div class="row">
                        <div class="col-xxl">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label class="form-label" for="projetname">Project Name</label>
                                            <input type="text" class="form-control" id="projetname" name="projetname" placeholder="Enter project name" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="id_clients">Client</label>
                                            <select class="form-select" id="id_clients" name="id_clients" required>
                                                <option value="">Select client</option>
                                                <?php foreach ($clients as $client): ?>
                                                    <option value="<?= $client['id_clients'] ?>"><?= $client['client_name'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="user_id">User</label>
                                            <select class="form-select" id="user_id" name="user_id" required>
                                                <option value="">Select user</option>
                                                <?php foreach ($users as $user): ?>
                                                    <option value="<?= $user['user_id'] ?>"><?= $user['firstname'] ?>_<?= $user['lastname'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" placeholder="Enter project description" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="start_date">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="end_date">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- / Content wrapper -->
        </div>
        <!-- / Layout container -->
    </div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<script src="../assets/js/main.js"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
</body>
</html>
