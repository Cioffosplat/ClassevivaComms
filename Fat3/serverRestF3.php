<?php

// Includi il file autoload di Fat-Free Framework
require_once 'vendor/autoload.php';

use Knocks\Classeviva\Students\Classeviva;

// Inizializza l'applicazione Fat-Free
$app = \Base::instance();

// Definisci la rotta per gestire la richiesta di login
$app->route('POST /login', function ($app) {
    $data = json_decode($app->body(), true);

    if (!isset($data['username']) || !isset($data['password'])) {
        $app->error(400, 'Username e password sono obbligatori');
    }

    $username = $data['username'];
    $password = $data['password'];

    try {
        $classeviva = new Classeviva();
        $responseLogin = $classeviva->login($username, $password);

        $app->response->header('Content-Type: application/json');
        $app->response->header('Access-Control-Allow-Origin: *');
        echo $responseLogin;
    } catch (Exception $e) {
        $app->error(500, $e->getMessage());
    }
});

// Definisci la rotta per gestire la richiesta dello stato
$app->route('GET /status', function ($app) {
    try {
        $classeviva = new Classeviva();
        $status = $classeviva->status();
        $status = json_decode($status, true);

        $app->response->header('Content-Type: application/json');
        echo json_encode($status);
    } catch (Exception $e) {
        $app->error(500, $e->getMessage());
    }
});

// Definisci la rotta per gestire la richiesta dei voti
$app->route('GET /grades', function ($app) {
    try {
        $classeviva = new Classeviva();
        $grades = $classeviva->grades();

        $app->response->header('Content-Type: application/json');
        $app->response->header('Access-Control-Allow-Origin: *');
        echo $grades;
    } catch (Exception $e) {
        $app->error(500, $e->getMessage());
    }
});

// Esegui l'applicazione Fat-Free
$app->run();
