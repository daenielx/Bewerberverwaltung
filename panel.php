<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

// Include the configuration file
require_once('inc/config.php');

// Include the header and the footer
require_once('inc/header.php');
require_once('inc/footer.php');

// Prüfen, ob die Rolle des Benutzers "User" ist
if ($_SESSION["role"] === "User") {
    header("location: admin.php"); // Benutzer mit der Rolle "User" werden zur admin.php weitergeleitet
    exit;
} elseif ($_SESSION["role"] === "Systemadmin" || $_SESSION["role"] === "Personalmanagement") {
    // Wenn die Rolle "Systemadmin" oder "Personalmanagement" ist, bleibt der Benutzer auf der aktuellen Seite
} else {
    // Falls die Rolle unbekannt ist oder andere Aktionen erforderlich sind, kannst du hier entsprechende Maßnahmen ergreifen.
}

// Aktuellen Titel abrufen
$currentTitle = getPageTitle();

// Update the page title if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["newTitle"])) {
    $newTitle = $_POST["newTitle"];

    // Update the title in the database
    $updateTitleQuery = "UPDATE admin_settings SET title = '$newTitle' WHERE id = 1";
    $db->query($updateTitleQuery);


    // Aktuellen Titel aktualisieren
    $currentTitle = $newTitle;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title><?= $currentTitle; ?> | obis | Concept</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/lumen/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>

<body>

    <div class="container">
        <h1 class="mt-4">Benutzerverwaltung</h1>
        <!-- Form zum Aktualisieren des Seiten-Titels -->
        <div class="container mt-4">
            <form method="post" action="">
                <div class="form-group">
                    <label for="newTitle">Seitentitel</label>
                    <input type="text" class="form-control" name="newTitle" id="newTitle" required placeholder="<?= $currentTitle; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Aktualisieren</button>
            </form>
        </div>
        
        <!-- Weitere Funktionen hier hinzufügen -->

    </div>
    <style>
        .footer {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        } 
    </style>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</html>
