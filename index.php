<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
          rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <script src="script.js"></script>
</head>

<body class="text-white" style="background-color: var(--theme0-background-color)">

<div class="container mx-auto py-8">
    <!-- Header rettangolare stondato con colore secondario -->
    <div id="header" class="rounded-lg px-4 py-2 flex justify-between items-center">
        <!-- Contenuto del header -->
        <div>
            <!-- Logo del sito -->
            <img id="logo" alt="Logo" class="w-20 h-20 rounded-full">
        </div>
        <div>
            <!-- Immagine personalizzata per l'icona utente -->
            <img id="userIcon" alt="User Icon" class="w-8 h-8 rounded-full">
        </div>
    </div>
    <!-- Altre sezioni o contenuti possono essere aggiunti qui -->
    <div class="mt-4">
        <!-- Pulsanti per cambiare tema -->
        <button onclick="setTheme('theme0')">Theme 0</button>
        <button onclick="setTheme('theme1')">Theme 1</button>
        <button onclick="setTheme('theme2')">Theme 2</button>
        <button onclick="setTheme('theme3')">Theme 3</button>
    </div>
</div>
</body>
</html>
