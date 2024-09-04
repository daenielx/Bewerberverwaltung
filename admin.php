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

// Include the header
require_once('inc/header.php');
require_once('inc/footer.php');



function getDistinctStellen()
{
    global $db;
    $sql = "SELECT DISTINCT stelle FROM kontaktformular";
    $result = $db->query($sql);
    $stellen = array();

    while ($row = $result->fetch_assoc()) {
        $stellen[] = $row['stelle'];
    }

    return $stellen;
}



?>
<!DOCTYPE html>
<html>

<head>
    <title>Bewerberverwaltung | obis | Concept</title>
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
    <div class="container">
        <h1 class="mt-4">Bewerberliste</h1>
        <?php

        // Funktion zum Abrufen der Anzahl der Bewerbungen im angegebenen Status
        function getApplicationCount($status)
        {
            global $db;
            $sql = "SELECT COUNT(*) as count FROM kontaktformular WHERE status = '$status'";
            $result = $db->query($sql);
            $row = $result->fetch_assoc();
            return $row['count'];
        }

        $eingegangenCount = getApplicationCount('Neu');
        $inBearbeitungCount = getApplicationCount('In Bearbeitung');
        $abgeschlossenCount = getApplicationCount('Abgeschlossen');

        // Funktion zum Abrufen der Gesamtanzahl der Bewerbungen
        function getTotalApplicationCount()
        {
            global $db;
            $sql = "SELECT COUNT(*) as count FROM kontaktformular";
            $result = $db->query($sql);
            $row = $result->fetch_assoc();
            return $row['count'];
        }

        $gesamtCount = getTotalApplicationCount();
        ?>  

<div class="row">
            <div class="col-md-12">
                <div class="card text-white text-center bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Gesamtanzahl Bewerber</h5>
                        <p class="card-text">Anzahl: <?php echo $gesamtCount; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Eingegangen</h5>
                        <p class="card-text">Anzahl: <?php echo $eingegangenCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">In Bearbeitung</h5>
                        <p class="card-text">Anzahl: <?php echo $inBearbeitungCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Abgeschlossen</h5>
                        <p class="card-text">Anzahl: <?php echo $abgeschlossenCount; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="text-right mb-3">
        <a href="functions/export_csv.php" class="btn btn-primary">
    <i class="fas fa-file-csv"></i> CSV EXPORT
</a>

<a href="functions/export_pdf.php" class="btn btn-primary">
    <i class="fas fa-file-pdf"></i> PDF EXPORT
</a>
<button class="btn btn-danger" onclick="deleteSelected()">
    <i class="fas fa-trash-alt"></i> Ausgewählte löschen
</button>

