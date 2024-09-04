<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the configuration file
require_once('../inc/config.php');

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Stelle aktualisieren
    $id = $_POST["id"];
    $newBezeichnung = $_POST["newBezeichnung"];

    $sql = "UPDATE stellen SET bezeichnung = '$newBezeichnung' WHERE id = $id";

    if ($db->query($sql) === TRUE) {
        header("location: ../stellenverwaltung.php");
        exit;
    } else {
        echo "Fehler beim Aktualisieren der Stelle: " . $db->error;
    }

    $db->close();
}
?>
