<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <!-- Definizione delle variabili per i colori -->
    <style>
        :root {
            --theme0-primary-color: #5DFDCB;
            --theme0-secondary-color: #7CC6FE;
            --theme0-accent-color: #F4FAFF;
            --theme0-text-color: #8789C0;
            --theme0-background-color: #08090A;

            --theme1-primary-color: #221d23;
            --theme1-secondary-color: #4f3824;
            --theme1-accent-color: #d1603d;
            --theme1-text-color: #ddb967;
            --theme1-background-color: #d0e37f;

            --theme2-primary-color: #210B2C;
            --theme2-secondary-color: #55286F;
            --theme2-accent-color: #BC96E6;
            --theme2-text-color: #D8B4E2;
            --theme2-background-color: #AE759F;

            --theme3-primary-color: #EEE0CB;
            --theme3-secondary-color: #BAA898;
            --theme3-accent-color: #848586;
            --theme3-text-color: #C2847A;
            --theme3-background-color: #280003;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css"
          rel="stylesheet">
</head>

<body class="text-white" style="background-color: var(--theme0-background-color)">

<div class="container mx-auto py-8">
    <!-- Header rettangolare stondato con colore secondario -->
    <div id="header" class="rounded-lg px-4 py-2 flex justify-between items-center"
         style="background-color: var(--theme0-secondary-color)">
        <!-- Contenuto del header -->
        <div>
            <!-- Logo del sito -->
            <img id="logo" src="resources/images/logos/logoTheme0.jpg" alt="Logo" class="w-20 h-20 rounded-full">
        </div>
        <div>
            <!-- Immagine personalizzata per l'icona utente -->
            <img id="userIcon" src="resources/images/users/defaultUserTheme0.jpg" alt="User Icon" class="w-8 h-8 rounded-full">
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

<script>
    // Funzione per impostare il tema quando la pagina si carica
    window.onload = function() {
        // Controlla se c'è un tema salvato in sessione
        const savedTheme = sessionStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        } else {
            // Imposta il tema predefinito se non è stato salvato nulla in sessione
            setTheme('theme0');
        }
    }

    function setTheme(theme) {
        // Salva il tema selezionato nella sessione del browser
        sessionStorage.setItem('theme', theme);

        // Applica il tema selezionato
        document.getElementById('header').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
        document.getElementById('logo').src = 'resources/images/logos/logo' + theme.charAt(0).toUpperCase() + theme.slice(1) + '.jpg';
        document.getElementById('userIcon').src = 'resources/images/users/defaultUser' + theme.charAt(0).toUpperCase() + theme.slice(1) + '.jpg';
    }
</script>

</body>

</html>
