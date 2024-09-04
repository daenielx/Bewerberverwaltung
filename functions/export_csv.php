<?php
// Verbindung zur Datenbank herstellen (wie zuvor)
$db = new mysqli('SERVER_ADRESSE', 'BENUTZERNAME', 'PASSWORT', 'bewerber');

if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// CSV-Datei vorbereiten und Header setzen
$filename = "Bewerber_" . date("Y-m-d") . ".csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen("php://output", "w");

// CSV-Header schreiben
fputcsv($output, array('ID', 'Vorname', 'Nachname', 'E-Mail', 'Telefon', 'Status'));

// SQL-Abfrage, um alle Einträge aus der Datenbank zu selektieren
$sql = "SELECT * FROM kontaktformular";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = getStatusText($row['status']);
        fputcsv($output, array($row['id'], $row['vorname'], $row['nachname'], $row['email'], $row['telefon'],));
    }
}

// Verbindung zur Datenbank schließen
$db->close();

function getStatusText($status) {
    switch ($status) {
        case 'Neu':
            return 'Neu';
        case 'In Bearbeitung':
            return 'In Bearbeitung';
        case 'Abgeschlossen':
            return 'Abgeschlossen';
        default:
            return 'Unbekannt';
    }
}

fclose($output);
?>