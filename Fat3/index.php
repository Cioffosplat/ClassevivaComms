<?php

require_once 'vendor/autoload.php';
require_once 'Classeviva.php';

use Papaya\Classeviva\Students\Classeviva;

$host = 'localhost';
$dbname = 'classevivacomms';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Errore di connessione al database: " . $e->getMessage();
}

$f3 = \Base::instance();

$f3->route('GET /notice', function () use ($pdo) {
    header('Content-Type: application/json');
    $result = $pdo->query("SELECT * FROM noticeboard");
    echo json_encode($result->fetchAll());
});

$f3->route('POST /update-profile-pic', function ($f3) use ($pdo) {
    header("Access-Control-Allow-Origin: *");
    $userId = $_SESSION['id'];
    if (isset($_FILES['profile_pic'])) {
        $tempFilePath = $_FILES['profile_pic']['tmp_name'];
        $imageData = file_get_contents($tempFilePath);

        $query = "UPDATE users SET user_pic = :user_pic WHERE id = :user_id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':user_pic', $imageData, PDO::PARAM_LOB);
        $statement->bindParam(':user_id', $userId);

        if ($statement->execute()) {
            // Invia una risposta JSON con stato 200 (OK)
            $f3->status(200);
            echo json_encode(array("message" => "Foto profilo aggiornata con successo"));
        } else {
            $f3->status(500);
            echo json_encode(array("message" => "Si è verificato un errore durante l'aggiornamento dell'immagine del profilo"));
        }
    } else {
        $f3->status(400);
        echo json_encode(array("message" => "Nessuna immagine caricata"));
    }
});


$f3->route('POST /login', function($f3) use ($pdo) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $classeviva = new Classeviva();
        $responseLogin = $classeviva->login($username, $password);
        $loginData = json_decode($responseLogin, true);
        $ident = $loginData['ident'];
        $id = filter_var($ident, FILTER_SANITIZE_NUMBER_INT);
        $token = $loginData['token'];
        $name = $loginData['firstName'];
        $surname = $loginData['lastName'];
        $responseCard = $classeviva->card($id,$token);
        $cardData = json_decode($responseCard, true);
        $birthDate = $cardData['card']["birthDate"];
        $f3->status(200);
        echo $responseLogin;
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }

    try {
        $stmt = $pdo->prepare("INSERT IGNORE INTO users (id, name, surname, birth_date) VALUES (:id, :name, :surname, :birthDate)");
        $stmt->bindParam(':id', $ident);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':surname', $surname);
        $stmt->bindParam(':birthDate', $birthDate);
        $stmt->execute();
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

$f3->route('POST /card', function($f3) {
    $id = $_POST['id'];
    $token = $_POST['token'];

    try {
        $classeviva = new Classeviva();
        $status = $classeviva->card($id,$token);
        echo $status;
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

$f3->route('POST /grades', function($f3) {
    $id = $_POST['id'];
    $token = $_POST['token'];

    try {
        $classeviva = new Classeviva();
        $grades = $classeviva->grades($id, $token);
        echo $grades;
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

$f3->route('POST /noticeboard', function($f3) {
    $id = $_POST['id'];
    $token = $_POST['token'];

    try {
        $classeviva = new Classeviva();
        $grades = $classeviva->noticeBoard($id, $token);
        echo $grades;
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

$f3->run();
?>
