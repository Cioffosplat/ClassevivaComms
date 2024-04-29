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

$f3->route('GET /notice',function () use ($pdo) {
    header('Content-Type: application/json');
    $result = $pdo->query("SELECT * FROM noticeboard");
    echo json_encode($result->fetchAll());
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
