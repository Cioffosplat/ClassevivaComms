//Saving function for the themes
window.onload = function() {
    const savedTheme = sessionStorage.getItem('theme');
    if (savedTheme) {
        setTheme(savedTheme);
    } else {
        setTheme('theme0');
    }
}

//Paintbrush Menu
function togglePaintbrushMenu() {
    const paintbrushMenu = document.getElementById('paintbrushMenu');
    paintbrushMenu.classList.toggle('show');
}
document.getElementById('paintbrushButton').addEventListener('click', togglePaintbrushMenu);

//Setting of the Themes
function setTheme(theme) {
    sessionStorage.setItem('theme', theme);
    document.getElementById('header').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
    document.getElementById('body').style.backgroundColor = 'var(--' + theme + '-background-color)';
    document.getElementById('paintbrushMenu').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('logo').src = 'resources/images/logos/logo' + theme.charAt(0).toUpperCase() + theme.slice(1) + '.jpg';
    document.getElementById('userIcon').src = 'resources/images/users/defaultUser' + theme.charAt(0).toUpperCase() + theme.slice(1) + '.jpg';
    document.getElementById('paintbrushButton').src = `resources/images/paintbrush/paintbrush${theme.charAt(0).toUpperCase() + theme.slice(1)}.png`;
}

function redirectToProfile() {
    window.location.href = "phpPages/profile.php";
}

function redirectToHomepage() {
    window.location.href = "index.php";
}