</div>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="text-left mb-3">
                <label for="statusFilter">Filter Status:</label>
                <select id="statusFilter" class="form-control">
                    <option value="all">Alle</option>
                    <option value="Neu">Neu</option>
                    <option value="In Bearbeitung">In Bearbeitung</option>
                    <option value="Abgeschlossen">Abgeschlossen</option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-left mb-3">
                <label for="stelleFilter">Filter Stelle:</label>
                <select id="stelleFilter" class="form-control">
                    <option value="all">Alle</option>
                    <?php
                    // Dynamisch Stellen aus der Datenbank laden
                    $stellen = getDistinctStellen();

                    foreach ($stellen as $stelle) {
                        echo "<option value='" . $stelle . "'>" . $stelle . "</option>";
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>
        <table class="table table-striped mt-4">
            <thead class="thead-primary">
                <tr>
                    <th><input type="checkbox" id="selectAllCheckbox"></th>
                    <th>ID</th>
                    <th>Vorname</th>
                    <th>Nachname</th>
                    <th>E-Mail</th>
                    <th>Telefon</th>
                    <th>Stelle</th>
                    <th>Nachricht</th>
                    <th>Status</th>
                    <th>Dateien</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Verbindung zur Datenbank herstellen (wie zuvor)
                

                if (isset($_GET['delete'])) {
                    $idToDelete = $_GET['delete'];
                
                    // Abrufen des Dateipfads aus der Datenbank
                    $getFilepathQuery = "SELECT dateipfad FROM kontaktformular WHERE id = $idToDelete";
                    $result = $db->query($getFilepathQuery);
                
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $filepath = $row['dateipfad'];
                
                        // Löschen der Dateien auf dem Server
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        } else {
                            echo '<script>$(document).ready(function() {
                                $(".toast").toast("show");
                                $("#toastMessage").text("Datei nicht gefunden: ' . $filepath . '");
                            });</script>';
                        }
                
                        // Löschen des Eintrags aus der Datenbank
                        $deleteSql = "DELETE FROM kontaktformular WHERE id = $idToDelete";
                        if ($db->query($deleteSql) === TRUE) {
                            echo '<script>$(document).ready(function() {
                                $(".toast").toast("show");
                                $("#toastMessage").text("Eintrag und zugehörige Dateien erfolgreich gelöscht.");
                                // Weiterleitung zur aktuellen Seite, ohne ?delete=
                                window.location.href = window.location.pathname;
                            });</script>';
                        } else {
                            echo '<script>$(document).ready(function() {
                                $(".toast").toast("show");
                                $("#toastMessage").text("Fehler beim Löschen des Eintrags: ' . $db->error . '");
                            });</script>';
                        }
                    } else {
                        echo '<script>$(document).ready(function() {
                            $(".toast").toast("show");
                            $("#toastMessage").text("Eintrag nicht gefunden: ' . $idToDelete . '");
                        });</script>';
                    }
                }

// Aktuelle Seite, standardmäßig 1
$currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

// Anzahl der Einträge pro Seite
$entriesPerPage = 10;

// Berechne den Offset für die SQL-Abfrage
$offset = ($currentPage - 1) * $entriesPerPage;

// SQL-Abfrage, um Einträge für die aktuelle Seite abzurufen
$sql = "SELECT * FROM kontaktformular ORDER BY id DESC LIMIT $offset, $entriesPerPage";
$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selectedEntries[]' value='" . $row['id'] . "'></td>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['vorname'] . "</td>";
                        echo "<td>" . $row['nachname'] . "</td>";
                        echo "<td><a href='mailto:" . $row['email'] . "'>" . $row['email'] . "</a></td>";
                        echo "<td><a href='tel:" . $row['telefon'] . "'>" . $row['telefon'] . "</td>";
                        echo "<td>" . $row['stelle'] . "</td>"; // Anzeigen der Stelle
                        echo "<td>";
