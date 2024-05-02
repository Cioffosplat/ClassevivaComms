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
        url: 'index.php',
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
    document.getElementById('sidebar').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('paint').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('sidebarX').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('paintX').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('logo').src = '../resources/images/logos/logo' + theme+ '.jpg';
    document.getElementById('logoSidebar').src = '../resources/images/logos/logo' + theme+ '.jpg';
    document.getElementById('tabIcon').setAttribute('href', '/resources/images/logos/logo' + theme+ '.jpg');
    document.getElementById('userIcon').src = '../resources/images/users/defaultuser' + theme + '.jpg';
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

var itemsPerPage = 10;
var currentPage = 1;

function renderTable(page) {
    var tableBody = document.getElementById("tableRows");
    tableBody.innerHTML = '';
    var startIndex = (page - 1) * itemsPerPage;
    var endIndex = Math.min(startIndex + itemsPerPage, commsData.items.length);

    for (var i = startIndex; i < endIndex; i++) {
        var item = commsData.items[i];
        var row = document.createElement("tr");
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${item.pubId}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${item.cntTitle}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${item.cntCategory}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${item.dinsert_allegato}</div>
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

function renderPagination() {
    var totalPages = Math.ceil(commsData.items.length / itemsPerPage);
    var paginationDiv = document.getElementById("pagination");
    paginationDiv.innerHTML = '';

    var prevButton = document.createElement("button");
    prevButton.textContent = "<";
    prevButton.className = "tableNav mr-2 px-3 py-1 text-gray-900 font-bold rounded-xl";
    prevButton.onclick = function() {
        if (currentPage > 1) {
            currentPage--;
            renderTable(currentPage);
            renderPagination();
        }
    };
    paginationDiv.appendChild(prevButton);

    var currentPageSpan = document.createElement("span");
    currentPageSpan.textContent = currentPage;
    currentPageSpan.className = "mr-2 px-3 py-1 text-gray-900 font-bold rounded-xl";
    paginationDiv.appendChild(currentPageSpan);

    var nextButton = document.createElement("button");
    nextButton.textContent = ">";
    nextButton.className = "mr-2 px-3 py-1 text-gray-900 font-bold rounded-xl";
    nextButton.onclick = function() {
        if (currentPage < totalPages) {
            currentPage++;
            renderTable(currentPage);
            renderPagination();
        }
    };
    paginationDiv.appendChild(nextButton);
}

renderTable(currentPage);
renderPagination();


