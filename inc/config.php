<?php
// Datenbankverbindungsinformationen
$dbServer = 'SERVER_ADRESSE';  // Bitte ersetzen Sie dies durch Ihre Serveradresse
$dbUsername = 'BENUTZERNAME';  // Bitte ersetzen Sie dies durch Ihren Datenbankbenutzernamen
$dbPassword = 'PASSWORT';      // Bitte ersetzen Sie dies durch Ihr Datenbankpasswort
$dbName = 'DATENBANK_NAME';    // Bitte ersetzen Sie dies durch Ihren Datenbanknamen

// Verbindung zur Datenbank herstellen
$db = new mysqli($dbServer, $dbUsername, $dbPassword, $dbName);

if ($db->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $db->connect_error);  // Fehlerbehandlung bei Verbindungsfehler
}
?>
