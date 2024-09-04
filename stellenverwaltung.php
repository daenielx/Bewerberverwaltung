<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

// Include the configuration file
require_once('inc/config.php');

// Include the header and footer
require_once('inc/header.php');
require_once('inc/footer.php');


// Funktion zum Abrufen der Stellen aus der Datenbank
function getStellen()
{
    global $db;
    $sql = "SELECT id, bezeichnung FROM stellen";
    $result = $db->query($sql);
    $stellen = array();
    while ($row = $result->fetch_assoc()) {
        $stellen[] = $row;
    }
    return $stellen;
}

$stellen = getStellen();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Stellenverwaltung | obis | Concept</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/lumen/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>

<body>

    <!-- Stellenverwaltung -->
    <div class="container">
        <h1 class="mt-4">Stellenverwaltung</h1>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped mt-4">
                    <thead class="thead-primary">
                        <tr>
                            <th>Bezeichnung</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stellen as $stelle) : ?>
                            <tr>
                                <td><?= $stelle['bezeichnung']; ?></td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#editStelleModal<?= $stelle['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Bearbeiten
                                    </a>
                                    <a href="#" data-toggle="modal" data-target="#confirmDeleteStelleModal<?= $stelle['id']; ?>" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Löschen
                                    </a>
                                </td>
                            </tr>

                            <!-- Löschen-Bestätigungsmodal -->
                            <div class="modal fade" id="confirmDeleteStelleModal<?= $stelle['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteStelleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteStelleModalLabel">Stelle löschen</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Möchten Sie die Stelle mit Bezeichnung "<?= $stelle['bezeichnung']; ?>" wirklich löschen?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="functions/delete_stelle.php?id=<?= $stelle['id']; ?>" class="btn btn-danger">Ja, löschen</a>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bearbeiten Modal -->
                            <div class="modal fade" id="editStelleModal<?= $stelle['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editStelleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editStelleModalLabel">Stelle bearbeiten</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="functions/update_stelle.php">
                                                <input type="hidden" name="id" value="<?= $stelle['id']; ?>">
                                                <div class="form-group">
                                                    <label for="newBezeichnung">Neue Bezeichnung</label>
                                                    <input type="text" class="form-control" id="newBezeichnung" name="newBezeichnung" value="<?= $stelle['bezeichnung']; ?>">
                                                </div>
                                                <button type="submit" class="btn btn-primary">Speichern</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Button zum Anzeigen des neuen Stelle-Modals -->
    <div class="container">
        <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#addStelleModal">
            <i class="fas fa-plus"></i> Neue Stelle hinzufügen
        </button>
    </div>

    <!-- Neuen Stelle hinzufügen Modal -->
    <div class="modal fade" id="addStelleModal" tabindex="-1" role="dialog" aria-labelledby="addStelleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStelleModalLabel">Neue Stelle hinzufügen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="functions/add_stelle.php">
                        <div class="form-group">
                            <label for="newBezeichnung">Bezeichnung</label>
                            <input type="text" class="form-control" id="newBezeichnung" name="newBezeichnung">
                        </div>
                        <button type="submit" class="btn btn-primary">Hinzufügen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="navbar navbar-expand-lg navbar-dark bg-primary fixed-bottom">
        <div class="container">
            <span class="text-white">&copy; <?php echo date("Y"); ?> obis | CONCEPT</span>
            <a class="navbar-brand ml-auto text-white" href="https://obis-concept.de" target="_blank">obis | CONCEPT</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>

</html>
