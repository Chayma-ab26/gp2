<?php
global $db;
session_start();
include 'config_bd.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérez les informations de l'utilisateur connecté
$userId = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM users WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifiez si les données utilisateur existent
if (!$user) {
    echo "User not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>My Profile</title>
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
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="index.html" class="app-brand-link">
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
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-copy"></i>
                        <div data-i18n="Extended UI">Users </div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="listuser.php" class="menu-link">
                                <div data-i18n="Perfect Scrollbar">List user</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="ajoutuser.php" class="menu-link">
                                <div data-i18n="Perfect Scrollbar">Add user</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-copy"></i>
                        <div data-i18n="Extended UI">Clients </div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="listclient.php" class="menu-link">
                                <div data-i18n="Perfect Scrollbar">List clients</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="addclient.php" class="menu-link">
                                <div data-i18n="Perfect Scrollbar">Add client</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-copy"></i>
                        <div data-i18n="Extended UI">Projects </div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="listprojet.php" class="menu-link">
                                <div data-i18n="Perfect Scrollbar">List project</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="addprojet.php" class="menu-link">
                                <div data-i18n="Perfect Scrollbar">Add project</div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </aside>
        <div class="layout-page">

            <div class="content-wrapper">
                <a href="myprofile.php">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4">My Profile</h4>
                </a>
                    <div class="card mb-4">
                        <h5 class="card-header">Profile Details</h5>
                        <div class="card-body">
                            <div class="row mt-4">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">First Name</label>
                                    <p class="form-control"><?php echo htmlspecialchars($user['firstname']); ?></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Last Name</label>
                                    <p class="form-control"><?php echo htmlspecialchars($user['lastname']); ?></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Email</label>
                                    <p class="form-control"><?php echo htmlspecialchars($user['Email']); ?></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <p class="form-control"><?php echo htmlspecialchars($user['numtel']); ?></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Date of Birth</label>
                                    <p class="form-control"><?php echo htmlspecialchars($user['dateN']); ?></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Adress</label>
                                    <p class="form-control"><?php echo htmlspecialchars($user['adresse']); ?></p>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Role</label>
                                    <p class="form-control"><?php
                                        // Assuming the role name is stored in a column `role_name`
                                        $stmt = $db->prepare("SELECT nom_role FROM role WHERE id_role = :id_role");
                                        $stmt->bindParam(':id_role', $user['id_role']);
                                        $stmt->execute();
                                        $role = $stmt->fetch(PDO::FETCH_ASSOC);
                                        echo htmlspecialchars($role['nom_role']);
                                        ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
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
