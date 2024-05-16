<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="tabIcon" rel="icon" type="image/x-icon" href="../resources/images/logos/originalLogo.jpeg">
    <title>Anteprima e Download PDF</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .pdf-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden; /* Assicurarsi che non ci siano overflow */
        }
        embed {
            width: 100%;
            height: 100%;
            border: none; /* Rimuovere eventuali bordi predefiniti */
        }
    </style>
</head>
<body>

<div class="pdf-container">
    <?php
    function getPreviewPdfFile($moduleId) {
        // Connessione al database e recupero del nome e del file PDF
        $servername = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'classevivacomms';

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connessione fallita: " . $conn->connect_error);
        }

        $sql = "SELECT name, file FROM module WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Errore nella preparazione della query: " . $conn->error);
        }
        $stmt->bind_param("i", $moduleId);
        $stmt->execute();
        $stmt->bind_result($moduleName, $pdfFile);
        $stmt->fetch();
        $stmt->close();
        $conn->close();

        return $pdfFile;
    }

    if (isset($_GET['module_id'])) {
        $moduleId = intval($_GET['module_id']);
        $previewPdf = getPreviewPdfFile($moduleId);
        echo '<embed id="pdf-embed" type="application/pdf">';
        echo '<script>';
        echo 'document.getElementById("pdf-embed").src = "data:application/pdf;base64,'.base64_encode($previewPdf).'";';
        echo '</script>';
    } else {
        echo "Inserisci l'ID del modulo per visualizzare l'anteprima del PDF.";
    }
    ?>
</div>

<script>
    function downloadPdf(moduleId) {
        window.location.href = "download.php?module_id=" + moduleId;
    }

    // Aggiungi un listener per il click sul pulsante di download
    document.addEventListener('DOMContentLoaded', function() {
        var downloadIcons = document.querySelectorAll('.download-icon');
        downloadIcons.forEach(function(icon) {
            icon.addEventListener('click', function(event) {
                event.preventDefault();
                var moduleId = icon.getAttribute('data-id');
                downloadPdf(moduleId);
            });
        });
    });
</script>

</body>
</html>
