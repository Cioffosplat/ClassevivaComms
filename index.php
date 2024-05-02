<?php
session_start();
//Unsetting of the session items
unset($_SESSION['ident']);
unset($_SESSION['id']);
unset($_SESSION['firstName']);
unset($_SESSION['lastName']);
unset($_SESSION['token']);
unset($_SESSION['commsNumber']);
unset($_SESSION['commsResponse']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-Classeviva Comms</title>
    <link rel="icon" type="image/x-icon" href="/resources/images/logos/logotheme0.jpg">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu+Condensed&display=swap')
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<div id="header" class="py-2 flex justify-between items-center rounded-b-2xl">
    <div class="inline-flex px-12">
        <img id="logo" class="w-20 h-20 rounded-full cursor-pointer shadow-2xl hover" onclick="redirectToLoginpage()" src="resources/images/logos/logotheme0.jpg" alt="Logo">
        <p class="text-xl pt-6 px-3 ">Classeviva Comms</p>
    </div>
</div>

<div id="loginPage" class="container mx-auto py-20 h-55 flex justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96 flex-column">
        <h2 class="text-4xl mb-5 mx-12 self-center">Login Classeviva</h2>
        <form action="phpPages/mainPage.php" method="post">
            <div class="m-4 self-center text-base text-gray-800">
                Inserisci le credenziali di Classeviva per accedere
            </div>
            <div class="mb-6">
                <div class="mb-6">
                    <label for="username" class="block text-base text-gray-700">Username/Email:</label>
                    <div class="inline flex mt-1 block w-full px-4 bg-white
                    focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-500 placeholder-gray-500
                    pl-1 pr-4 rounded-2xl border border-gray-400 w-full
                    focus:outline-none focus:border-blue-400">
                        <svg class="h-6 w-6 mt-4 mr-1" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                        <input type="text" id="username" name="username" class="flex py-4 w-full focus:outline-none" placeholder="Enter your email">
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-base text-gray-700">Password:</label>
                <div class="inline flex mt-1 block w-full px-4 bg-white
                    focus:outline-none focus:border-blue-500 focus:ring focus:ring-blue-500 placeholder-gray-500
                    pl-1 pr-4 rounded-2xl border border-gray-400 w-full
                    focus:outline-none focus:border-blue-400">
                    <svg class="h-6 w-6 mt-4 mr-1" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <input type="password" id="password" name="password" class="flex py-4 w-full focus:outline-none" placeholder="Enter your password">
                </div>
            </div>
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='text-red-500'>{$_SESSION['error']}</p>";
                unset($_SESSION['error']);
            }
            ?>
            <class="inline-flex">
                <button type="submit" class=" flex bg-[#50858B] text-white px-20 py-3 ml-12 rounded-md hover:bg-[#7CC6FE]">Login
                    <svg class="h-6 w-6 ml-1 flex" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg></button>
            </div>
        </form>
    </div>
    <div id="cookie-banner" class="cookie_banner fixed bottom-0 left-0 w-full bg-[#50858B] text-white p-4">
        <div class="max-w-screen-lg mx-auto flex items-center justify-center space-x-3">
            <p class="text-sm">Questo sito utilizza i cookie per migliorare l'esperienza dell'utente. <br> Per le direttive riguardanti i cookie fare riferimento alla <a class="underline underline-offset-1" href="https://papaya.netsons.org/resources/websitesAndLinks/cookie_policy.html">Cookie Policy</a></p>
            <div class="flex space-x-3">
                <button onclick="acceptCookies()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">Accetta</button>
                <button onclick="rejectCookies()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">Rifiuta</button>
            </div>
        </div>
    </div>

<link href="style.css" rel="stylesheet">
<script src="scripts/index.js"></script>
</body>
</html>