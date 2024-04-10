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

// Se si è loggati e viene richiesta la visualizzazione dei voti, esegui la chiamata CURL per ottenere i voti
/*if (isset($_POST['getGrades'])) {
    $ch_grades = curl_init();
    $url = 'http://192.168.197.35/projects/diocane/serverRest.php/?action=grades';
    curl_setopt($ch_grades, CURLOPT_URL, $url);
    curl_setopt($ch_grades, CURLOPT_POSTFIELDS, http_build_query(array('username' => $_SESSION['username'], 'password' => $_SESSION['password'])));
    curl_setopt($ch_grades, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch_grades);
    curl_close($ch_grades);

    if ($response !== false) {
        $gradesData = json_decode($response, true);
        if (isset($gradesData['grades']) && is_array($gradesData['grades']) && count($gradesData['grades']) > 0) {
            $_SESSION['grades'] = $gradesData['grades'];
            echo "<pre>";
            echo "Risposta alla richiesta di voti: <br>";
            echo ($gradesData);
            echo "</pre>";
        }
    }
} */

// Recupera e stampa la risposta alla richiesta di login
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    $ch_login = curl_init();
    $url_login = 'http://192.168.197.35/projects/diocane/serverRest.php/?action=login';
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

if (isset($_SESSION['id']) && isset($_SESSION['token'])) {
    $ch_login = curl_init();
    $url_login = 'http://192.168.197.35/projects/diocane/serverRest.php/?action=grades';
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

<!-- <form id="gradesForm" action="" method="post">
    <input type="hidden" name="getGrades" value="true">
    <input type="submit" value="Visualizza/Nascondi Voti">
    <label for="filter">Ordina per:</label>
    <select id="filter" name="filter">
        <option value="asc" <?php if ($filter === 'asc') echo 'selected'; ?>>Dal più basso al più alto</option>
        <option value="desc" <?php if ($filter === 'desc') echo 'selected'; ?>>Dal più alto al più basso</option>
    </select>
</form>-->


<form method="post">
    <input type="hidden" name="logout" value="true">
    <input type="submit" value="Logout">
</form>

<!--
<div id="gradesList">
    <h2>Voti</h2>
    <ul id="grades">
        <?php
        if (isset($_SESSION['grades'])) {
            foreach ($_SESSION['grades'] as $grade) {
                echo '<li><strong>' . $grade['subjectDesc'] . ':</strong> ' . $grade['displayValue'] . '</li>';
            }
        }
        ?>
    </ul>
</div>-->

<!-- <script>
    document.getElementById('filter').addEventListener('change', function() {
        document.getElementById('gradesForm').submit();
    });
</script> -->
</body>
</html>
