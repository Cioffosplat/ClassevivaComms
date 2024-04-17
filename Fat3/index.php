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
    $body = json_decode($f3->BODY, true);
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $classeviva = new Classeviva();
        $responseLogin = $classeviva->login($username, $password);
        $f3->status(200);
        $f3->set('HEADERS.Content-Type', 'application/json');
        echo $responseLogin;
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

$f3->route('POST /status', function($f3) {
    $body = json_decode($f3->BODY, true);
    $id = $body['id'];
    $token = $body['token'];

    try {
        $classeviva = new Classeviva();
        $status = $classeviva->status($id, $token);
        $status = json_decode($status, true);
        $f3->set('HEADERS.Content-Type', 'application/json');
        $f3->set('HEADERS.Z-Dev-Apikey', 'Tg1NWEwNGIgIC0K');
        $f3->set('HEADERS.Z-Auth-Token', $token);
        echo $status;
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

$f3->route('POST /grades', function($f3) {
    $body = json_decode($f3->BODY, true);
    $id = $body['id'];
    $token = $body['token'];

    try {
        $classeviva = new Classeviva();
        $grades = $classeviva->grades($id, $token);
        $f3->set('HEADERS.Content-Type', 'application/json');
        echo $grades;
    } catch (Exception $e) {
        $f3->status(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
});

$f3->run();
?>
