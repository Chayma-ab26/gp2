<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gestionprojet";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the database
$sql = "
    SELECT p.id_projet, p.projetname, c.client_name, u.firstname AS user_name, p.description, p.start_date, p.end_date
    FROM projets p
    JOIN clients c ON p.id_clients = c.id_clients
    JOIN users u ON p.user_id = u.user_id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Tables - Basic Tables | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>
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
<div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
        <?php
        include 'aside_nav.php';
        ?>
            <div class="content-wrapper">
                <div class="container-xxxl flex-grow-1 container-p-y">
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Projects /</span> Project List</h4>
                    <div class="card">
                        <h5 class="card-header">Projects</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Project Name</th>
                                    <th>Client Name</th>
                                    <th>User Name</th>
                                    <th>Description</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                <?php
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                                <td>" . htmlspecialchars($row["projetname"]) . "</td>
                                                <td>" . htmlspecialchars($row["client_name"]) . "</td>
                                                <td>" . htmlspecialchars($row["user_name"]) . "</td>
                                                <td>" . htmlspecialchars($row["description"]) . "</td>
                                                <td>" . htmlspecialchars($row["start_date"]) . "</td>
                                                <td>" . htmlspecialchars($row["end_date"]) . "</td>
                                                <td>
                                                    <a href='editprojet.php?id_projet=" . urlencode($row["id_projet"]) . "' class='btn btn-primary'>Edit</a>
                                                    <button class='btn btn-danger' onclick='confirmDelete(" . htmlspecialchars($row["id_projet"]) . ")'>Delete</button>
                                                </td>
                                            </tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        if (confirm("Are you sure you want to delete this project?")) {
            fetch('deleteprojet.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'id': id
                })
            })
                .then(response => response.text())
                .then(result => {
                    if (result === 'success') {
                        location.reload();
                    } else {
                        alert("Error deleting project: " + result);
                    }
                });
        }
    }
</script>

<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>

<?php
$conn->close();
?>
