<?php
session_start();
if (isset($_POST['username']) && isset($_POST['password'])) {
    //login serverRest Request
    $ch_login = curl_init();
    $url_login = 'http://192.168.101.35/projects/ClassevivaComms/Fat3/login';
    curl_setopt($ch_login, CURLOPT_URL, $url_login);
    curl_setopt($ch_login, CURLOPT_POSTFIELDS, http_build_query(array('username' => $_POST['username'], 'password' => $_POST['password'])));
    curl_setopt($ch_login, CURLOPT_RETURNTRANSFER, true);
    $responseLogin = curl_exec($ch_login);
    $loginData = json_decode($responseLogin, true);

    if ($loginData && isset($loginData['ident']) && isset($loginData['token'])) {
        //Sensitive data session saving
        $_SESSION['ident'] = $loginData["ident"];
        $_SESSION['id'] = filter_var($loginData["ident"], FILTER_SANITIZE_NUMBER_INT);
        $_SESSION['token'] = $loginData["token"];
        $_SESSION['firstName'] = $loginData["firstName"];
        $_SESSION['lastName'] = $loginData["lastName"];

//        //Set default profile pic image
//        $ch_profile = curl_init();
//        $url_profile = 'http://192.168.101.35/projects/ClassevivaComms/Fat3/update-profile-pic';
//        curl_setopt($ch_profile, CURLOPT_URL, $url_profile);
//        curl_setopt($ch_profile, CURLOPT_POSTFIELDS, http_build_query(array('profile_pic' => '../resources/images/users/defaultusertheme0.jpg', 'sessionUserId' => $_SESSION['id'])));
//        curl_setopt($ch_profile, CURLOPT_RETURNTRANSFER, true);
//        $response_profile = curl_exec($ch_profile);

        header("Location: {$_SERVER['PHP_SELF']}");
        exit;
    } else if(empty($_SESSION['ident'] || $_SESSION['token']) && !isset($_POST['username']) && !isset($_POST['password'])) {
        header('Location: error.php');
    } else {
        $_SESSION['error'] = "Email o password errate. ";
        header('Location: ../index.php');
        die();
    }
}
//Comms request for comms counter
$ch_comms = curl_init();
$url_comms = 'http://192.168.101.35/projects/ClassevivaComms/Fat3/noticeboard';
curl_setopt($ch_comms, CURLOPT_URL, $url_comms);
curl_setopt($ch_comms, CURLOPT_POSTFIELDS, http_build_query(array('id' => $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_comms, CURLOPT_RETURNTRANSFER, true);
$response_comms = curl_exec($ch_comms);
$commsData = json_decode($response_comms, true);
$_SESSION['commsResponse'] = $commsData;

//Logging number of communications
$_SESSION['commsNumber'] = count($commsData['items']);

//Star request for number of starred comms
$ch_star = curl_init();
$url_star = 'http://192.168.101.35/projects/ClassevivaComms/Fat3/user-stars';
curl_setopt($ch_star, CURLOPT_URL, $url_star);
curl_setopt($ch_star, CURLOPT_POSTFIELDS, http_build_query(array('sessionUserId' => $_SESSION['id'])));
curl_setopt($ch_star, CURLOPT_RETURNTRANSFER, true);
$response_star = curl_exec($ch_star);
$starData = json_decode($response_star, true);
$_SESSION['starResponse'] = $starData;

$_SESSION['starNumber'] = sizeof($starData);

//Modules request for module counter
$ch_modules = curl_init();
$url_modules = 'http://192.168.101.35/projects/ClassevivaComms/Fat3/modules';
curl_setopt($ch_modules, CURLOPT_URL, $url_modules);
curl_setopt($ch_modules, CURLOPT_RETURNTRANSFER, true);
$response_modules = curl_exec($ch_modules);
$modulesData = json_decode($response_modules, true);
$_SESSION['modulesReponse'] = $modulesData;

//Loggin number of modules
$_SESSION['modulesNumber'] = count($modulesData);
?>

<!--Send via javascript the json array with the commsData-->
<script>var commsData = <?php echo json_encode($_SESSION['commsResponse']); ?>;</script>
<script>var userId= <?php echo $_SESSION['id'];?>;</script>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage-Classeviva Comms</title>
    <link id="tabIcon" rel="icon" type="image/x-icon" href="../resources/images/logos/originalLogo.jpeg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap')
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div id="backgroundBlur" class="fixed inset-0 transition-opacity">
    <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
</div>
<!-- MainPage Section -->
<div id="mainPage" class="h-screen overflow-hidden bg-gray-200">

    <!-- Theme Color Section -->
    <div class="fixed w-60 min-h-screen overflow-y-auto transition-transform transform translate-x-full ease-in-out duration-300 rounded-l-2xl right-0"
         id="paint">
    <button class="absolute top-0 right-0 m-4 text-white">
            <svg id="paintX" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6  18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col pt-5 pl-5">
            <a class="text-4xl">Temi:</a>
        </div>
        <div class="flex flex-col p-5">
            <div class="inline-flex items-center">
                <a class="text-xl p-5">Acqua</a>
                <button class="w-12 h-12 rounded-full bg-[#5DFDCB]" onclick="setTheme('theme0')"></button>
            </div>
            <div class="inline-flex items-center">
                <a class="text-xl p-5">Brown</a>
                <button class="w-12 h-12 rounded-full bg-[#221d23]" onclick="setTheme('theme1')"></button>
            </div>
            <div class="inline-flex items-center">
                <a class="text-xl p-5">Purple</a>
                <button class="w-12 h-12 rounded-full bg-[#210B2C]" onclick="setTheme('theme2')"></button>
            </div>
            <div class="inline-flex items-center">
                <a class="text-xl p-5">Cream</a>
                <button class="w-12 h-12 rounded-full bg-[#EEE0CB]" onclick="setTheme('theme3')"></button>
            </div>
        </div>
    </div>

    <!-- Sidebar Section-->
    <div class="fixed w-60 min-h-screen overflow-y-auto transition-transform transform -translate-x-full ease-in-out duration-300 rounded-r-2xl"
         id="sidebar">
        <button class="absolute top-0 right-0 m-4 text-white">
            <svg id="sidebarX" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24">
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
                <li class="flex mb-10"><a href="comms.php" class="block"><h3 class="sidebarText">Comunicazioni</h3></a></li>
                <li class="flex mb-10"><a href="star.php" class="block"><h3 class="sidebarText">Preferiti</h3></a></li>
                <li class="flex mb-10"><a href="group.php" class="block"><h3 class="sidebarText">Gruppi</h3></a></li>
                <li class="flex mb-10"><a href="module.php" class="block"><h3 class="sidebarText">Moduli</h3></a></li>
                <li class="flex mb-10">
                    <a href="../index.php" class="block">
                        <h3 class="sidebarText">Logout</h3>
                    </a>
                </li>
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
                <p class="text-xl mt-6 mx-3 ">Classeviva Comms</p>
            </div>
            <div class="flex items-center px-2">
                <button class="w-7 h-7 cursor-pointer mx-2" id="open-paint">
                    <img id="paintbrushButton" src="../resources/images/paintbrush/paintbrushtheme0.png" alt="paintbrushButton">
                </button>
                <img id="userIcon" alt="User Icon" class="w-10 h-10 rounded-full ml-2 cursor-pointer" onclick="redirectToProfile()" src="">
                <?php
                echo "<p style='font-family: 'Ubuntu Condensed';' class='px-2 mx-2'>{$_SESSION['firstName']} {$_SESSION['lastName']}</p>"
                ?>
            </div>
        </div>

    <div class="flex justify-center mt-10 text-6xl font-bold">
        <?php
        echo "<p style='font-family: \"Ubuntu Condensed\";' class='px-2 mx-2'>Benvenuto {$_SESSION['firstName']} {$_SESSION['lastName']} a Classeviva Comms!</p>";
        ?>
    </div>

    <!--Card Forms-->
    <div id="cards" class="flex flex-column sm:flex-row w-full mt-20 justify-center">
        <form action="comms.php" class="w-full sm:w-1/4 mb-0 md:w-1/4 lg:w-1/4 mb-5 sm:mx-5">
            <div id="commsForm" class="bg-white shadow-lg rounded-lg p-6 space-y-4">
                <div class="flex items-center space-x-4">
                    <div id="commsLogo" class="p-2 rounded-full">
                        <svg id="commsLogoSVG" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Comunicazioni</div>
                        <div class="text-gray-900 text-2xl font-semibold">
                            <?php
                            echo $_SESSION['commsNumber'];
                            ?>
                        </div>
                    </div>
                </div>
                <button id="commsSubmit" type="submit" class="w-full text-gray-900 text-sm py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    Visualizza
                </button>
            </div>
        </form>
        <form action="star.php" class="w-full sm:w-1/4 mb-0 md:w-1/4 lg:w-1/4 mb-5 sm:mx-5">
            <div id="starForm" class="bg-white shadow-lg rounded-lg p-6 space-y-4">
                <div class="flex items-center space-x-4">
                    <div id="starLogo" class="p-2 rounded-full">
                        <svg id="starLogoSVG" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Preferiti</div>
                        <div class="text-gray-900 text-2xl font-semibold">
                            <?php
                            echo $_SESSION['starNumber'];
                            ?>
                        </div>
                    </div>
                </div>
                <button id="starSubmit" type="submit" class="w-full text-gray-900 text-sm py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    Visualizza
                </button>
            </div>
        </form>
        <form action="group.php" class="w-full sm:w-1/4 mb-0 md:w-1/4 lg:w-1/4 mb-5 sm:mx-5">
            <div id="groupForm" class="bg-white shadow-lg rounded-lg p-6 space-y-4">
                <div class="flex items-center space-x-4">
                    <div id="groupLogo" class="p-2 rounded-full">
                        <svg id="groupLogoSVG" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Gruppi</div>
                        <div class="text-gray-900 text-2xl font-semibold">
                            no
                        </div>
                    </div>
                </div>
                <button id="groupSubmit" type="submit" class="w-full text-gray-900 text-sm py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    Visualizza
                </button>
            </div>
        </form>
        <form action="module.php" class="w-full sm:w-1/4 mb-0 md:w-1/4 lg:w-1/4 mb-5 sm:mx-5">
            <div id="moduleForm" class="bg-white shadow-lg rounded-lg p-6 space-y-4">
                <div class="flex items-center space-x-4">
                    <div id="moduleLogo" class="p-2 rounded-full">
                        <svg id="moduleLogoSVG" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-gray-600 text-sm">Moduli</div>
                        <div class="text-gray-900 text-2xl font-semibold">
                            <?php
                            echo $_SESSION['modulesNumber'];
                            ?>
                        </div>
                    </div>
                </div>
                <button id="moduleSubmit" type="submit" class="w-full text-gray-900 text-sm py-2 px-4 rounded-md transition duration-300 ease-in-out">
                    Visualizza
                </button>
            </div>
        </form>
    </div>

    <div class="container mx-auto overflow-x-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Ultime Comunicazioni:</h1>
    </div>

    <!--Comms Table-->
    <div class="container mt-2 mx-auto overflow-x-auto rounded-2xl">
        <table class="min-w-full divide-y divide-gray-900">
            <thead class="" id="tableBack">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    Titolo
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    <div id="filters" class="container mx-auto overflow-x-auto">
                        Categoria
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    <div id="sortToggle" class="cursor-pointer">
                        Data
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    Allegati
                </th>
            </tr>
            </thead>
            <tbody id="tableRows" class="divide-y divide-gray-900">
            </tbody>
        </table>
    </div>
</div>
<!-- Cookie Banner -->
<div id="cookie-banner" class="cookie_banner fixed bottom-0 left-0 w-full text-black p-4" style="background-color: var(--theme0-accent-color)">
        <div class="max-w-screen-lg mx-auto flex items-center justify-center space-x-3">
            <p class="text-sm">Questo sito utilizza i cookie per migliorare l'esperienza dell'utente. <br> Per le direttive riguardanti i cookie fare riferimento alla <a class="underline underline-offset-1" href="https://papaya.netsons.org/resources/websitesAndLinks/cookie_policy.html">Cookie Policy</a></p>
            <div class="flex space-x-3">
                <button onclick="acceptCookies()" class="bg-green-500 hover:bg-green-600 text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">Accetta</button>
                <button onclick="rejectCookies()" class="bg-red-500 hover:bg-red-600 text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">Rifiuta</button>
            </div>
        </div>
    </div>
    <link href="../style.css" rel="stylesheet">
    <script src="../scripts/mainPage.js"></script>
</body>

</html>
