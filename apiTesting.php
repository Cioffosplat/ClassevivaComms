<?php
session_start();

$filter = 'asc';

//login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $_SESSION['loggedIn'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
}

// Logout
if (isset($_POST['logout'])) {
    $_SESSION['loggedIn'] = false;
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['grades']);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];

// Se non sei loggato, mostra il form di login e termina lo script
if (!$loggedIn) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
    <h1>Effettua il login</h1>
    <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Login">
    </form>
    </body>
    </html>
    <?php
    exit;
}

// Recupera e stampa la risposta alla richiesta di login
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $ch_login = curl_init();
    $url_login = 'http://192.168.1.177/projects/ClassevivaComms/serverRest.php/?action=login';
    curl_setopt($ch_login, CURLOPT_URL, $url_login);
    curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('username' => $_SESSION['username'], 'password' => $_SESSION['password'])));
    curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
    $response_login = curl_exec($ch_login);
    $loginData = json_decode($response_login,true);
    echo $response_login;
    $_SESSION['ident'] = $loginData["ident"];
    $_SESSION['id'] = filter_var($loginData["ident"], FILTER_SANITIZE_NUMBER_INT);
    $_SESSION['token'] = $loginData["token"];
    echo "\n",$_SESSION['ident'],"\n";
    echo "\n",$_SESSION['id'],"\n";
    echo "\n",$_SESSION['token'],"\n";
}

// Recupera e stampa la risposta alla richiesta dei voti dell'utente
if (isset($_SESSION['id']) && isset($_SESSION['token'])) {
    $ch_login = curl_init();
    $url_login = 'http://192.168.1.177/projects/ClassevivaComms/serverRest.php/?action=grades';
    curl_setopt($ch_login, CURLOPT_URL, $url_login);
    curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('id' => $_SESSION['id'], 'token' => $_SESSION['token'])));
    curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
    $response_login = curl_exec($ch_login);
    echo $response_login;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area Utente</title>
</head>
<body>
<h1>Benvenuto, <?php echo isset($_SESSION['username']) ? htmlentities($_SESSION['username']) : 'Ospite'; ?></h1>
<p>La tua password salvata nella sessione è: <?php echo isset($_SESSION['password']) ? htmlentities($_SESSION['password']) : 'Nessuna password salvata'; ?></p>

<form method="post">
    <input type="hidden" name="logout" value="true">
    <input type="submit" value="Logout">
</form>
</body>
</html>
