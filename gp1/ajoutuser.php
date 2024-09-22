

<?php
session_start();

// Inclure le fichier de configuration de la base de données
global $db;
include 'config_bd.php';

// Vérifier si la requête est une requête POST et si le champ firstName est défini
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['firstName'])) {
    // Récupérer les données du formulaire
    $name = $_POST['firstName'];
    $namee = $_POST['lastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $numtel = $_POST['phoneNumber'];
    $date_naissance = $_POST['dateN'];
    $address = $_POST['address'];
    $id_role = $_POST['role']; // Assuming the role value is passed as a POST parameter

    // Hasher le mot de passe
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insérer les données dans la base de données
    try {
        $stmt = $db->prepare("INSERT INTO users (Firstname,Lastname, Email, password, Numtel, dateN, Adresse, id_role) VALUES (:name,:namee ,:email, :password, :numtel, :date_naissance, :address, :id_role)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':namee', $namee);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':numtel', $numtel);
        $stmt->bindParam(':date_naissance', $date_naissance);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':id_role', $id_role);

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
    <title>Account settings - Account | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <!-- Icons. Uncomment required icon fonts -->
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
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> Add Account</h4>
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <!-- Account -->

                        <hr class="my-0" />
                        <div class="card-body">
                                    <form id="formAccountSettings" method="POST" action="">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <label for="firstName" class="form-label">First Name</label>
                                                <input class="form-control" type="text" id="firstName" name="firstName" placeholder="first name" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="lastName" class="form-label">Last Name</label>
                                                <input class="form-control" type="text" id="lastName" name="lastName" placeholder="last name" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input class="form-control" type="email" id="email" name="email" placeholder="john.doe@example.com" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="password" class="form-label">Password</label>
                                                <input class="form-control" type="password" id="password" name="password" placeholder="Password" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                                <input class="form-control" type="text" id="phoneNumber" name="phoneNumber" placeholder="012 345 6789" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="dateN" class="form-label">Date of Birth</label>
                                                <input class="form-control" type="date" id="dateN" name="dateN" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="address" class="form-label">Address</label>
                                                <input class="form-control" type="text" id="address" name="address" placeholder="1234 Main St" />
                                            </div>
                                            <div class="mb-3 col-md-6">
                                                <label for="role" class="form-label">Role</label>
                                                <select class="select2 form-select" name="role">
                                                    <?php

                                                    $stmt = $db->query("SELECT * FROM role");
                                                    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($roles as $role) {
                                                        echo '<option value="' . $role['id_role'] . '">' . $role['nom_role'] . '</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary me-2">Save </button>
                                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the modal
        var modal = document.getElementById("myModal");

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
