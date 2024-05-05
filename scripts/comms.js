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
    document.getElementById('searchInput').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
    document.getElementById('tableBack').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('category').style.backgroundColor = 'var(--' + theme + '-accent-color)';
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

// Table Rendering Script
var itemsPerPage = 10;
var currentPage = 1;
var filters = {
    category: "",
    sort: "desc"
};
var favorites = [];

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
        titleCell.className = "px-4 py-4 whitespace-nowrap";
        var titleContent = document.createElement("div");
        titleContent.className = "text-sm text-gray-900 flex items-center";

        var favoriteIcon = document.createElementNS("http://www.w3.org/2000/svg", "svg");
        favoriteIcon.setAttribute("class", "w-6 h-6 fill-current text-yellow-500 favorite-icon");
        favoriteIcon.setAttribute("xmlns", "http://www.w3.org/2000/svg");
        favoriteIcon.setAttribute("viewBox", "0 0 24 24");
        favoriteIcon.setAttribute("stroke-width", "1.5");
        favoriteIcon.setAttribute("stroke", "currentColor");

        if (favorites[i]) {
            favoriteIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />';
        } else {
            favoriteIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" fill="none"/>';
        }

        favoriteIcon.dataset.index = i;

        favoriteIcon.addEventListener("click", function() {
            toggleFavorite(this);
        });

        titleContent.appendChild(favoriteIcon);
        var titleText = document.createElement("span");
        titleText.textContent = item.cntTitle;
        titleText.addEventListener("click", createShowCommunicationHandler(i));
        titleContent.appendChild(titleText);

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
            `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Allegato</span>` :
            `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nessun Allegato</span>`;
        row.appendChild(attachmentCell);

        tableBody.appendChild(row);
    }
    var ascIcon = document.getElementById("ascIcon");
    var descIcon = document.getElementById("descIcon");
    if (filters.sort === "desc") {
        ascIcon.style.display = "none";
        descIcon.style.display = "inline";
    } else {
        ascIcon.style.display = "inline";
        descIcon.style.display = "none";
    }
}

function renderPagination() {
    var filteredItems = filterItems(commsData.items);
    var totalPages = Math.ceil(filteredItems.length / itemsPerPage);
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
    currentPageSpan.textContent = currentPage + "/" + totalPages;
    currentPageSpan.className = "mx-4 px-3 py-1 text-gray-900 font-bold rounded-xl";
    paginationDiv.appendChild(currentPageSpan);

    var nextButton = createPaginationButton(">", function () {
        if (currentPage < totalPages) {
            currentPage++;
            renderTable(currentPage);
            renderPagination();
        }
    });
    paginationDiv.appendChild(nextButton);

    paginationDiv.style.textAlign = "center";
}


function createPaginationButton(text, onclickFunction) {
    var button = document.createElement("button");
    button.textContent = text;
    button.className = "tableNav mr-2 px-3 py-1 text-gray-900 font-bold rounded-xl";
    button.onclick = onclickFunction;
    return button;
}
//Filters Section
function applyFilters() {
    filters.category = document.getElementById("category").value;
    renderTable(1);
    renderPagination();
}

