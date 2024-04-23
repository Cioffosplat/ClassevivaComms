//Saving function for the themes
window.onload = function () {
    const savedTheme = sessionStorage.getItem('theme');
    if (savedTheme) {
        setTheme(savedTheme);
    } else {
        setTheme('theme0');
    }
    checkCookieConsent();
}

// Logout Function when tab is closed
window.onunload = function (){
    $.ajax({
        url: 'login.php',
        method: 'POST',
        data: {logout: true},
    });
}

//Sidebar Functions
document.getElementById("open-sidebar").addEventListener("click", openSidebar);
document.getElementById("sidebar").querySelector("button").addEventListener("click", closeSidebar);
function openSidebar() {
    document.getElementById("sidebar").classList.remove("-translate-x-full");
    document.getElementById("backgroundBlur").style.display = "block";
}
function closeSidebar() {
    document.getElementById("sidebar").classList.add("-translate-x-full");
    document.getElementById("backgroundBlur").style.display = "none";
}

//Paintbrush Menu
function togglePaintbrushMenu() {
    const paintbrushMenu = document.getElementById('paintbrushMenu');
    paintbrushMenu.classList.toggle('show');
}

//Setting of the Themes
function setTheme(theme) {
    sessionStorage.setItem('theme', theme);
    document.getElementById('header').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
    document.getElementById('mainPage').style.backgroundColor = 'var(--' + theme + '-background-color)';
    document.getElementById('sidebar').style.color = 'var(--' + theme + '-text-color)';
    document.getElementById('sidebar').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('logo').src = '../resources/images/logos/logo' + theme+ '.jpg';
    document.getElementById('logoSidebar').src = '../resources/images/logos/logo' + theme+ '.jpg';
    document.getElementById('tabIcon').setAttribute('href', '/resources/images/logos/logo' + theme+ '.jpg');
    document.getElementById('userIcon').src = '../resources/images/users/defaultuser' + theme + '.jpg';
    document.getElementById('paintbrushButton').src = '../resources/images/paintbrush/paintbrush' + theme + '.png';
    document.getElementById('paintbrushMenu').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('sidebarX').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementsByClassName('sidebarText')[0].style.color = 'var(--' + theme + '-text-color)';
    document.getElementsByClassName('sidebarText')[1].style.color = 'var(--' + theme + '-text-color)';
    document.getElementsByClassName('sidebarText')[2].style.color = 'var(--' + theme + '-text-color)';
    document.getElementById('tableBack').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('tableRows').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    if(!getCookie("cookie_consent")) document.getElementById('cookie-banner').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementsByClassName('tableNav')[0].style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementsByClassName('tableNav')[1].style.backgroundColor = 'var(--' + theme + '-accent-color)';
}

function redirectToProfile() {
    window.location.href = "profile.php";
}

function redirectToHomepage() {
    window.location.href = "mainPage.php";
}

//Sezione per il cookie banner
function setCookieConsent(consent) {
    var d = new Date();
    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = "cookie_consent=" + consent + ";" + expires + ";path=/";
}

function checkCookieConsent() {
    var consent = getCookie("cookie_consent");
    if (consent === "true") {
        hideCookieBanner();
    } else if (consent === "false") {
        hideCookieBanner();
    } else {
        showCookieBanner();
    }
}

function showCookieBanner() {
    document.getElementById("cookie-banner").style.display= "block";
}

function hideCookieBanner() {
    document.getElementById("cookie-banner").style.display = "none";
}

function acceptCookies() {
    setCookieConsent(true);
    hideCookieBanner();
}
function rejectCookies() {
    setCookieConsent(true);
    hideCookieBanner();
}

function getCookie(name) {
    var cname = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var cookieArr = decodedCookie.split(';');
    for (var i = 0; i < cookieArr.length; i++) {
        var c = cookieArr[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(cname) === 0) {
            return c.substring(cname.length, c.length);
        }
    }
    return "";
}