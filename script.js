/*Colour Palettes*/
var theme0PrimaryColor = "#5DFDCB";
var theme0SecondaryColor = "#7CC6FE";
var theme0AccentColor = "#F4FAFF";
var theme0Accent2Color = "#011627";
var theme0TextColor = "#000000";
var theme0BackgroundColor = "#bdbebd";

var theme1PrimaryColor = "#221d23";
var theme1SecondaryColor = "#4f3824";
var theme1AccentColor = "#d1603d";
var theme1Accent2Color = "#ddb967";
var theme1TextColor = "#000000";
var theme1BackgroundColor = "#d0e37f";

var theme2PrimaryColor = "#210B2C";
var theme2SecondaryColor = "#55286F";
var theme2AccentColor = "#BC96E6";
var theme2Accent2Color = "#D8B4E2";
var theme2TextColor = "#FFFFFF";
var theme2BackgroundColor = "#AE759F";

var theme3PrimaryColor = "#EEE0CB";
var theme3SecondaryColor = "#BAA898";
var theme3AccentColor = "#848586";
var theme3Accent2Color = "#C2847A";
var theme3TextColor = "#FFFFFF";
var theme3BackgroundColor = "#280003";

//Ajax Script implementation
var script = document.createElement("SCRIPT");
script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js';
script.type = 'text/javascript';

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
        url: '<?php echo $_SERVER[\'PHP_SELF\']; ?>',
        method: 'POST',
        data: {logout: true},
    });
}

//Sidebar Functions
function openSidebar() {
    document.getElementById("sidebar").classList.remove("-translate-x-full");
    document.getElementById("backgroundBlur").style.display = "block";
}
function closeSidebar() {
    document.getElementById("sidebar").classList.add("-translate-x-full");
    document.getElementById("backgroundBlur").style.display = "none";
}
document.getElementById("open-sidebar").addEventListener("click", openSidebar);
document.getElementById("sidebar").querySelector("button").addEventListener("click", closeSidebar);

//Paintbrush Menu
function togglePaintbrushMenu() {
    const paintbrushMenu = document.getElementById('paintbrushMenu');
    paintbrushMenu.classList.toggle('show');
}

//Setting of the Themes
function setTheme(theme) {
    sessionStorage.setItem('theme', theme);
    document.getElementById('header').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
    document.getElementById('body').style.backgroundColor = 'var(--' + theme + '-background-color)';
    document.getElementById('body').style.color = 'var(--' + theme + '-text-color)';
    document.getElementById('sidebar').style.color = 'var(--' + theme + '-text-color)';
    document.getElementById("sidebarText1").classList.add("hover:text-[" + 'var(--' + theme + '-accent-color)' + "]");
    document.getElementById("sidebarText2").classList.add("hover:text-[" + 'var(--' + theme + '-accent-color)' + "]");
    document.getElementById("sidebarText3").classList.add("hover:text-[" + 'var(--' + theme + '-accent-color)' + "]");
    document.getElementById("sidebarText4").classList.add("hover:text-[" + 'var(--' + theme + '-accent-color)' + "]");
    document.getElementById('logo').src = 'resources/images/logos/logo' + theme.charAt(0).toUpperCase() + theme.slice(1) + '.jpg';
    document.getElementById('sidebar').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('cookie-banner').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('paintbrushMenu').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('userIcon').src = 'resources/images/users/defaultUser' + theme.charAt(0).toUpperCase() + theme.slice(1) + '.jpg';
    document.getElementById('paintbrushButton').src = `resources/images/paintbrush/paintbrush${theme.charAt(0).toUpperCase() + theme.slice(1)}.png`;
}

function redirectToProfile() {
    window.location.href = "profile.php";
}

function redirectToHomepage() {
    window.location.href = "index.php";
}

//Sezione per il cookie banner
function setCookieConsent(consent) {
    var d = new Date();
    d.setTime(d.getTime() + (30 * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = "cookie_consent=" + consent + ";" + expires + ";path=/";
    sessionStorage.setItem("cookie_consent", consent);
}

function checkCookieConsent() {
    var consent = getCookie("cookie_consent") || sessionStorage.getItem("cookie_consent");
    if (consent === "true") {
        hideCookieBanner();
    } else if (consent === "false") {
        hideCookieBanner();
    } else {
        showCookieBanner();
    }
}

function showCookieBanner() {
    var banner = document.getElementById("cookie-banner");
    banner.style.display = "block";
}

function hideCookieBanner() {
    var banner = document.getElementById("cookie-banner");
    banner.style.display = "none";
}

function acceptCookies() {
    setCookieConsent(true);
    hideCookieBanner();
}

function rejectCookies() {
    setCookieConsent(false);
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
