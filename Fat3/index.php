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

$f3->route('OPTIONS /*', function () {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    die();
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
        $stmt->bindParam(':id', $id);
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
$f3->route('POST /update-profile-pic', function ($f3) use ($pdo) {
    header('Access-Control-Allow-Origin: *');
    $sessionUserId = $_POST['sessionUserId'];

    if (isset($_FILES['profile_pic']) && isset($sessionUserId)) {
        $tempFilePath = $_FILES['profile_pic']['tmp_name'];

        if(is_uploaded_file($tempFilePath)) {
            $imageData = file_get_contents($tempFilePath);

            $query = "UPDATE users SET user_pic = :user_pic WHERE id = :user_id";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':user_pic', $imageData, PDO::PARAM_LOB);
            $statement->bindParam(':user_id', $sessionUserId);

            if ($statement->execute()) {
                $f3->status(200);
                echo json_encode(array("message" => "Foto profilo aggiornata con successo"));
            } else {
                $f3->status(500);
                echo json_encode(array("message" => "Si è verificato un errore durante l'aggiornamento dell'immagine del profilo"));
            }
        } else {
            $f3->status(500);
            echo json_encode(array("message" => "Errore durante il caricamento dell'immagine"));
        }
    } else {
        $f3->status(400);
        echo json_encode(array("message" => "Nessuna immagine caricata o ID utente mancante"));
    }
});

$f3->route('POST /profile-pic', function ($f3) use ($pdo) {
    header('Access-Control-Allow-Origin: *');

    if (isset($_POST['sessionUserId'])) {
        $userId = $_POST['sessionUserId'];

        $query = "SELECT user_pic FROM users WHERE id = :user_id";
        $statement = $pdo->prepare($query);
        $statement->bindParam(':user_id', $userId);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['user_pic'])) {
            header('Content-Type: image/jpeg');
            echo $result['user_pic'];
            return;
        }
    }

    $defaultImage = file_get_contents('../resources/users/defaultusertheme0.jpg');
    header('Content-Type: image/jpeg');
    echo $defaultImage;
});




$f3->route('POST /save-favorite', function($f3) use ($pdo) {
    header('Access-Control-Allow-Origin: *');
    try {
        $circolareId = $_POST['circolareId'];
        $sessionUserId = $_POST['sessionUserId'];

        $stmt = $pdo->prepare("INSERT IGNORE INTO likes (notice_id, user_id) VALUES (:circolareId, :sessionUserId)");
        $stmt->bindParam(':circolareId', $circolareId);
        $stmt->bindParam(':sessionUserId', $sessionUserId);
        $stmt->execute();
        $f3->status(200);
        echo json_encode(array("message" => "Preferito salvato con successo nel database"));
    } catch (Exception $e) {
        error_log($e->getMessage());
        $f3->status(500);
    }
});

$f3->route('POST /remove-favorite', function($f3) use ($pdo) {
    header('Access-Control-Allow-Origin: *');
    try {
        $circolareId = $_POST['circolareId'];
        $sessionUserId = $_POST['sessionUserId'];

        $stmt = $pdo->prepare("DELETE FROM likes WHERE notice_id = :circolareId AND user_id = :sessionUserId");
        $stmt->bindParam(':circolareId', $circolareId);
        $stmt->bindParam(':sessionUserId', $sessionUserId);
        $stmt->execute();
        $f3->status(200);
        echo json_encode(array("message" => "Favorite removed successfully from the database"));
    } catch (Exception $e) {
        error_log($e->getMessage());
        $f3->status(500);
    }
});


$f3->route('POST /insert-notice', function($f3) use ($pdo) {
    header('Access-Control-Allow-Origin: *');
    try {
        $circolareId = $_POST['pubId'];
        $cntCategory = $_POST['cntCategory'];
        $cntTitle = $_POST['cntTitle'];
        $cntValidFrom = $_POST['cntValidFrom'];

        $stmt = $pdo->prepare("INSERT IGNORE INTO noticeboard (id, name, category, date) VALUES (:pubId, :cntTitle, :cntCategory, :cntValidFrom)");
        $stmt->bindParam(':pubId', $circolareId);
        $stmt->bindParam(':cntCategory', $cntCategory);
        $stmt->bindParam(':cntTitle', $cntTitle);
        $stmt->bindParam(':cntValidFrom', $cntValidFrom);
        $stmt->execute();


        $userId= $_POST['userId'];
        $stmt = $pdo->prepare("INSERT IGNORE INTO has_viewed (notice_id, user_id) VALUES (:circolareId, :userId)");
        $stmt->bindParam(':circolareId', $circolareId);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        $f3->status(200);
        echo json_encode(array("message" => "Dati inseriti con successo nella tabella noticeboard"));
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(array("error" => $e->getMessage()));
    }
});

$f3->route('POST /user-stars', function($f3) use ($pdo) {
    header('Access-Control-Allow-Origin: *');
    $sessionUserId= $_POST['sessionUserId'];

    try {
        $stmt = $pdo->prepare("SELECT nb.id, nb.name, nb.category, nb.date
                               FROM noticeboard nb
                               INNER JOIN likes l ON nb.id = l.notice_id
                               WHERE l.user_id = :sessionUserId");
        $stmt->bindParam(':sessionUserId', $sessionUserId);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $f3->status(200);
        echo json_encode($result);
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});


$f3->run();
?>
