<?php
session_start();

// Inclure le fichier de configuration de la base de données
global $db;
include 'config_bd.php';

// Vérifier si la requête est une requête POST et si le champ client_name est défini
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['client_name'])) {
    // Récupérer les données du formulaire
    $name = $_POST['client_name'];
    $company = $_POST['company'];
    $email = $_POST['Email'];
    $phone = $_POST['phone'];
    $adresse = $_POST['adress'];

    // Insérer les données dans la base de données
    try {
        $stmt = $db->prepare("INSERT INTO clients (client_name, company, Email, phone, adress) VALUES (:client_name, :company, :Email, :phone, :adress)");
        $stmt->bindParam(':client_name', $name);
        $stmt->bindParam(':company', $company);
        $stmt->bindParam(':Email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':adress', $adresse);
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Clients /</span> Add Client </h4>

                    <!-- Basic Layout -->
                    <div class="row">
                        <div class="col-xxl">
                            <div class="card mb-4">
                                <div class="card-header d-flex align-items-center justify-content-between">

                                </div>
                                <div class="card-body">
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-name">Name</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="basic-default-name" name="client_name" placeholder="John Doe" required />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-company">Company</label>
                                            <div class="col-sm-10">
                                                <input type="text" class="form-control" id="basic-default-company" name="company" placeholder="ACME Inc." required />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-email">Email</label>
                                            <div class="col-sm-10">
                                                <div class="input-group input-group-merge">
                                                    <input type="email" id="basic-default-email" class="form-control" name="Email" placeholder="john.doe@example.com" aria-label="john.doe" aria-describedby="basic-default-email2" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-phone">Phone Number</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="basic-default-phone" class="form-control phone-mask" name="phone" placeholder="658 799 8941" aria-label="658 799 8941" aria-describedby="basic-default-phone" required />
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-default-message">Address</label>
                                            <div class="col-sm-10">
                                                <input type="text" id="basic-default-message" class="form-control" name="adress" placeholder="123 Main St" aria-label="123 Main St" aria-describedby="basic-icon-default-message2" required />
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-sm-10">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->

                <!-- / Footer -->
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
<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<script src="../assets/js/main.js"></script>
<!-- Page JS -->
<script>
    // Script for modal display and close functionality
    var modal = document.getElementById("myModal");
    var span = document.getElementsByClassName("close")[0];

    if (modal) {
        modal.style.display = "block";
    }

    if (span) {
        span.onclick = function() {
            modal.style.display = "none";
        }
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>
