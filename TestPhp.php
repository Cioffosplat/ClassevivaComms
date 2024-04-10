<?php

require_once 'Classeviva.php';

use Papaya\Classeviva\Students\Classeviva;

// Credenziali di accesso

$username = 'G8183544Y';
$password = 'sf10796i';
$id = null;
$token = null;

// Inizializzazione dell'istanza di Classeviva
$classeviva = new Classeviva();
/*try {
    // Ottenere i messaggi dal tabellone delle comunicazioni con scaricamento allegati
    $noticeBoardWithAttachments = $classeviva->noticeBoard(1, 1, "CF", 19805952);
    // Gestisci gli allegati scaricati qui
    echo "\n$noticeBoardWithAttachments\n";
} catch (Exception $e) {
    echo 'Errore: ' . $e->getMessage();
}*/

try {
    $loginData2 = $classeviva->login($username,$password);
    echo "\n$loginData2\n";
    $loginData = json_decode($loginData2,true);
    $_SESSION['ident'] = $loginData["ident"];
    $_SESSION['token'] = $loginData["token"];
    $id = filter_var($loginData["ident"], FILTER_SANITIZE_NUMBER_INT);
    $token = $loginData["token"];
    echo "\n", $id,"\n";
    echo $_SESSION['token'];
    $grades = $classeviva->grades($id,$token);
    echo "\n$grades\n";
} catch (Exception $e) {
    echo 'Errore: ' . $e->getMessage();
}


