<?php
session_start();

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];

//login
if (isset($_POST['username']) && isset($_POST['password']) && !$loggedIn) {
    $ch_login = curl_init();
    $url_login = 'https://papaya.netsons.org/serverRest.php/?action=login';
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
        $_SESSION['error'] = "Email o password errate. ";
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
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap')
        </style>
        <link href="style.css" rel="stylesheet">
        <script src="script.js"></script>
    </head>

    <body>
    <div id="headerLogin" class="px-4 py-2 flex justify-between items-center relative" style="background-color: var(--theme0-secondary-color)">
        <img id="logo" class="w-20 h-20 rounded-full cursor-pointer shadow-2xl hover" onclick="redirectToHomepage()" src="resources/images/logos/logoTheme0.jpg">
    </div>

    <div id= "loginPage" class="container mx-auto py-20 h-50 flex justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-3xl mb-6">Login con Classeviva</h2>
            <form method="post">
                <div class="mb-6">
                    <label for="username" class="block text-sm text-gray-700">Username/Email</label>
                    <input type="text" id="username" name="username" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-lg focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-500" placeholder="Enter your email">
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-md text-lg focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-500" placeholder="Enter your password">
                </div>
                <?php
                if (isset($_SESSION['error'])) {
                    echo "<p class='text-red-500'>{$_SESSION['error']}</p>";
                    unset($_SESSION['error']);
                }
                ?>
                <div class="text-right">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600">Login</button>
                </div>
            </form>
        </div>
    </div>
    <?php
    if(!isset($_COOKIE['cookie_consent'])){
        echo '<div style="background-color: var(--theme0-accent2-color)"id="cookie-banner" class="cookie_banner fixed bottom-0 left-0 w-full bg-gray-900 text-white p-4">
        <div class="max-w-screen-lg mx-auto flex items-center justify-center space-x-3">
            <p class="text-sm">Questo sito utilizza i cookie per migliorare l\'esperienza dell\'utente. <br> Per le direttive riguardanti i cookie fare riferimento alla <a class="underline underline-offset-1" href="https://papaya.netsons.org/resources/websitesAndLinks/cookie_policy.html">Cookie Policy</a></p>
            <div class="flex space-x-3">
                <button onclick="acceptCookies()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">Accetta</button>
                <button onclick="rejectCookies()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">Rifiuta</button>
            </div>
        </div>
    </div>';
    }
    ?>
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
    <script src="script.js"></script>
</head>

<body id='body'>
<div id="header" class="px-4 py-2 flex justify-between items-center relative">
    <div>
        <img id="logo" class="w-20 h-20 rounded-full cursor-pointer shadow-2xl hover" onclick="redirectToHomepage()">
    </div>
    <div class="flex items-center">
        <img id="paintbrushButton" class="paintbrush w-6 h-6 cursor-pointer" src="resources/images/paintbrush/paintbrushTheme0.png" alt="Paintbrush" onclick="togglePaintbrushMenu()">
        <div id="paintbrushMenu" class="absolute z-10 bg-gray-200 p-4 rounded-lg top-20 right-0 scale-100 origin-top shadow-2xl">
            <button onclick="setTheme('theme0')" id='theme0' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Default</button>
            <button onclick="setTheme('theme1')" id='theme1' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 1</button>
            <button onclick="setTheme('theme2')" id='theme2' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 2</button>
            <button onclick="setTheme('theme3')" id='theme3' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 3</button>
        </div>
        <img id="userIcon" alt="User Icon" class="w-10 h-10 rounded-full ml-2 cursor-pointer" onclick="redirectToProfile()">
    </div>
</div>

<?php
echo "\n", $_SESSION['ident'], "\n";
echo "\n", $_SESSION['id'], "\n";
echo "\n", $_SESSION['token'], "\n";
?>

<?php
if(!isset($_COOKIE['cookie_consent'])){
    echo '<div style="background-color: var(--theme0-accent2-color)"id="cookie-banner" class="cookie_banner fixed bottom-0 left-0 w-full bg-gray-900 text-white p-4">
        <div class="max-w-screen-lg mx-auto flex items-center justify-center space-x-3">
            <p class="text-sm">Questo sito utilizza i cookie per migliorare l\'esperienza dell\'utente. <br> Per le direttive riguardanti i cookie fare riferimento alla <a class="underline underline-offset-1" href="https://papaya.netsons.org/resources/websitesAndLinks/cookie_policy.html">Cookie Policy</a></p>
            <div class="flex space-x-3">
                <button onclick="acceptCookies()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">Accetta</button>
                <button onclick="rejectCookies()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">Rifiuta</button>
            </div>
        </div>
    </div>';
}
?>
<form method="post">
    <input type="hidden" name="logout" value="true">
    <input type="submit" value="Logout">
</form>
</body>

</html>
