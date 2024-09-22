<?php
global $db;
include 'config_bd.php';

if (isset($_POST['id_projet'])) {
    $id_projet = $_POST['id_projet'];

    // Récupérer les tâches associées au projet sélectionné
    $stmt = $db->prepare("SELECT * FROM taches WHERE id_projet = ?");
    $stmt->execute([$id_projet]);
    $taches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($taches) {
        foreach ($taches as $tache) {
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="taches[]" value="' . htmlspecialchars($tache['id_tache']) . '" id="tache_' . htmlspecialchars($tache['id_tache']) . '">';
            echo '<label class="form-check-label" for="tache_' . htmlspecialchars($tache['id_tache']) . '">';
            echo htmlspecialchars($tache['nomtache']) . ' - ' . htmlspecialchars($tache['descriptiontache']);
            echo '</label>';
            echo '</div>';
        }
    } else {
        echo '<p>Aucune tâche disponible pour ce projet.</p>';
    }
}
