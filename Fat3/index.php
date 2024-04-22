<?php

require_once 'vendor/autoload.php';
require_once 'Classeviva.php';

use Papaya\Classeviva\Students\Classeviva;

$f3 = \Base::instance();

$f3->route('GET /',
    function() {
    echo 'hello world';
    }
);

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

$f3->route('POST /status', function($f3) {
    $token = $_POST['token'];

    try {
        $classeviva = new Classeviva();
        $status = $classeviva->status();
        $status = json_decode($status, true);
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
