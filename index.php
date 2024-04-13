<?php
    session_start();

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];
//login
if (isset($_POST['username']) && isset($_POST['password']) && !$loggedIn) {
    $ch_login = curl_init();
    $url_login = 'https://papaya.netsons.org/phpPages/serverRest.php/?action=login';
    curl_setopt($ch_login, CURLOPT_URL, $url_login);
    curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('username' => $_POST['username'], 'password' => $_POST['password'])));
    curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
    $response_login = curl_exec($ch_login);
    $loginData = json_decode($response_login, true);

    if ($loginData && isset($loginData['ident']) && isset($loginData['token'])) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['ident'] = $loginData["ident"];
        $_SESSION['id'] = filter_var($loginData["ident"], FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['token'] = $loginData["token"];
        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } else {
        echo "Errore nel login. Riprova.";
    }
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap')
    </style>
    <link href="style.css" rel="stylesheet">
</head>

<body id = 'body' class="text-white">

<div class="container mx-auto py-8">
    <div id="header" class="rounded-lg px-4 py-2 flex justify-between items-center relative">
        <div>
            <img id="logo" alt="Logo" class="w-20 h-20 rounded-full cursor-pointer" onclick="redirectToHomepage()">
        </div>
        <div class="flex items-center">
            <img id="paintbrushButton" class="paintbrush w-6 h-6 cursor-pointer" src="resources/images/paintbrush/paintbrushTheme0.png" alt="Paintbrush">
            <div id="paintbrushMenu" class="absolute z-10 bg-gray-200 p-4 rounded-lg top-20 right-0 transition-opacity transition-transform scale-100 origin-top shadow-2xl">
                <button onclick="setTheme('theme0')" id = 'theme0' class="block my-2 p-2 text-white rounded-md focus:ring">Default</button>
                <button onclick="setTheme('theme1')" id = 'theme1' class="block my-2 p-2 text-white rounded-md focus:ring">Theme 1</button>
                <button onclick="setTheme('theme2')" id = 'theme2' class="block my-2 p-2 text-white rounded-md focus:ring">Theme 2</button>
                <button onclick="setTheme('theme3')" id = 'theme3' class="block my-2 p-2 text-white rounded-md focus:ring">Theme 3</button>
            </div>
            <img id="userIcon" alt="User Icon" class="w-10 h-10 rounded-full ml-2 cursor-pointer" onclick="redirectToProfile()">
        </div>
    </div>
</div>

<?php
echo "\n",$_SESSION['ident'],"\n";
echo "\n",$_SESSION['id'],"\n";
echo "\n",$_SESSION['token'],"\n";
?>


<form method="post">
    <input type="hidden" name="logout" value="true">
    <input type="submit" value="Logout">
</form>
<script src="script.js"></script>
</body>
</html>
