<?php
session_start();
$ch_login = curl_init();
$url_login = 'http://192.168.248.35/projects/ClassevivaComms/Fat3/login';
curl_setopt($ch_login, CURLOPT_URL, $url_login);
curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('username' => 'G8183544Y', 'password' => 'sf10796i')));
curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
$response_login = curl_exec($ch_login);
$loginData = json_decode($response_login, true);
//echo $response_login;
//
$_SESSION['token'] = $loginData["token"];
//echo "\n";
$_SESSION['id'] = filter_var($loginData["ident"], FILTER_SANITIZE_NUMBER_INT);
//echo "\n";
//echo $_SESSION['name'] = filter_var($loginData["firstName"]);
//echo "\n";
//echo $_SESSION['surname'] = filter_var($loginData["lastName"]);

$ch_comms = curl_init();
$url_comms = 'http://192.168.248.35/projects/ClassevivaComms/Fat3/noticeboard';
curl_setopt($ch_comms, CURLOPT_URL, $url_comms);
curl_setopt($ch_comms, CURLOPT_POSTFIELDS, http_build_query(array('id'=> $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_comms, CURLOPT_RETURNTRANSFER, true);
$response_comms = curl_exec($ch_comms);
$commsData = json_decode($response_comms, true);

echo json_encode($commsData['items'],true);
?>
