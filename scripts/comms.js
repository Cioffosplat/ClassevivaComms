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
var filters = {
    category: "",
    sort: "asc" // Default sort order
};

function renderTable(page) {
    var tableBody = document.getElementById("tableRows");
    tableBody.innerHTML = '';
    var filteredItems = filterItems(commsData.items);
    var startIndex = (page - 1) * itemsPerPage;
    var endIndex = Math.min(startIndex + itemsPerPage, filteredItems.length);

    for (var i = startIndex; i < endIndex; i++) {
        var item = filteredItems[i];
        var row = document.createElement("tr");

        var titleCell = document.createElement("td");
        titleCell.className = "px-6 py-4 whitespace-nowrap";
        var titleContent = document.createElement("div");
        titleContent.className = "text-sm text-gray-900";
        titleContent.textContent = item.cntTitle;
        titleCell.appendChild(titleContent);
        row.appendChild(titleCell);

        var categoryCell = document.createElement("td");
        categoryCell.className = "px-6 py-4 whitespace-nowrap";
        var categoryContent = document.createElement("div");
        categoryContent.className = "text-sm text-gray-900";
        categoryContent.textContent = item.cntCategory;
        categoryCell.appendChild(categoryContent);
        row.appendChild(categoryCell);

        var allegatoCell = document.createElement("td");
        allegatoCell.className = "px-6 py-4 whitespace-nowrap";
        var allegatoContent = document.createElement("div");
        allegatoContent.className = "text-sm text-gray-900";
        allegatoContent.textContent = item.dinsert_allegato;
        allegatoCell.appendChild(allegatoContent);
        row.appendChild(allegatoCell);

        var attachmentCell = document.createElement("td");
        attachmentCell.className = "px-6 py-4 whitespace-nowrap";
        attachmentCell.innerHTML = item.cntHasAttach ?
            `<span class="badge badge-success">Allegato</span>` :
            `<span class="badge badge-danger">Nessun Allegato</span>`;
        row.appendChild(attachmentCell);

        tableBody.appendChild(row);
    }
}

function renderPagination() {
    var totalPages = Math.ceil(filterItems(commsData.items).length / itemsPerPage);
    var paginationDiv = document.getElementById("pagination");
    paginationDiv.innerHTML = '';

    var prevButton = createPaginationButton("<", function () {
        if (currentPage > 1) {
            currentPage--;
            renderTable(currentPage);
            renderPagination();
        }
    });
    paginationDiv.appendChild(prevButton);

    var currentPageSpan = document.createElement("span");
    currentPageSpan.textContent = currentPage;
    paginationDiv.appendChild(currentPageSpan);

    var nextButton = createPaginationButton(">", function () {
        if (currentPage < totalPages) {
            currentPage++;
            renderTable(currentPage);
            renderPagination();
        }
    });
    paginationDiv.appendChild(nextButton);
}

function createPaginationButton(text, onclickFunction) {
    var button = document.createElement("button");
    button.textContent = text;
    button.className = "tableNav mr-2 px-3 py-1 text-gray-900 font-bold rounded-xl";
    button.onclick = onclickFunction;
    return button;
}

function applyFilters() {
    filters.category = document.getElementById("category").value;
    filters.sort = document.getElementById("sort").value;
    renderTable(1);
    renderPagination();
}

function filterItems(items) {
    var filteredItems = items.filter(function (item) {
        return (filters.category === "" || item.cntCategory === filters.category);
    });

    if (filters.sort === "asc") {
        filteredItems.sort(function (a, b) {
            return new Date(a.dinsert_allegato) - new Date(b.dinsert_allegato);
        });
    } else {
        filteredItems.sort(function (a, b) {
            return new Date(b.dinsert_allegato) - new Date(a.dinsert_allegato);
        });
    }

    return filteredItems;
}

// Initial rendering
renderTable(currentPage);
renderPagination();



