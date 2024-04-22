<?php
session_start();
$ch_login = curl_init();
$url_login = 'http://192.168.1.187/projects/ClassevivaComms/Fat3/login';
curl_setopt($ch_login, CURLOPT_URL, $url_login);
curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('username' => 'fioccosplat@gmail.com', 'password' => 'Fiocco2018')));
curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
$response_login = curl_exec($ch_login);
$loginData = json_decode($response_login, true);
echo $response_login;

echo $_SESSION['token'] = $loginData["token"];
echo "\n";
echo $_SESSION['id'] = filter_var($loginData["ident"], FILTER_SANITIZE_NUMBER_INT);

$ch_comms = curl_init();
$url_comms = 'http://192.168.1.187/projects/ClassevivaComms/Fat3/grades';
curl_setopt($ch_comms, CURLOPT_URL, $url_comms);
curl_setopt($ch_comms, CURLOPT_POSTFIELDS, http_build_query(array('id'=> $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_comms, CURLOPT_RETURNTRANSFER, true);
$response_comms = curl_exec($ch_comms);
$commsData = json_decode($response_comms, true);
echo $response_comms;
?>
