<?php
// Include the configuration file
require_once('../inc/config.php');

// Überprüfe, ob POST-Daten vorhanden sind
if(isset($_POST['selectedEntries']) && is_array($_POST['selectedEntries'])) {
    $selectedEntries = $_POST['selectedEntries'];

    // Überprüfe, ob die ausgewählten Einträge nicht leer sind
    if(!empty($selectedEntries)) {
        // Wandle die Werte in ein kommasepariertes String um, um sie in der SQL-Abfrage zu verwenden
        $selectedIds = implode(',', $selectedEntries);

        // SQL-Abfrage zum Löschen der markierten Einträge
        $deleteSql = "DELETE FROM kontaktformular WHERE id IN ($selectedIds)";

        // Führe die SQL-Abfrage aus
        if ($db->query($deleteSql) === TRUE) {
            echo 'success';
        } else {
            echo 'Fehler beim Löschen der Einträge: ' . $db->error;
        }
    } else {
        echo 'Keine ausgewählten Einträge zum Löschen.';
    }
} else {
    echo 'Ungültige Anfrage.';
}

// Schließe die Verbindung zur Datenbank
$db->close();
?>
