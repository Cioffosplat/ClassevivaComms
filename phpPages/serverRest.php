<?php

require_once 'phpClass/Classeviva.php';

use Papaya\Classeviva\Students\Classeviva;

function handleLoginRequest() {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        http_response_code(400);
        echo 'Username e password sono obbligatori';
        return;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $classeviva = new Classeviva();
        $responseLogin = $classeviva ->login($username,$password);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo $responseLogin;
    } catch (Exception $e) {
        $responseLogin = ['error' => $e->getMessage()];
        header('Content-Type: application/json');
        http_response_code(500);
        header('Access-Control-Allow-Origin: *');
        echo $responseLogin;
    }
}

function handleStatusRequest() {
    $id = $_POST['id'];
    $token = $_POST['token'];
    try {
        $classeviva = new Classeviva();
        $status = $classeviva->status($id,$token);
        $status = json_decode($status, true);
        header('Content-Type: application/json');
        header('Z-Dev-Apikey: Tg1NWEwNGIgIC0K');
        header('Z-Auth-Token: ' . $token);
        echo $status;
    } catch (Exception $e) {
        $response = ['error' => $e->getMessage()];
        header('Content-Type: application/json');
        http_response_code(500);
        header('Access-Control-Allow-Origin: *');
        echo $response;
    }
}

function handleGradesRequest() {
    $id = $_POST['id'];
    $token = $_POST['token'];
    try {
        $classeviva = new Classeviva();
        $grades = $classeviva->grades($id,$token);
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        echo $grades;
    } catch (Exception $e) {
        $response = ['error' => $e->getMessage()];
        header('Content-Type: application/json');
        http_response_code(500);
        header('Access-Control-Allow-Origin: *');
        echo $response;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_GET['action'])) {
        if ($_GET['action'] === 'login') {
            handleLoginRequest();
        } elseif ($_GET['action'] === 'grades') {
            handleGradesRequest();
        } elseif ($_GET['action'] === 'status') {
            handleStatusRequest();
        } else {
            http_response_code(405);
            echo 'Action Not Allowed';
        }
    } else {
        http_response_code(400);
        echo 'Action is missing';
    }
} else {
    http_response_code(405);
    echo 'Method Not Allowed';
}
?>
