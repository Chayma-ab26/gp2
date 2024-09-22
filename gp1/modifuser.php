<?php
session_start();

// Inclure le fichier de configuration de la base de données
global $db;
include 'config_bd.php';

// Vérifier si l'ID de l'utilisateur est passé dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid user ID');
}

$user_id = intval($_GET['id']); // Convertir en entier pour éviter les injections

// Récupérer les informations de l'utilisateur
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$users) {
    die('User not found');
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire en les nettoyant
    $name = htmlspecialchars($_POST['firstName']);
    $namee = htmlspecialchars($_POST['lastName']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $numtel = htmlspecialchars($_POST['phoneNumber']);
    $date_naissance = $_POST['dateN'];
    $address = htmlspecialchars($_POST['address']);
    $id_role = intval($_POST['role']); // Convertir en entier pour éviter les injections

    // Valider les données
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format';
    } elseif (empty($password)) {
        // Ne pas mettre à jour le mot de passe si le champ est vide
        $hashed_password = $users['password'];
    } else {
        // Hasher le mot de passe si changé
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    }

    if (!isset($error)) {
        try {
            $stmt = $db->prepare("UPDATE users SET Firstname = :name, Lastname = :namee, Email = :email, password = :password, Numtel = :numtel, dateN = :date_naissance, Adresse = :address, id_role = :id_role WHERE user_id = :user_id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':namee', $namee);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':numtel', $numtel);
            $stmt->bindParam(':date_naissance', $date_naissance);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':id_role', $id_role);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            $stmt->execute();
            $success = 'User updated successfully!';
        } catch (PDOException $e) {
            $error = 'Update failed: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Edit User</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons -->
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="../assets/vendor/js/helpers.js"></script>
    <!-- Template customizer & Theme config files -->
    <script src="../assets/js/config.js"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Edit User</h4>
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">

                            </div>
                        </div>
                        <hr class="my-0" />
                        <div class="card-body">
                            <form id="formAccountSettings" method="POST" action="">
                                <div class="row">
                                    <div class="mb-3 col-md-6">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input class="form-control" type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($users['firstname']); ?>" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input class="form-control" type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($users['lastname']); ?>" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input class="form-control" type="email" id="email" name="email" value="<?php echo htmlspecialchars($users['Email']); ?>" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <input class="form-control" type="password" id="password" name="password" placeholder="New Password" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="phoneNumber" class="form-label">Phone Number</label>
                                        <input class="form-control" type="text" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($users['numtel']); ?>" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="dateN" class="form-label">Date of Birth</label>
                                        <input class="form-control" type="date" id="dateN" name="dateN" value="<?php echo htmlspecialchars($users['dateN']); ?>" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="address" class="form-label">Adress</label>
                                        <input class="form-control" type="text" id="address" name="address" value="<?php echo htmlspecialchars($users['adresse']); ?>" />
                                    </div>
                                    <div class="mb-3 col-md-6">
                                        <label for="role" class="form-label">Role</label>
                                        <select class="select2 form-select" name="role">
                                            <?php
                                            $stmt = $db->query("SELECT * FROM role");
                                            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($roles as $role) {
                                                $selected = ($role['id_role'] == $users['id_role']) ? 'selected' : '';
                                                echo '<option value="' . $role['id_role'] . '" ' . $selected . '>' . $role['nom_role'] . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-2">Save</button>
                                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- / Content -->
                <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
    </div>
    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
</div>
<!-- / Layout wrapper -->

<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<!-- endbuild -->
<!-- Vendors JS -->
<!-- Main JS -->
<script src="../assets/js/main.js"></script>
<!-- Page JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the modal
        var modal = document.getElementById("myModal");
        var success = "<?php echo isset($success) ? $success : ''; ?>";
        var error = "<?php echo isset($error) ? $error : ''; ?>";

        if (success || error) {
            modal.style.display = "block";
            document.getElementById("modalMessage").innerText = success || error;
        }

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    });
</script>
</body>
</html>
