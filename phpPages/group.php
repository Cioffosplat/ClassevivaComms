<?php
session_start();
?>

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


