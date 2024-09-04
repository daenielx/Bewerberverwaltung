<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["newUsername"]) && isset($_POST["newPassword"]) && isset($_POST["newRole"])) {
        $newUsername = $_POST["newUsername"];
        $newPassword = $_POST["newPassword"];
        $newRole = $_POST["newRole"];
        
        // Verbindung zur Datenbank herstellen
        $db = new mysqli('SERVER_ADRESSE', 'BENUTZERNAME', 'PASSWORT', 'bewerber');
        
        // Überprüfen, ob die Verbindung erfolgreich war
        if ($db->connect_error) {
            die("Verbindung zur Datenbank fehlgeschlagen: " . $db->connect_error);
        }

        // Sichere das Passwort (idealerweise Hash und Salt verwenden)
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // SQL-Abfrage zum Einfügen eines neuen Benutzers in die Datenbank
        $sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sss", $newUsername, $hashedPassword, $newRole);

        if ($stmt->execute()) {
            header("location: ../benutzerverwaltung.php");
        } else {
            echo "Fehler beim Hinzufügen des Benutzers: " . $stmt->error;
        }
    } else {
        echo "Ungültige Anfrage.";
    }
} else {
    echo "Ungültige Anfrage.";
}
?>
