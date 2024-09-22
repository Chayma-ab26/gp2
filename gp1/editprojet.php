<?php
session_start();

// Inclure le fichier de configuration de la base de données
global $db;
include 'config_bd.php';

// Debugging: afficher les variables GET
var_dump($_GET); // Ajoutez cette ligne pour vérifier les paramètres d'URL

// Vérifier si l'ID du projet est passé dans l'URL
if (isset($_GET['id_projet']) && !empty($_GET['id_projet'])) {
    $projet_id = $_GET['id_projet'];

    // Préparer et exécuter la requête pour obtenir les informations du projet
    try {
        $stmt = $db->prepare("SELECT projetname, id_clients, user_id, description, start_date, end_date FROM projets WHERE id_projet = :id_projet");
        $stmt->bindParam(':id_projet', $projet_id, PDO::PARAM_INT);
        $stmt->execute();
        $projet = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si le projet existe
        if (!$projet) {
            die('No valid project ID provided');
        }

        // Récupérer le nom du client
        $stmt = $db->prepare("SELECT client_name FROM clients WHERE id_clients = :id_clients");
        $stmt->bindParam(':id_clients', $projet['id_clients'], PDO::PARAM_INT);
        $stmt->execute();
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer le nom de l'utilisateur
        $stmt = $db->prepare("SELECT firstname, lastname FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $projet['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $client_name = $client ? $client['client_name'] : 'Unknown';
        $firstname = $user ? $user['firstname'] : 'Unknown';
        $lastname = $user ? $user['lastname'] : 'Unknown';

        // Récupérer tous les clients pour le menu déroulant
        $stmt = $db->prepare("SELECT id_clients, client_name FROM clients");
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Récupérer tous les utilisateurs avec le rôle 2 pour le menu déroulant
        $stmt = $db->prepare("SELECT user_id, firstname, lastname FROM users WHERE id_role = 2");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die('Database error: ' . $e->getMessage());
    }
} else {
    die('No project ID provided');
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $projetname = $_POST['projetname'];
    $id_clients = $_POST['id_clients'];
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Mettre à jour les informations du projet dans la base de données
    try {
        $stmt = $db->prepare("UPDATE projets SET projetname = :projetname, id_clients = :id_clients, user_id = :user_id, description = :description, start_date = :start_date, end_date = :end_date WHERE id_projet = :id_projet");
        $stmt->bindParam(':projetname', $projetname);
        $stmt->bindParam(':id_clients', $id_clients, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':id_projet', $projet_id, PDO::PARAM_INT);
        $stmt->execute();

        $success_message = 'Project updated successfully!';
    } catch (PDOException $e) {
        $error_message = 'Update failed: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Edit Project | Your Project Management</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700&display=swap" rel="stylesheet" />
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Projects /</span> Edit Project</h4>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <h5 class="card-header">Edit Project Details</h5>
                                <div class="card-body">
                                    <!-- Display success or error messages -->
                                    <?php if (isset($success_message)): ?>
                                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                                    <?php elseif (isset($error_message)): ?>
                                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                    <?php endif; ?>

                                    <!-- Edit project form -->
                                    <form action="editproject.php?id_projet=<?php echo $projet_id; ?>" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label" for="projetname">Project Name</label>
                                            <input type="text" class="form-control" id="projetname" name="projetname" value="<?php echo htmlspecialchars($projet['projetname']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="id_clients">Client</label>
                                            <select class="form-select" id="id_clients" name="id_clients" required>
                                                <?php foreach ($clients as $client): ?>
                                                    <option value="<?php echo $client['id_clients']; ?>" <?php echo $client['id_clients'] == $projet['id_clients'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($client['client_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="user_id">User</label>
                                            <select class="form-select" id="user_id" name="user_id" required>
                                                <?php foreach ($users as $user): ?>
                                                    <option value="<?php echo $user['user_id']; ?>" <?php echo $user['user_id'] == $projet['user_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="description">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($projet['description']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="start_date">Start Date</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo htmlspecialchars($projet['start_date']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label" for="end_date">End Date</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo htmlspecialchars($projet['end_date']); ?>" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update Project</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- / Content -->

                <!-- Footer -->

                <!-- / Footer -->

                <div class="content-backdrop fade"></div>
            </div>
            <!-- / Content wrapper -->
        </div>
        <!-- / Layout container -->
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
