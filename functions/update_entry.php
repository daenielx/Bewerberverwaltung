<?php

// Include the configuration file
require_once('../inc/config.php');

// Überprüfen, ob POST-Daten vorhanden sind
if (isset($_POST['id']) && isset($_POST['vorname']) && isset($_POST['nachname']) && isset($_POST['email']) && isset($_POST['telefon']) && isset($_POST['status'])) {
    // POST-Daten abrufen
    $id = $_POST['id'];
    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];
    $email = $_POST['email'];
    $telefon = $_POST['telefon'];
    $status = $_POST['status'];

    // SQL-Abfrage zum Aktualisieren des Eintrags
    $sql = "UPDATE kontaktformular SET vorname = '$vorname', nachname = '$nachname', email = '$email', telefon = '$telefon', status = '$status' WHERE id = $id";

    if ($db->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo $db->error;
    }
} else {
    echo 'error';
}

// Verbindung zur Datenbank schließen
$db->close();