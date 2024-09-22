<?php
global $db;
include 'config_bd.php';

if (isset($_POST['id_projet'])) {
    $id_projet = $_POST['id_projet'];

    $tasks_stmt = $db->prepare("SELECT * FROM taches WHERE id_projet = ?");
    $tasks_stmt->execute([$id_projet]);
    $tasks = $tasks_stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($tasks as $task) {
        echo '<div class="task-item">';
        echo '<input type="checkbox" name="taches[]" value="' . $task['id_tache'] . '">';
        echo htmlspecialchars($task['nomtache']);
        echo '</div>';
    }
}
?>
