<?php
session_start();
$ch_comms = curl_init();
$url_comms = 'http://192.168.1.177/projects/ClassevivaComms/Fat3/noticeboard';
curl_setopt($ch_comms, CURLOPT_URL, $url_comms);
curl_setopt($ch_comms, CURLOPT_POSTFIELDS, http_build_query(array('id'=> $_SESSION['id'], 'token' => $_SESSION['token'])));
curl_setopt($ch_comms, CURLOPT_RETURNTRANSFER, true);
$response_comms = curl_exec($ch_comms);
$commsData = json_decode($response_comms, true);
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
<div id="mainPage" class="h-screen overflow-auto bg-gray-200">
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

    <div class="container mx-auto overflow-x-auto mt-5">
        <h1 class="text-2xl font-bold mb-4">Comunicazioni</h1>
    </div>

    <?php
    $commsPerPage = 10;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $startIndex = ($page - 1) * $commsPerPage;
    $currentPageData = array_slice($commsData['items'], $startIndex, $commsPerPage);
    $totalPages = ceil(count($commsData['items']) / $commsPerPage);
    ?>

    <div class="container mx-auto overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <!-- Intestazione della tabella -->
            <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Id
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Titolo
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Categoria
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Data di Inserimento
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Allegati
                </th>
            </tr>
            </thead>
            <!-- Corpo della tabella -->
            <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($currentPageData as $item) { ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo $item['pubId']; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo $item['cntTitle']; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo $item['cntCategory']; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo $item['dinsert_allegato']; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($item['cntHasAttach']) { ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Allegato
                            </span>
                        <?php } else { ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Nessun Allegato
                            </span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>

        <div class="mt-4">
            <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                <a href="?page=<?php echo $i; ?>" class="inline-block px-4 py-2 mx-1 bg-gray-200 text-gray-800 rounded"><?php echo $i; ?></a>
            <?php } ?>
        </div>
    </div>
</div>

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
<link href="../style.css" rel="stylesheet">
<script src="../scripts/comms.js"></script>
</body>

</html>


