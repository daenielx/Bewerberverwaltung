<?php
$db = new mysqli('SERVER_ADRESSE', 'BENUTZERNAME', 'PASSWORT', 'bewerber');
if ($db->connect_error) {
    die('Verbindung fehlgeschlagen: ' . $db->connect_error);
}
function getPageTitle() {
    global $db;
    $sql = "SELECT title FROM admin_settings WHERE setting_name = 'name'";
    $result = $db->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['title'];
    }

    return "Bewerberverwaltung"; // Standardwert, falls nichts in der Datenbank gefunden wurde
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?php echo getPageTitle(); ?> | obis | Concept</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/lumen/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
        body {
            padding-top: 70px; /* Ändere dies entsprechend der Höhe deines Navbar */
            padding-bottom: 70px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="admin.php">
                <i class="fas fa-tachometer-alt"></i> <?php echo getPageTitle(); ?>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Herzlich Willkommen, <?php echo htmlspecialchars($_SESSION["username"]); ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
    <?php if ($_SESSION["role"] !== "User"): ?>
        <a class="dropdown-item" href="benutzerverwaltung.php">
            <i class="fas fa-users-cog"></i> Benutzerverwaltung
        </a>
        <a class="dropdown-item" href="stellenverwaltung.php">
        <i class="fas fa-briefcase"></i> Stellenverwaltung
    </a>
    <?php endif; ?>
    <a class="dropdown-item" href="logout.php">
        <i class="fas fa-sign-out-alt"></i> Abmelden
    </a>
</div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    </body>

</html>