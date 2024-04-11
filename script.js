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
