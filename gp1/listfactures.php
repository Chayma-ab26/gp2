 <?php
        session_start();
?>
 <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Factures</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
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
            <div class="container mt-5">
    <h2 class="mb-4">Invoice List</h2>
    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-primary" onclick="window.location.href='addfacture.php'">New</button>
    </div>

    <table class="table table-striped" id="facturesTable">
        <thead>
        <tr>
            <th>#</th>
            <th>Client</th>
            <th>Project</th>
            <th>Amount</th>
            <th>Issue Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php

        global $db;

        include 'config_bd.php';  // Connexion à la base de données
        // Requête pour récupérer les factures
        $stmt = $db->query("SELECT f.id_facture, c.client_name, p.projetname, f.montant, f.date_emission, f.status
                                FROM factures f
                                INNER JOIN projets p ON f.id_projet = p.id_projet
                                INNER JOIN clients c ON p.id_clients = c.id_clients");

        $factures = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($factures as $facture) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($facture['id_facture']) . "</td>";
            echo "<td>" . htmlspecialchars($facture['client_name']) . "</td>";
            echo "<td>" . htmlspecialchars($facture['projetname']) . "</td>";
            echo "<td>" . htmlspecialchars($facture['montant']) . " €</td>";
            echo "<td>" . htmlspecialchars($facture['date_emission']) . "</td>";
            echo "<td>" . htmlspecialchars($facture['status']) . "</td>";
            echo "<td>
                        <a href='delete_facture.php?id=" . htmlspecialchars($facture['id_facture']) . "' class='btn btn-sm btn-danger'>Delete</a>
                        <a href='show_facture.php?id=" . htmlspecialchars($facture['id_facture']) . "' class='btn btn-sm btn-success'>Show</a>

                      </td>";
            echo "</tr>";
        }
        ?>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert
        <?php echo $_GET['message'] === 'success' ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php
                if ($_GET['message'] === 'success') {
                    echo "Invoice successfully deleted.";
                } elseif ($_GET['message'] === 'error') {
                    echo "Une erreur est survenue lors de la suppression de la facture.";
                    if (isset($_GET['error'])) {
                        echo " Détails : " . htmlspecialchars($_GET['error']);
                    }
                } elseif ($_GET['message'] === 'missing_id') {
                    echo "ID de facture non spécifié.";
                }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        </tbody>
    </table>
</div>
            <script src="../assets/vendor/libs/jquery/jquery.js"></script>
            <script src="../assets/vendor/libs/popper/popper.js"></script>
            <script src="../assets/vendor/js/bootstrap.js"></script>
            <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
            <script src="../assets/vendor/js/menu.js"></script>
            <script src="../assets/js/main.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#facturesTable').DataTable();  // Activation de DataTables
    });
</script>


</body>

</html>
