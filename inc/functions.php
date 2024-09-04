<?php
// Funktion zum Abrufen der verfügbaren Stellen aus der Datenbank
function getStellen()
{
    global $db;
    $sql = "SELECT id, bezeichnung FROM stellen";
    $result = $db->query($sql);
    $stellen = array();
    while ($row = $result->fetch_assoc()) {
        $stellen[] = $row;
    }
    return $stellen;
}

// Funktion zum Aktualisieren der Bezeichnung einer Stelle
function updateStelle($stelleId, $bezeichnung)
{
    global $db;
    $stelleId = $db->real_escape_string($stelleId);
    $bezeichnung = $db->real_escape_string($bezeichnung);

    $sql = "UPDATE stellen SET bezeichnung = '$bezeichnung' WHERE id = $stelleId";
    if ($db->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Funktion zum Löschen einer Stelle
function deleteStelle($stelleId)
{
    global $db;
    $stelleId = $db->real_escape_string($stelleId);

    $sql = "DELETE FROM stellen WHERE id = $stelleId";
    if ($db->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Funktion zum Hinzufügen einer neuen Stelle
function addStelle($bezeichnung)
{
    global $db;
    $bezeichnung = $db->real_escape_string($bezeichnung);

    $sql = "INSERT INTO stellen (bezeichnung) VALUES ('$bezeichnung')";
    if ($db->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}
?>
