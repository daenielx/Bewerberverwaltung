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

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $user_id = $_GET["id"];

    // SQL-Abfrage, um den Benutzer mit der angegebenen ID zu löschen
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die("Vorbereitung der SQL-Abfrage fehlgeschlagen: " . $db->error);
    }

    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Erfolgreich gelöscht, leite zur Benutzerverwaltung zurück oder zu einer anderen geeigneten Seite weiter
        header("location: ../benutzerverwaltung.php");
    } else {
        die("Löschen des Benutzers fehlgeschlagen: " . $db->error);
    }

    $stmt->close();
    $db->close();
} else {
    // Wenn die Anfrage nicht korrekt ist, zeige eine Fehlermeldung oder handle sie anders
    echo "Ungültige Anfrage.";
}
?>
