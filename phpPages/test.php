<?php
function downloadPdfFile($moduleId) {
    // Dettagli della connessione al database
    $servername = 'localhost'; // Sostituisci con il tuo server
    $username = 'root'; // Sostituisci con il tuo username
    $password = ''; // Sostituisci con la tua password
    $dbname = 'classevivacomms'; // Sostituisci con il nome del tuo database

    // Creazione della connessione
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Controllo della connessione
    if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
    }

    // Query per ottenere il file PDF dal database
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

    if ($pdfFile) {
        // Impostazione degli header per il download del file
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $moduleName . '.pdf"');
        header('Content-Length: ' . strlen($pdfFile));

        // Output del contenuto del file PDF
        echo $pdfFile;
    } else {
        echo "File PDF non trovato.";
    }
}

// Chiamata della funzione con l'ID del modulo desiderato
if (isset($_GET['module_id'])) {
    $moduleId = intval($_GET['module_id']);
    downloadPdfFile($moduleId);
} else {
    echo "ID del modulo non fornito.";
}
?>
