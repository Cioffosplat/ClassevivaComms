<?php
session_start();

//login
if (isset($_POST['username']) && isset($_POST['password'])) {
    $ch_login = curl_init();
    $url_login = 'http:/papaya.netsons.org/Fat3/login';
    curl_setopt($ch_login, CURLOPT_URL, $url_login);
    curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('username' => $_POST['username'], 'password' => $_POST['password'])));
    curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
    $response_login = curl_exec($ch_login);
    $loginData = json_decode($response_login, true);

    if ($loginData && isset($loginData['ident']) && isset($loginData['token'])) {
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
    unset($_SESSION['ident']);
    unset($_SESSION['id']);
    unset($_SESSION['firstName']);
    unset($_SESSION['lastName']);
    unset($_SESSION['token']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage-ClassevivaComms</title>
    <link id="tabIcon" rel="icon" type="image/x-icon" href="/resources/images/logos/originalLogo.jpeg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap')
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {}
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<div id="backgroundBlur" class="fixed inset-0 transition-opacity">
    <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
</div>
<!-- MainPage Section -->
<div id="mainPage" class="h-screen overflow-hidden bg-gray-200">
    <!-- Sidebar Section-->
    <div class=" flex-col absolute w-60 min-h-screen overflow-y-auto transition-transform transform -translate-x-full ease-in-out duration-300 rounded-r-2xl"
         id="sidebar">
        <button class="absolute top-0 right-0 m-4 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                 stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6  18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="p-4">
            <div class="inline-flex items-center">
                <img id="logoSidebar" class="w-20 h-20 rounded-full cursor-pointer shadow-2xl hover" onclick="redirectToHomepage()" src="../resources/images/logos/logotheme0.jpg" alt="Logo">
                <h3 class="m-2">ClassevivaComms</h3>
            </div>
            <ul class="column-flex text-2xl mt-5 ">
                <li class="flex mb-10"><a href="" class="block"><h3 class="sidebarText">Comunicazioni</h3></a></li>
                <li class="flex mb-10"><a href="" class="block"><h3 class="sidebarText">Preferiti</h3></a></li>
                <li class="flex mb-10"><a href="" class="block"><h3 class="sidebarText">Gruppi</h3></a></li>
                <li class="flex mb-10"><form action="../index.php" method="post">
                        <input type="hidden" name="logout" value="true">
                        <input class="cursor-pointer" type="submit" value="Logout">
                    </form></li>
            </ul>
        </div>
    </div>
    <!-- Header Section-->
        <div id="header" class="py-2 flex justify-between items-center rounded-b-2xl">
            <div class="inline-flex">
                <button class="px-4 text-black hover:text-gray-700" id="open-sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
                <img id="logo" class="w-20 h-20 rounded-full cursor-pointer shadow-2xl hover" onclick="redirectToHomepage()" src="../resources/images/logos/logotheme0.jpg" alt="Logo">
                <p class="text-xl pt-6 px-3 ">ClassevivaComms</p>
            </div>
            <div class="flex items-center px-2">
                <img id="paintbrushButton" class="w-7 h-7 cursor-pointer mx-2" src="../resources/images/paintbrush/paintbrushtheme0.png" onclick="togglePaintbrushMenu()" alt="Paintbrush">
                <div id="paintbrushMenu" class="fixed z-10 p-3 rounded-lg top-20 left-57 scale-100 origin-top shadow-2xl mx-2">
                    <button onclick="setTheme('theme0')" id='theme0' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Default</button>
                    <button onclick="setTheme('theme1')" id='theme1' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 1</button>
                    <button onclick="setTheme('theme2')" id='theme2' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 2</button>
                    <button onclick="setTheme('theme3')" id='theme3' class="block my-2 p-2 rounded-md focus:ring shadow-2xl">Theme 3</button>
                </div>
                <img id="userIcon" alt="User Icon" class="w-10 h-10 rounded-full ml-2 cursor-pointer" onclick="redirectToProfile()" src="../resources/images/users/defaultusertheme0.jpg">
                <?php
                echo "<p style='font-family: 'Ubuntu Condensed';' class='px-2 mx-2'>{$_SESSION['firstName']} {$_SESSION['lastName']}</p>"
                ?>
            </div>
        </div>

    <!--Card Forms-->
    <div id="cards" class="flex flex-row w-screen mt-20 place-content-center">
        <form action="comms.php" class=" " style="width: 25%">
            <div id="commsForm" class="bg-white shadow-lg rounded-lg p-6 space-y-4 ">
                <div class="flex items-center space-x-4">
                    <div id="commsLogo" class="p-2 rounded-full">
                        <svg id="commsLogoSVG" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke = "black" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>

                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Comunicazioni Totali</div>
                        <div class="text-gray-900 text-2xl font-semibold ">
                            71,897
                        </div>
                    </div>
                </div>
                <button id="commsSubmit" type="submit" class="w-full text-gray-900 text-sm py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    Visualizza
                </button>
            </div>
        </form>
        <form action="star.php" class="mx-10" style="width: 25%">
            <div id="starForm" class="bg-white shadow-lg rounded-lg p-6 space-y-4">
                <div class= "flex items-center space-x-4">
                    <div id="starLogo" class="p-2 rounded-full">
                        <svg id="starLogoSVG" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Preferiti</div>
                        <div class="text-gray-900 text-2xl font-semibold">
                            200
                        </div>
                    </div>
                </div>
                <button id="starSubmit" type="submit" class="w-full text-gray-900 text-sm py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    Visualizza
                </button>
            </div>
        </form>
        <form action="group.php" class=" " style="width: 25%">
            <div id="groupForm" class="bg-white shadow-lg rounded-lg p-6 space-y-4">
                <div class= "flex items-center space-x-4">
                    <div id="groupLogo" class="p-2 rounded-full">
                        <svg id="groupLogoSVG" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Gruppi</div>
                        <div class="text-gray-900 text-2xl font-semibold">
                            5
                        </div>
                    </div>
                </div>
                <button id="groupSubmit" type="submit" class="w-full text-gray-900 text-sm py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    Visualizza
                </button>
            </div>
        </form>
    </div>
</div>
<?php
/*echo "<div class='flex m-9'>
        <p class='px-2'> {$_SESSION['ident']}</p>
        <p class='px-2'> {$_SESSION['id']}</p>  
        <p class='px-2'> {$_SESSION['token']}</p>
       </div>";
*/
?>
<!-- Cookie Banner -->
<div id="cookie-banner" class="cookie_banner fixed bottom-0 left-0 w-full text-black p-4" style="background-color: var(--theme0-accent2-color)">
        <div class="max-w-screen-lg mx-auto flex items-center justify-center space-x-3">
            <p class="text-sm">Questo sito utilizza i cookie per migliorare l\'esperienza dell\'utente. <br> Per le direttive riguardanti i cookie fare riferimento alla <a class="underline underline-offset-1" href="https://papaya.netsons.org/resources/websitesAndLinks/cookie_policy.html">Cookie Policy</a></p>
            <div class="flex space-x-3">
                <button onclick="acceptCookies()" class="bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">Accetta</button>
                <button onclick="rejectCookies()" class="bg-red-500 hover:bg-red-600 text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">Rifiuta</button>
            </div>
        </div>
    </div>
}
    <link href="../style.css" rel="stylesheet">
    <script src="../scripts/mainPage.js"></script>
</body>

</html>
