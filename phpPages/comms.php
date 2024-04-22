<?php
session_start();
$ch_comms = curl_init();
$url_comms = 'http://192.168.1.187/projects/ClassevivaComms/Fat3/card';
curl_setopt($ch_comms, CURLOPT_URL, $url_comms);
curl_setopt($ch_comms, CURLOPT_POSTFIELDS, http_build_query(array('id'=> $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_comms, CURLOPT_RETURNTRANSFER, true);
$response_comms = curl_exec($ch_comms);
$commsData = json_decode($response_comms, true);
echo $response_comms;
?>


