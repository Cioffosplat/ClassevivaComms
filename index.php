<?php
session_start();

$loggedIn = isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'];

//login
if (isset($_POST['username']) && isset($_POST['password']) && !$loggedIn) {
    $ch_login = curl_init();
    $url_login = 'http:/papaya.netsons.org/Fat3/login';
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
        $_SESSION['firstName'] = $loginData["firstName"];
        $_SESSION['lastName'] = $loginData["lastName"];
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
        <title>Homepage</title>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap')
        </style>
        <link href="style.css" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {}
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>

    <body>
    <div id="headerLogin" class="px-20 py-2 flex justify-between items-center relative">
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
        echo '<div id="cookie-banner" class="cookie_banner fixed bottom-0 left-0 w-full bg-gray-900 text-white p-4" style="background-color: var(--theme0-accent2-color)">
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
    <form id="logoutForm" method="post" style="display: none;">
        <input type="hidden" name="logout" value="true">
    </form>

    <script src="script.js"></script>

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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap')
    </style>
    <link href="style.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body id='body'>
<div id="backgroundBlur" class="fixed inset-0 transition-opacity">
    <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
</div>
<div id="mainPage" class="h-screen flex-col overflow-hidden bg-gray-200">
    <div>
        <div class="absolute text-white w-56 min-h-screen overflow-y-auto transition-transform transform -translate-x-full ease-in-out duration-300"
             id="sidebar">
            <button class="absolute top-0 right-0 m-4 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6  18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="p-4">
                <h1 class="text-2xl font-semibold">Sidebar</h1>
                <ul class="mt-4">
                    <li class="mb-2"><a href="#" class="block hover:text-indigo-400">Home</a></li>
                    <li class="mb-2"><a href="#" class="block hover:text-indigo-400">About</a></li>
                    <li class="mb-2"><a href="#" class="block hover:text-indigo-400">Services</a></li>
                    <li class="mb-2"><a href="#" class="block hover:text-indigo-400">Contact</a></li>
                </ul>
            </div>
        </div>
        <div id="header" class="px-20 py-2 flex justify-between items-center ">
            <div class="inline-flex">
                <button class="px-4 text-black hover:text-gray-700" id="open-sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <img id="logo" class="w-20 h-20 rounded-full cursor-pointer shadow-2xl hover" onclick="redirectToHomepage()">
                <p class="text-xl pt-6 px-3 ">ClassevivaComms</p>
            </div>
            <div class="flex items-center px-2">
                <img id="paintbrushButton" class="paintbrush w-7 h-7 cursor-pointer mx-2" src="resources/images/paintbrush/paintbrushTheme0.png" onclick="togglePaintbrushMenu()">
                <div id="paintbrushMenu" class="fixed z-10 p-3 rounded-lg top-20 left-57 scale-100 origin-top shadow-2xl mx-2">
                    <button onclick="setTheme('theme0')" id='theme0' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Default</button>
                    <button onclick="setTheme('theme1')" id='theme1' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 1</button>
                    <button onclick="setTheme('theme2')" id='theme2' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 2</button>
                    <button onclick="setTheme('theme3')" id='theme3' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 3</button>
                </div>
                <img id="userIcon" alt="User Icon" class="w-10 h-10 rounded-full ml-2 cursor-pointer" onclick="redirectToProfile()">
                <?php
                echo "<p class='px-2 mx-2'>{$_SESSION['firstName']} {$_SESSION['lastName']}</p>"
                ?>
            </div>
        </div>
    </div>
<?php
echo "<div class='flex'>
        <p class='px-2'> {$_SESSION['ident']}</p>
        <p class='px-2'> {$_SESSION['id']}</p>  
        <p class='px-2'> {$_SESSION['token']}</p>
       </div>";
?>

<?php
if(!isset($_COOKIE['cookie_consent'])){
    echo '<div id="cookie-banner" class="fixed bottom-0 left-0 w-full text-white p-4" style="background-color: var(--theme0-accent2-color)">
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
    <script src="script.js"></script>
</body>

</html>
