<?php
session_start();
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
                    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Users /</span> User List</h4>
                    <div class="card">
                        <h5 class="card-header">Admin</h5>
                        <div class="table-responsive text-nowrap">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Phone number</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Date of Birth</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                <?php
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
                                $sql = "SELECT user_id, firstname, lastname, numtel, email, adresse, dateN FROM users WHERE id_role = '1'";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    // Output data of each row
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                    <td>" . $row["firstname"]. "</td>
                                                    <td>" . $row["lastname"]. "</td>
                                                    <td>" . $row["numtel"]. "</td>
                                                    <td>" . $row["email"]. "</td>
                                                    <td>" . $row["adresse"]. "</td>
                                                    <td>" . $row["dateN"]. "</td>
                                                    <td><a href='modifuser.php?id=" . urlencode($row["user_id"]) . "' class='btn btn-primary'>Edit</a>
                                                    <button class='btn btn-danger' onclick='confirmDelete(" . $row["user_id"] . ")'>Delete</button></td>
                                                </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>No results found</td></tr>";
                                }
                                $conn->close();
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr class="my-5" />
                <div class="card">
                    <h5 class="card-header">Consultant</h5>
                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Phone number</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>Date of Birth</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                            <?php
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
                            $sql = "SELECT user_id, firstname, lastname, numtel, email, adresse, dateN FROM users WHERE id_role = '2'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                                    <td>" . $row["firstname"]. "</td>
                                                    <td>" . $row["lastname"]. "</td>
                                                    <td>" . $row["numtel"]. "</td>
                                                    <td>" . $row["email"]. "</td>
                                                    <td>" . $row["adresse"]. "</td>
                                                    <td>" . $row["dateN"]. "</td>
                                                    <td><a href='modifuser.php?id=" . urlencode($row["user_id"]) . "' class='btn btn-primary'>Edit</a>
                                                    <button class='btn btn-danger' onclick='confirmDelete(" . $row["user_id"] . ")'>Delete</button></td>
                                                </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No results found</td></tr>";
                            }
                            $conn->close();
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr class="my-5" />

        </div>
    </div>
</div>

<div class="layout-overlay layout-menu-toggle"></div>
<div class="drag-target"></div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="deleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/vendor/libs/jquery/jquery.js"></script>
<script src="../assets/vendor/libs/popper/popper.js"></script>
<script src="../assets/vendor/js/bootstrap.js"></script>
<script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="../assets/vendor/js/menu.js"></script>
<script src="../assets/js/main.js"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>

<script>
    function confirmDelete(user_Id) {
        $('#deleteModal').modal('show');
        $('#deleteButton').attr('data-user-id', user_Id);
    }

    $('#deleteButton').click(function() {
        var user_Id = $(this).attr('data-user-id');
        $.ajax({
            url: 'deleteuser.php',
            type: 'POST',
            data: { id: user_Id },
            success: function(response) {
                if (response == 'success') {
                    location.reload();
                } else {
                    alert('Error deleting user: ' + response);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert('AJAX error: ' + error);
            }
        });
    });
</script>

</body>
</html>