echo "<button class='btn btn-primary show-message-btn' data-toggle='modal' data-target='#messageModal' data-message='" . htmlentities($row['nachricht'], ENT_QUOTES) . "'>Anzeigen</button>";
echo "</td>";
                        echo "<td>" . getStatusText($row['status']) . "</td>";
                        echo "<td><a href='" . $row['dateipfad'] . "' download><i class='fas fa-download'></i></a></td>";
                        echo "<td>";
                        if ($_SESSION["role"] === "Systemadmin" || $_SESSION["role"] === "Personalmanagement") {
                            echo "<a href='#' data-toggle='modal' data-target='#editModal' onclick='loadEditForm(" . $row['id'] . ", \"" . $row['vorname'] . "\", \"" . $row['nachname'] . "\", \"" . $row['email'] . "\", \"" . $row['telefon'] . "\", \"" . $row['status'] . "\")'><i class='fas fa-edit text-primary'></i></a>";
                            echo "<span>&nbsp;&nbsp;</span>";
                            echo "<a href='#' onclick='confirmDelete(" . $row['id'] . ")'><i class='fas fa-trash-alt text-danger'></i></a>";
                        } else {
                            // Füge hier einen Code hinzu, um die Anzeige für andere Benutzerrollen anzupassen, falls erforderlich.
                        }
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Keine Einträge gefunden.</td></tr>";
                }

                // Verbindung zur Datenbank schließen
                $db->close();

                function getStatusText($status)
                {
                    $statusClass = '';
                    switch ($status) {
                        case 'Neu':
                            $statusClass = 'badge badge-info';
                            break;
                        case 'In Bearbeitung':
                            $statusClass = 'badge badge-warning';
                            break;
                        case 'Abgeschlossen':
                            $statusClass = 'badge badge-success';
                            break;
                        default:
                            $statusClass = 'badge badge-secondary';
                    }
                    return '<span class="' . $statusClass . '">' . $status . '</span>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-center">
        <?php
        // Anzahl der Einträge pro Seite
        $entriesPerPage = 10;

        // Gesamtanzahl der Seiten berechnen
        $totalPages = ceil($gesamtCount / $entriesPerPage);

        // Aktuelle Seite, standardmäßig 1
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;

        // Vorherige Seite
        $prevPage = $currentPage - 1;

        // Nächste Seite
        $nextPage = $currentPage + 1;

        // Zeige vorherige Seite, wenn nicht auf der ersten Seite
        if ($currentPage > 1) {
            echo "<li class='page-item'><a class='page-link' href='?page=$prevPage'>&laquo;</a></li>";
        }

        // Zeige Seitennummern
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<li class='page-item " . ($i == $currentPage ? 'active' : '') . "'><a class='page-link' href='?page=$i'>$i</a></li>";
        }

        // Zeige nächste Seite, wenn nicht auf der letzten Seite
        if ($currentPage < $totalPages) {
            echo "<li class='page-item'><a class='page-link' href='?page=$nextPage'>&raquo;</a></li>";
        }
        ?>
    </ul>
</nav>

    <!-- Modal für Nachrichten -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Nachricht anzeigen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="messageContent"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Schließen</button>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript-Code, um den Nachrichteninhalt im Modal anzuzeigen
    $(document).on('click', '.show-message-btn', function () {
        var message = $(this).data('message');
        $('#messageContent').text(message);
    });
</script>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Eintrag bearbeiten</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="form-group">
                            <label for="editVorname">Vorname</label>
                            <input type="text" class="form-control" id="editVorname" name="editVorname">
                        </div>
                        <div class="form-group">
                            <label for="editNachname">Nachname</label>
                            <input type="text" class="form-control" id="editNachname" name="editNachname">
                        </div>
                        <div class="form-group">
                            <label for="editEmail">E-Mail</label>
                            <input type="email" class="form-control" id="editEmail" name="editEmail">
                        </div>
                        <div class="form-group">
                            <label for="editTelefon">Telefonnummer</label>
                            <input type="text" class="form-control" id="editTelefon" name="editTelefon">
                        </div>
                        <div class="form-group">
                            <label for="editStatus">Status</label>
                            <select class="form-control" id="editStatus" name="editStatus">
                                <option value="Neu">Neu</option>
                                <option value="In Bearbeitung">In Bearbeitung</option>
                                <option value="Abgeschlossen">Abgeschlossen</option>
                            </select>
                        </div>
                        <input type="hidden" id="editID" name="editID">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="saveChanges()">Speichern</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Eintrag löschen</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bist du sicher, dass du diesen Eintrag löschen möchtest?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                <a href="#" id="deleteEntryBtn" class="btn btn-danger">Löschen</a>
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

<!-- Toast-Benachrichtigung -->
<div class="toast" style="position: absolute; bottom: 0; right: 0;">
    <div class="toast-header">
        <strong class="mr-auto">Benachrichtigung</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body" id="toastMessage"></div>
</div>

