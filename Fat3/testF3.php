<?php
$ch_login = curl_init();
$url_login = 'http://localhost/projects/ClassevivaComms/index.php/login';
curl_setopt($ch_login, CURLOPT_URL, $url_login);
curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('username' => 'fioccosplat@gmail.com', 'password' => 'Fiocco2018')));
curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
$response_login = curl_exec($ch_login);
$loginData = json_decode($response_login, true);
?>
