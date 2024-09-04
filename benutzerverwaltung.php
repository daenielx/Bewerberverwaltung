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

// Funktion zum Abrufen der Benutzer aus der Datenbank
function getUsers()
{
    global $db;
    $sql = "SELECT id, username, role FROM users";
    $result = $db->query($sql);
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

$users = getUsers();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Bewerberverwaltung | obis | Concept</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://bootswatch.com/4/lumen/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

</head>

<body>

    <div class="container">
        <h1 class="mt-4">Benutzerverwaltung</h1>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped mt-4">
                    <thead class="thead-primary">
                        <tr>
                            <th>Benutzername</th>
                            <th>Rolle</th>
                            <th>Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user) : ?>
                            <tr>
                                <td><?= $user['username']; ?></td>
                                <td>
                                    <?php
                                    $badgeClass = '';
                                    switch ($user['role']) {
                                        case 'Systemadmin':
                                            $badgeClass = 'badge badge-success';
                                            break;
                                        case 'Personalmanagement':
                                            $badgeClass = 'badge badge-danger';
                                            break;
                                        case 'User':
                                            $badgeClass = 'badge badge-primary';
                                            break;
                                    }
                                    ?>
                                    <span class="<?= $badgeClass ?>"><?= $user['role']; ?></span>
                                </td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#editUserModal<?= $user['id']; ?>" class="btn btn-primary">
                                        <i class="fas fa-edit"></i> Bearbeiten
                                    </a>
                                    <a href="#" data-toggle="modal" data-target="#confirmDeleteModal<?= $user['id']; ?>" class="btn btn-danger">
                                        <i class="fas fa-trash-alt"></i> Löschen
                                    </a>
                                </td>
                            </tr>

                            <!-- Löschen-Bestätigungsmodal -->
                            <div class="modal fade" id="confirmDeleteModal<?= $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteModalLabel">Benutzer löschen</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Möchten Sie den Benutzer <?= $user['username']; ?> wirklich löschen?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="functions/delete_user.php?id=<?= $user['id']; ?>" class="btn btn-danger">Ja, löschen</a>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bearbeiten Modal -->
                            <div class="modal fade" id="editUserModal<?= $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editUserModalLabel">Benutzer bearbeiten</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="post" action="functions/update_user.php">
                                                <input type="hidden" name="id" value="<?= $user['id']; ?>">
                                                <div class="form-group">
                                                    <label for="newUsername">Neuer Benutzername</label>
                                                    <input type="text" class="form-control" id="newUsername" name="newUsername" value="<?= $user['username']; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="newRole">Neue Rolle</label>
                                                    <select class="form-control" id="newRole" name="newRole">
                                                        <option value="Systemadmin" <?php if ($user['role'] == 'Systemadmin') echo 'selected'; ?>>Systemadmin</option>
                                                        <option value="Personalmanagement" <?php if ($user['role'] == 'Personalmanagement') echo 'selected'; ?>>Personalmanagement</option>
                                                        <option value="User" <?php if ($user['role'] == 'User') echo 'selected'; ?>>User</option>
                                                    </select>
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

    <!-- Button zum Anzeigen des neuen Benutzer-Modals -->
    <div class="container">
        <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#addUserModal">
            <i class="fas fa-plus"></i> Neuen Benutzer anlegen
        </button>
    </div>

<!-- Neuen Benutzer hinzufügen Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Neuen Benutzer anlegen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="functions/add_user.php">
                    <div class="form-group">
                        <label for="newUsername">Benutzername</label>
                        <input type="text" class="form-control" id="newUsername" name="newUsername">
                    </div>
                    <div class="form-group">
                        <label for="newPassword">Passwort</label>
                        <input type="password" class="form-control" id="newPassword" name="newPassword">
                    </div>
                    <div class="form-group">
                        <label for="newRole">Rolle</label>
                        <select class="form-control" id="newRole" name="newRole">
                            <option value="Systemadmin">Systemadmin</option>
                            <option value="Personalmanagement">Personalmanagement</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Hinzufügen</button>
                </form>
            </div>
        </div>
    </div>
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
