<?php
require('fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(190, 10, 'Bewerberliste', 0, 0, 'C');
        $this->Ln(10);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 10, 'Firmenname', 0, 0, 'C'); // Ersetze 'Firmenname' durch den tatsächlichen Firmennamen
        $this->Ln(20);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Seite ' . $this->PageNo(), 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(15, 10, 'ID', 1);
$pdf->Cell(35, 10, 'Vorname', 1);
$pdf->Cell(35, 10, 'Nachname', 1);
$pdf->Cell(45, 10, 'E-Mail', 1);
$pdf->Cell(30, 10, 'Telefon', 1);
$pdf->Cell(30, 10, 'Status', 1);
$pdf->Ln();

// Verbindung zur Datenbank herstellen (du kannst dies an deine Datenbankanmeldeinformationen anpassen)
$db = new mysqli('SERVER_ADRESSE', 'BENUTZERNAME', 'PASSWORT', 'bewerber');

if ($db->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $db->connect_error);
}

// SQL-Abfrage, um alle Bewerberdaten aus der Datenbank zu selektieren
$sql = "SELECT * FROM kontaktformular";
$result = $db->query($sql);

while ($row = $result->fetch_assoc()) {
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(15, 10, $row['id'], 1);
    $pdf->Cell(35, 10, $row['vorname'], 1);
    $pdf->Cell(35, 10, $row['nachname'], 1);
    $pdf->Cell(45, 10, $row['email'], 1);
    $pdf->Cell(30, 10, $row['telefon'], 1);
    $pdf->Cell(30, 10, $row['status'], 1);
    $pdf->Ln();
}

$today = date("Y-m-d");
$filename = "Bewerberliste_" . $today . ".pdf";

// ...
$pdf->Output($filename, 'D'); // Die PDF-Datei wird im Browser angezeigt und kann heruntergeladen werden.

// Verbindung zur Datenbank schließen
$db->close();