function filterItems(items) {
    var filteredItems = items.filter(function (item) {
        var categoryMatch = filters.category === "" || item.cntCategory === filters.category;
        var searchMatch = document.getElementById("searchInput").value.trim() === "" ||
            item.cntTitle.toLowerCase().includes(document.getElementById("searchInput").value.trim().toLowerCase());
        return categoryMatch && searchMatch;
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

//Section for adding favoutires
function toggleFavorite(icon) {
    var index = parseInt(icon.dataset.index);
    var item = commsData.items[index];
    var circolareId = item.pubId;
    var allegati = item.attachments;
    for (var i = 0; i < allegati.length; i++) {
        if (allegati[i].fileName.includes("Circolare")) {
            circolareId = allegati[i].attachNum;
            break;
        }
    }
    console.log("ID della circolare:", circolareId);
    console.log(typeof circolareId);
    favorites[index] = !favorites[index];
    renderTable(currentPage);

    var sessionUserId = userId;
    console.log(sessionUserId);
    console.log(typeof userId);
    saveFavoriteToDatabase(circolareId, sessionUserId);
}

function saveFavoriteToDatabase(circolareId, sessionUserId) {
    var formData = new FormData();
    formData.append('circolareId',circolareId);
    formData.append('sessionUserId',sessionUserId);
    fetch('http://192.168.1.177/projects/ClassevivaComms/Fat3/save-favorite', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore durante il salvataggio dei dati nel database');
            }
            return response.json();
        })
        .then(data => {
            console.log("Dati salvati nel database:", data);
        })
        .catch(error => {
            console.error(error.message);
        });
}

//Aggiunta della comunicazione al database
function saveCommunicationToDatabase(index) {
    return function() {
        var item = commsData.items[index];
        var pubId = item.pubId;
        var cntCategory = item.cntCategory;
        var cntTitle = item.cntTitle;
        var cntValidFrom = item.cntValidFrom;
        console.log(pubId);
        console.log(cntCategory);
        console.log(cntTitle);
        console.log(cntValidFrom);
        addCommunication(pubId, cntCategory, cntTitle, cntValidFrom);
    }
}

function addCommunication(pubId, cntCategory, cntTitle, cntValidFrom) {
    var formData = new FormData();
    formData.append('userId',userId);
    formData.append('pubId', pubId);
    formData.append('cntCategory', cntCategory);
    formData.append('cntTitle', cntTitle);
    formData.append('cntValidFrom', cntValidFrom);


    fetch('http://192.168.1.177/projects/ClassevivaComms/Fat3/insert-notice', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore durante l\'aggiunta della comunicazione');
            }
            return response.json();
        })
        .then(data => {
            console.log("Comunicazione aggiunta con successo:", data);
        })
        .catch(error => {
            console.error(error.message);
        });
}

document.getElementById("sortToggle").addEventListener("click", function() {
    var ascIcon = document.getElementById("ascIcon");
    var descIcon = document.getElementById("descIcon");

    if (ascIcon.style.display === "none") {
        ascIcon.style.display = "inline";
        descIcon.style.display = "none";
        filters.sort = "asc";
    } else {
        ascIcon.style.display = "none";
        descIcon.style.display = "inline";
        filters.sort = "desc";
    }

    applyFilters();
});

//Search Section
document.getElementById("searchInput").addEventListener("input", function() {
    applyFilters();
});

//Showing Communication Info
function createShowCommunicationHandler(index) {
    return function() {
        saveCommunicationToDatabase(index)();
        var item = commsData.items[index];
        showCommunicationInfo(item.cntTitle, item.cntCategory, item.cntValidFrom, item.attachments);
    }
}
function showCommunicationInfo(title, category, validFrom, attachments) {
    var infoDiv = document.getElementById("communicationInfo");
    var titleElement = document.getElementById("communicationTitle");
    var dateElement = document.getElementById("communicationDate");
    var attachmentsElement = document.getElementById("communicationAttachments");

    titleElement.textContent = title;
    dateElement.textContent = "Data: " + validFrom;

    var attachmentsList = document.createElement("ul");
    attachments.forEach(function(attachment) {
        var attachmentItem = document.createElement("li");
        var attachmentLink = document.createElement("a");
        attachmentLink.textContent = attachment.fileName;
        attachmentLink.href = "#";
        attachmentItem.appendChild(attachmentLink);
        attachmentsList.appendChild(attachmentItem);
    });
    attachmentsElement.innerHTML = "";
    attachmentsElement.appendChild(attachmentsList);
    infoDiv.classList.remove("hidden");
}
// Close Communication Info
document.getElementById("closeCommunicationInfo").addEventListener("click", function() {
    var infoDiv = document.getElementById("communicationInfo");
    if (infoDiv) {
        infoDiv.classList.add("hidden");
    } else {
        console.error("Div delle informazioni della comunicazione non trovato.");
    }
});

// Initial rendering of the table
renderTable(currentPage);
renderPagination();
