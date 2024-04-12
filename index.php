<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>

<body id = 'body' class="text-white">

<div class="container mx-auto py-8">
    <div id="header" class="rounded-lg px-4 py-2 flex justify-between items-center relative">
        <div>
            <img id="logo" alt="Logo" class="w-20 h-20 rounded-full">
        </div>
        <div class="flex items-center">
            <img id="paintbrushButton" class="paintbrush w-6 h-6 cursor-pointer" src="resources/images/paintbrush/paintbrushTheme0.png" alt="Paintbrush">
            <div id="paintbrushMenu" class="absolute z-10 bg-gray-200 p-4 rounded-lg top-20 right-0 transition-opacity transition-transform scale-100 origin-top">
                <button onclick="setTheme('theme0')" id = 'theme0' class="block my-2 p-2 bg-blue-500 text-white rounded-md">Theme 0</button>
                <button onclick="setTheme('theme1')" id = 'theme1' class="block my-2 p-2 bg-yellow-500 text-white rounded-md">Theme 1</button>
                <button onclick="setTheme('theme2')" id = 'theme2' class="block my-2 p-2 bg-green-500 text-white rounded-md">Theme 2</button>
                <button onclick="setTheme('theme3')" id = 'theme3' class="block my-2 p-2 bg-red-500 text-white rounded-md">Theme 3</button>
            </div>
            <img id="userIcon" alt="User Icon" class="w-10 h-10 rounded-full ml-2">
        </div>
    </div>
</div>

<script src="script.js"></script>

</body>

</html>
