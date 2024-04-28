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

$f3->route('POST /login', function($f3) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $classeviva = new Classeviva();
        $responseLogin = $classeviva->login($username, $password);
        $f3->status(200);
        echo $responseLogin;
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
