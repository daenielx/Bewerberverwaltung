<?php
// Zeige alle Fehler an
error_reporting(E_ALL);
ini_set('display_errors', 1);

$vorname = $_POST['vorname'];
$nachname = $_POST['nachname'];
$email = $_POST['email'];
$telefon = $_POST['telefon'];
$nachricht = $_POST['nachricht'];
$stelle_id = $_POST['stelle'];

// Überprüfen, ob eine Datei hochgeladen wurde
if (!empty($_FILES["dateien"]["name"])) {
    // Datei-Upload-Verarbeitung
    $uploadVerzeichnis = "uploads/";
    $zieldatei = $uploadVerzeichnis . $vorname . "_" . $nachname . ".zip";

    // ZIP-Archiv erstellen
    $zip = new ZipArchive;
    if ($zip->open($zieldatei, ZipArchive::CREATE) === TRUE) {
        // Dateien zum ZIP-Archiv hinzufügen
        foreach ($_FILES["dateien"]["tmp_name"] as $key => $tmp_name) {
            $file_name = $_FILES["dateien"]["name"][$key];
            $zip->addFile($tmp_name, $file_name);
        }
        $zip->close();

        echo "Die Dateien wurden erfolgreich zu einer ZIP-Datei hochgeladen.";
    } else {
        echo "Fehler beim Erstellen des ZIP-Archivs.";
        die();
    }
} else {
    echo "Fehler beim Hochladen der Dateien. Keine Datei ausgewählt.";
    die();
}

// Verbindung zur Datenbank herstellen
$db = new mysqli('SERVER_ADRESSE', 'BENUTZERNAME', 'PASSWORT', 'bewerber');

// Überprüfen, ob die Verbindung erfolgreich war
if ($db->connect_error) {
    die("Verbindung fehlgeschlagen: " . $db->connect_error);
}

// Status auf "Neu" festlegen
$status = "Neu";

// SQL-Abfrage zum Abrufen der Bezeichnung der ausgewählten Stelle basierend auf der Stellen-ID
$sql_stelle = "SELECT bezeichnung FROM stellen WHERE id = ?";
$stmt = $db->prepare($sql_stelle);
$stmt->bind_param('i', $stelle_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $stelle_bezeichnung = $row['bezeichnung'];
} else {
    $stelle_bezeichnung = "Unbekannt"; // Standardwert, falls keine Übereinstimmung gefunden wurde
}

// SQL-Abfrage zum Einfügen der Daten in die Datenbank unter Verwendung der Stellen-Bezeichnung
$sql = "INSERT INTO kontaktformular (vorname, nachname, email, telefon, nachricht, dateipfad, status, stelle) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $db->prepare($sql);
$stmt->bind_param('ssssssss', $vorname, $nachname, $email, $telefon, $nachricht, $zieldatei, $status, $stelle_bezeichnung);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    header("Location: form.php");
    exit();
} else {
    echo "Fehler beim Speichern der Daten: " . $stmt->error;
}

// Verbindung zur Datenbank schließen
$stmt->close();
$db->close();
?>
