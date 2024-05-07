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


//Saving function for the themes
let savedTheme = sessionStorage.getItem('theme');
window.onload = function () {
    savedTheme = sessionStorage.getItem('theme');
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

//Paintbrush Menu Functions
function openPaint() {
    document.getElementById("paint").classList.add("translate-x-0");
    document.getElementById("backgroundBlur").style.display = "block";
}
function closePaint() {
    document.getElementById("paint").classList.remove("translate-x-0");
    document.getElementById("backgroundBlur").style.display = "none";
}

document.getElementById("open-paint").addEventListener("click", openPaint);
document.getElementById("paint").querySelector("button").addEventListener("click", closePaint);

//Setting of the Themes
function setTheme(theme) {
    sessionStorage.setItem('theme', theme);
    if(!getCookie("cookie_consent")) document.getElementById('cookie-banner').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('header').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
    document.getElementById('mainPage').style.backgroundColor = 'var(--' + theme + '-background-color)';
    document.getElementById('commsForm').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('starForm').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('groupForm').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('moduleForm').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('commsLogo').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('starLogo').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('groupLogo').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('moduleLogo').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('commsLogoSVG').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('starLogoSVG').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('groupLogoSVG').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('moduleLogoSVG').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('commsSubmit').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('starSubmit').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('groupSubmit').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('moduleSubmit').style.backgroundColor = 'var(--' + theme + '-primary-color)';
    document.getElementById('commsSubmit').style.color = 'var(--' + theme + '-text-color)';
    document.getElementById('starSubmit').style.color = 'var(--' + theme + '-text-color)';
    document.getElementById('groupSubmit').style.color = 'var(--' + theme + '-text-color)';
    document.getElementById('moduleSubmit').style.color = 'var(--' + theme + '-text-color)';
    document.getElementById('sidebar').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('paint').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('sidebarX').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('paintX').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('logo').src = '../resources/images/logos/logo' + theme+ '.jpg';
    document.getElementById('logoSidebar').src = '../resources/images/logos/logo' + theme+ '.jpg';
    document.getElementById('tabIcon').setAttribute('href', '/resources/images/logos/logo' + theme+ '.jpg');
    document.getElementById('paintbrushButton').src = '../resources/images/paintbrush/paintbrush' + theme + '.png';
    document.getElementById('tableBack').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('tableRows').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
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

//Table Rendering Script
var itemsPerPage = 4;
var currentPage = 1;

function renderTable(page) {
    var tableBody = document.getElementById("tableRows");
    tableBody.innerHTML = '';
    commsData.items.sort(function(a, b) {
        return new Date(b.cntValidFrom) - new Date(a.cntValidFrom);
    });

    var startIndex = (page - 1) * itemsPerPage;
    var endIndex = Math.min(startIndex + itemsPerPage, commsData.items.length);

    for (var i = startIndex; i < endIndex; i++) {
        var item = commsData.items[i];
        var row = document.createElement("tr");
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${item.cntTitle}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${item.cntCategory}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${item.cntValidFrom}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                ${item.cntHasAttach ?
            `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Allegato</span>` :
            `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nessun Allegato</span>`}
            </td>
        `;
        tableBody.appendChild(row);
    }
}
renderTable(currentPage);


//Section to update the profile pic
function updateProfilePic() {
    const sessionUserId = userId;
    const formData = new FormData();
    formData.append('sessionUserId', sessionUserId);
    fetch('http://192.168.101.35/projects/ClassevivaComms/Fat3/profile-pic', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (response.ok) {
                return response.blob();
            }
            throw new Error('Errore durante il recupero dell\'immagine del profilo');
        })
        .then(blob => {
            const imgUrl = URL.createObjectURL(blob);
            document.getElementById('userIcon').src = imgUrl;
            document.getElementById('profilePicBig').src = imgUrl;
        })
        .catch(error => {
            console.error('Errore:', error);
        });
}

updateProfilePic();
