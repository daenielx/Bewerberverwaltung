<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Verbindung zur Datenbank herstellen
    $db = new mysqli('SERVER_ADRESSE', 'BENUTZERNAME', 'PASSWORT', 'bewerber');

    // Überprüfen, ob die Verbindung erfolgreich war
    if ($db->connect_error) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . $db->connect_error);
    }

    // Benutzereingaben aus dem Formular erhalten
    $user_id = $_POST["id"];
    $newUsername = $_POST["newUsername"];
    $newRole = $_POST["newRole"];

    // SQL-Abfrage, um die Benutzerdaten zu aktualisieren
    $sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die("Vorbereitung der SQL-Abfrage fehlgeschlagen: " . $db->error);
    }

    $stmt->bind_param("ssi", $newUsername, $newRole, $user_id);

    if ($stmt->execute()) {
        // Erfolgreich aktualisiert, leite zur Benutzerverwaltung zurück oder zu einer anderen geeigneten Seite weiter
    
        // Überprüfe, ob die Aktualisierung für den angemeldeten Benutzer erfolgt
        if ($user_id == $_SESSION["id"]) {
            // Aktualisiere auch die Session-Daten, wenn der angemeldete Benutzer betroffen ist
            $_SESSION["username"] = $newUsername;
            $_SESSION["role"] = $newRole;
        }
    
        header("location: ../benutzerverwaltung.php");
    } else {
        die("Aktualisieren des Benutzers fehlgeschlagen: " . $db->error);
    }

    $stmt->close();
    $db->close();
} else {
    // Wenn die Anfrage nicht korrekt ist, zeige eine Fehlermeldung oder handle sie anders
    echo "Ungültige Anfrage.";
}
?>
