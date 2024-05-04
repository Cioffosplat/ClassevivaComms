<?php
session_start();
//Comms request for comms counter
$ch_card = curl_init();
$url_card = 'http://192.168.1.177/projects/ClassevivaComms/Fat3/card';
curl_setopt($ch_card, CURLOPT_URL, $url_card);
curl_setopt($ch_card, CURLOPT_POSTFIELDS, http_build_query(array('id' => $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_card, CURLOPT_RETURNTRANSFER, true);
$response_card = curl_exec($ch_card);
$cardData = json_decode($response_card, true);
$_SESSION['cardResponse'] = $cardData;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage-Classeviva Comms</title>
    <link id="tabIcon" rel="icon" type="image/x-icon" href="/resources/images/logos/originalLogo.jpeg">
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
<div id="mainPage" class="h-screen overflow-auto bg-gray-200">
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
    <div class="flex-col fixed w-60 min-h-screen overflow-y-auto transition-transform transform -translate-x-full ease-in-out duration-300 rounded-r-2xl"
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
                <h3 class="m-2">Classeviva Comms</h3>
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
            <p class="text-xl pt-6 px-3 ">Classeviva Comms</p>
        </div>
        <div class="flex items-center px-2">
            <button class="w-7 h-7 cursor-pointer mx-2" id="open-paint">
                <img id="paintbrushButton" src="../resources/images/paintbrush/paintbrushtheme0.png" alt="paintbrushButton">
            </button>
            <img id="userIcon" alt="User Icon" class="w-10 h-10 rounded-full ml-2 cursor-pointer" onclick="redirectToProfile()" src="../resources/images/users/defaultusertheme0.jpg">
            <?php
            echo "<p style='font-family: 'Ubuntu Condensed';' class='px-2 mx-2'>{$_SESSION['firstName']} {$_SESSION['lastName']}</p>"
            ?>
        </div>
    </div>

    <!--Profile Section-->
    <div id="profileDiv" class="container mx-auto break-words mb-6 shadow-lg rounded-xl mt-16 pt-4">
        <div class="px-6">
            <div class="flex flex-wrap justify-center">
                <div class="w-full flex justify-center">
                    <div class="relative">
                        <img src="https://github.com/creativetimofficial/soft-ui-dashboard-tailwind/blob/main/build/assets/img/team-2.jpg?raw=true" class="shadow-xl rounded-full align-middle border-none absolute -m-16 -ml-20 lg:-ml-16 max-w-[150px]"/>
                    </div>
                </div>
                <div class="w-full text-center mt-20">
                    <div class="flex justify-center lg:pt-4 pt-8 pb-0">
                    </div>
                </div>
            </div>
            <div class="text-center mt-2 p-2">
                <h3 class="text-2xl  font-bold leading-normal mb-1 ml-5"><?php echo $_SESSION['cardResponse']['card']['firstName']; ?> <?php echo $_SESSION['cardResponse']['card']['lastName']; ?></h3>
                <div class="text-xs mt-0 mb-2  font-bold uppercase">
                    <i class="fas fa-map-marker-alt opacity-75 mb-2 ml-3"></i><?php echo $_SESSION['cardResponse']['card']['schCity']; ?>
                </div>
            </div>
        </div>
    </div>

    <!--About Profile Section-->
    <div id="profileAbout" class="container mx-auto overflow-x-auto mt-5 rounded-lg p-3">
        <div class="flex items-center space-x-2 font-semibold leading-8">
        <span>
            <svg id="profileAboutIcon" class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </span>
            <span class="tracking-wide">Informazioni Utente</span>
        </div>
        <div>
            <div class="grid md:grid-cols-2 text-sm">
                <div class="grid grid-cols-2">
                    <div class="px-4 py-2 font-semibold">Nome</div>
                    <div class="px-4 py-2"><?php echo $_SESSION['cardResponse']['card']['firstName']; ?></div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="px-4 py-2 font-semibold">Cognome</div>
                    <div class="px-4 py-2"><?php echo $_SESSION['cardResponse']['card']['lastName']; ?></div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="px-4 py-2 font-semibold">Data di Nascita</div>
                    <div class="px-4 py-2"><?php echo $_SESSION['cardResponse']['card']['birthDate']; ?></div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="px-4 py-2 font-semibold">Codice Fiscale</div>
                    <div class="px-4 py-2"><?php echo $_SESSION['cardResponse']['card']['fiscalCode']; ?></div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="px-4 py-2 font-semibold">Scuola</div>
                    <div class="px-4 py-2"><?php echo $_SESSION['cardResponse']['card']['schName']; ?>, <?php echo $_SESSION['cardResponse']['card']['schDedication']; ?></div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="px-4 py-2 font-semibold">Città</div>
                    <div class="px-4 py-2"><?php echo $_SESSION['cardResponse']['card']['schCity']; ?></div>
                </div>
                <div class="grid grid-cols-2">
                    <div class="px-4 py-2 font-semibold">Provincia</div>
                    <div class="px-4 py-2"><?php echo $_SESSION['cardResponse']['card']['schProv']; ?></div>
                </div>
            </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data">
            Seleziona un'immagine da caricare:
            <input type="file" name="profilePicUpload" id="profilePicUpload">
            <input type="submit" value="Carica Immagine" name="submit">
        </form>
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
<script src="../scripts/profile.js"></script>
</body>

</html>


