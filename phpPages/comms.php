<?php
session_start();
//Section dedicated to the viewing of the noticeboard
$ch_comms = curl_init();
$url_comms = 'http://192.168.1.177/projects/ClassevivaComms/Fat3/noticeboard';
curl_setopt($ch_comms, CURLOPT_URL, $url_comms);
curl_setopt($ch_comms, CURLOPT_POSTFIELDS, http_build_query(array('id'=> $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_comms, CURLOPT_RETURNTRANSFER, true);
$response_comms = curl_exec($ch_comms);
$commsData = json_decode($response_comms, true);
//Section dedicated to the Comms Table
$commsPerPage = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$startIndex = ($current_page - 1) * $commsPerPage;
$currentPageData = array_slice($commsData['items'], $startIndex, $commsPerPage);
$total_pages = ceil(count($commsData['items']) / $commsPerPage);
$prev_page = ($current_page > 1) ? $current_page - 1 : 1;
$next_page = ($current_page < $total_pages) ? $current_page + 1 : $total_pages;
?>

<!--Send via javascript the json array with the commsData-->
<script>var commsData = <?php echo json_encode($commsData); ?>;</script>

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

    <div class="container mx-auto overflow-x-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Comunicazioni</h1>
    </div>

    <!--Search Field-->

    <div class="flex flex-column container mx-auto overflow-x-auto justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mt-2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input id="searchInput" class="h-10 w-full px-3 pr-16 rounded-lg text-sm"
               type="search" name="search" placeholder="Cerca comunicazioni...">
    </div>

    <!--Comms Table-->
    <div class="container mt-2 mx-auto overflow-x-auto rounded-t-2xl">
        <table class="min-w-full divide-y divide-gray-900">
            <thead class="" id="tableBack">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    Titolo
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    <div id="filters" class="container mx-auto overflow-x-auto">
                    <select id="category" onchange="applyFilters()" class="rounded-md p-1">
                        <option value="">Tutte</option>
                        <option value="Circolare">Circolare</option>
                        <option value="Scuola/famiglia">Scuola/Famiglia</option>
                        <option value="News">News</option>
                        <option value="Documenti - Segreteria Digitale">Documenti-Segreteria Digitale</option>
                    </select>
                    </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-900 uppercase tracking-wider">
                    <div id="sortToggle" class="cursor-pointer">
                        Data
                        <span id="ascIcon">▲</span>
                        <span id="descIcon" style="display: none;">▼</span>
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

        <div id="pagination" class="flex justify-center mt-4"></div>
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
<script src="../scripts/comms.js"></script>
</body>

</html>