<script>
    function loadEditForm(id, vorname, nachname, email, telefon, status) {
        document.getElementById('editID').value = id;
        document.getElementById('editVorname').value = vorname;
        document.getElementById('editNachname').value = nachname;
        document.getElementById('editEmail').value = email;
        document.getElementById('editTelefon').value = telefon;
        document.getElementById('editStatus').value = status;
    }

    function saveChanges() {
        var id = document.getElementById('editID').value;
        var vorname = document.getElementById('editVorname').value;
        var nachname = document.getElementById('editNachname').value;
        var email = document.getElementById('editEmail').value;
        var telefon = document.getElementById('editTelefon').value;
        var status = document.getElementById('editStatus').value;

        // Führen Sie eine Ajax-Anfrage durch, um die Daten zu speichern
        $.ajax({
            type: "POST",
            url: "functions/update_entry.php",
            data: {
                id: id,
                vorname: vorname,
                nachname: nachname,
                email: email,
                telefon: telefon,
                status: status
            },
            success: function (response) {
                if (response === 'success') {
                    $("#toastMessage").text("Änderungen erfolgreich gespeichert.");
                    $(".toast").toast("show");
                    location.reload();
                } else {
                    $("#toastMessage").text("Fehler beim Speichern der Änderungen: " + response);
                    $(".toast").toast("show");
                }
            }
        });
    }
</script>
<script>
    $(document).ready(function () {
        // Filterfunktion für Status und Stelle
        $("#statusFilter, #stelleFilter").change(function () {
            filterTable();
        });

        function filterTable() {
            var selectedStatus = $("#statusFilter").val();
            var selectedStelle = $("#stelleFilter").val();

            // Zeige alle Zeilen
            $("tbody tr").show();

            // Filtern nach Status
            if (selectedStatus !== "all") {
                $("tbody tr").each(function () {
                    var statusCell = $(this).find("td:nth-child(9)"); // Index für die Status-Zelle anpassen
                    var statusText = statusCell.text();
                    if (statusText !== selectedStatus) {
                        $(this).hide();
                    }
                });
            }

            // Filtern nach Stelle
            if (selectedStelle !== "all") {
                $("tbody tr").each(function () {
                    var stelleCell = $(this).find("td:nth-child(7)"); // Index für die Stellen-Zelle anpassen
                    var stelleText = stelleCell.text();
                    if (stelleText !== selectedStelle) {
                        $(this).hide();
                    }
                });
            }
        }
    });
</script>
<script>
    function confirmDelete(id) {
        $('#deleteEntryBtn').attr('href', '?delete=' + id);
        $('#deleteModal').modal('show');
    }
</script>
<script>
        // JavaScript-Code, um markierte Einträge zu löschen
        function deleteSelected() {
            var selectedEntries = [];
            // Durchlaufe alle markierten Checkboxen
            $("input[name='selectedEntries[]']:checked").each(function () {
                selectedEntries.push($(this).val());
            });

            // Überprüfe, ob mindestens eine Checkbox ausgewählt wurde
            if (selectedEntries.length > 0) {
                // Führe eine Ajax-Anfrage durch, um die markierten Einträge zu löschen
                $.ajax({
                    type: "POST",
                    url: "functions/delete_selected.php", // Passe den Pfad zur Datei an, die die Löschung durchführt
                    data: { selectedEntries: selectedEntries },
                    success: function (response) {
                        if (response === 'success') {
                            $("#toastMessage").text("Ausgewählte Einträge erfolgreich gelöscht.");
                            $(".toast").toast("show");
                            location.reload();
                        } else {
                            $("#toastMessage").text("Fehler beim Löschen der Einträge: " + response);
                            $(".toast").toast("show");
                        }
                    }
                });
            } else {
                alert("Bitte wählen Sie mindestens einen Eintrag zum Löschen aus.");
            }
        }
    </script>
    <script>
    // JavaScript-Code, um alle Einträge auszuwählen
    $(document).ready(function () {
        $("#selectAllCheckbox").change(function () {
            $("input[name='selectedEntries[]']").prop('checked', $(this).prop('checked'));
        });

        $("input[name='selectedEntries[]']").change(function () {
            if ($("input[name='selectedEntries[]']:checked").length === $("input[name='selectedEntries[]']").length) {
                $("#selectAllCheckbox").prop('checked', true);
            } else {
                $("#selectAllCheckbox").prop('checked', false);
            }
        });
    });
</script>

</html>