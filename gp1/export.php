<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'config_bd.php';

if (isset($_GET['export']) && $_GET['export'] == 'excel') {
    $project_filter = isset($_GET['project']) ? $_GET['project'] : '';
    $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
    $consultant_filter = isset($_GET['consultant']) ? $_GET['consultant'] : '';
    $month_filter = isset($_GET['month']) ? $_GET['month'] : '';
    $year_filter = isset($_GET['year']) ? $_GET['year'] : '';

    $sql = "SELECT taches.*, projets.projetname, users.firstname, users.lastname 
            FROM taches 
            INNER JOIN projets ON taches.id_projet = projets.id_projet 
            INNER JOIN users ON taches.user_id = users.user_id
            WHERE 1=1";

    $params = [];

    if ($project_filter) {
        $sql .= " AND projets.projetname LIKE :project";
        $params[':project'] = "%$project_filter%";
    }

    if ($status_filter) {
        $sql .= " AND taches.status = :status";
        $params[':status'] = $status_filter;
    }

    if ($consultant_filter) {
        $sql .= " AND users.user_id = :consultant";
        $params[':consultant'] = $consultant_filter;
    }

    if ($month_filter) {
        $sql .= " AND MONTH(taches.date) = :month";
        $params[':month'] = $month_filter;
    }

    if ($year_filter) {
        $sql .= " AND YEAR(taches.date) = :year";
        $params[':year'] = $year_filter;
    }

    $stmt = $db->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->execute();
    $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Génération du fichier Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="tasks_export.xls"');
    header('Cache-Control: max-age=0');

    echo "<table border='1'>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Project</th>";
    echo "<th>Task Name</th>";
    echo "<th>Consultant</th>";
    echo "<th>Date</th>";
    echo "<th>Status</th>";
    echo "<th>Description</th>";
    echo "</tr>";

    foreach ($taches as $tache) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($tache['id_tache']) . "</td>";
        echo "<td>" . htmlspecialchars($tache['projetname']) . "</td>";
        echo "<td>" . htmlspecialchars($tache['nomtache']) . "</td>";
        echo "<td>" . htmlspecialchars($tache['firstname'] . ' ' . $tache['lastname']) . "</td>";
        echo "<td>" . htmlspecialchars($tache['date']) . "</td>";
        echo "<td>" . htmlspecialchars(ucfirst($tache['status'])) . "</td>";
        echo "<td>" . htmlspecialchars($tache['descriptiontache']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    exit;
}
?>
