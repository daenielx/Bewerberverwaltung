<!DOCTYPE html>
<html>

<head>
    <title>Bewerbungsformular | obis | Concept</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/lumen/bootstrap.min.css">
    <script>
        function validateForm() {
            // Überprüfen, ob mindestens eine Datei ausgewählt wurde
            var fileInput = document.getElementById('dateien');
            if (fileInput.files.length === 0) {
                alert('Bitte wählen Sie mindestens eine Datei aus.');
                return false;
            }

            // Überprüfen, ob die Dateigröße weniger als 3 MB beträgt
            var totalSize = 0;
            for (var i = 0; i < fileInput.files.length; i++) {
                totalSize += fileInput.files[i].size;
            }

            var maxSizeInBytes = 3 * 1024 * 1024; // 3 MB
            if (totalSize > maxSizeInBytes) {
                alert('Die Gesamtgröße der Dateien darf nicht mehr als 3 MB betragen.');
                return false;
            }

            // Überprüfen, ob die Datenschutzbestimmungen akzeptiert wurden
            var datenschutzCheckbox = document.getElementById('datenschutz');
            if (!datenschutzCheckbox.checked) {
                alert('Bitte bestätigen Sie die Datenschutzbestimmungen.');
                return false;
            }

            return true;
        }

        // Funktion, um sicherzustellen, dass nur Zahlen eingegeben werden
        function validatePhoneNumber(evt) {
            var theEvent = evt || window.event;

            // Tasten-Code ermitteln
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);

            // Erlaubte Zeichen (nur Zahlen und das '+'-Zeichen)
            var regex = /[0-9]|\+/;

            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <h1>Bewerbungsformular</h1>
        <form action="form_verarbeitung.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="vorname">Vorname:</label>
                <input type="text" class="form-control" name="vorname" required>
            </div>

            <div class="form-group">
                <label for="nachname">Nachname:</label>
                <input type="text" class="form-control" name="nachname" required>
            </div>

            <div class="form-group">
                <label for="email">E-Mail:</label>
                <input type="email" class="form-control" name="email" required>
            </div>

            <div class="form-group">
                <label for="telefon">Telefonnummer:</label>
                <input type="tel" class="form-control" name="telefon" onkeypress="validatePhoneNumber(event)" required>
            </div>

            <div class="form-group">
                <label for="stelle">Stelle:</label>
                <select class="form-control" name="stelle">
                    <?php
                    // Hier verbindest du dich mit der Datenbank und rufst die Stellen ab
                    $db = new mysqli('SERVER_ADRESSE', 'BENUTZERNAME', 'PASSWORT', 'bewerber');
                    $sql = "SELECT id, bezeichnung FROM stellen";
                    $result = $db->query($sql);

                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['bezeichnung'] . "</option>";
                    }

                    $db->close();
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="nachricht">Nachricht:</label>
                <textarea class="form-control" name="nachricht" required></textarea>
            </div>

            <div class="form-group">
                <label for="dateien" class="form-label mt-4">Unterlagen (docx, pdf):</label>
                <input class="form-control" type="file" id="dateien" name="dateien[]" accept=".docx, .pdf" multiple required>
            </div>

            <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="datenschutz" name="datenschutz" required>
                <label class="form-check-label" for="datenschutz">Ich bestätige die Datenschutzbestimmungen.</label>
            </div>

            <br>
            <button type="submit" class="btn btn-primary">Absenden</button>
        </form>
    </div>
</body>

</html>
