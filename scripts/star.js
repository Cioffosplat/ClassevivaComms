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
    document.getElementById('paintbrushButton').src = '../resources/images/paintbrush/paintbrush' + theme + '.png';
    document.getElementById('searchInput').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
    document.getElementById('tableBack').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('category').style.backgroundColor = 'var(--' + theme + '-accent-color)';
    document.getElementById('tableRows').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('communicationBannerStar').style.backgroundColor = 'var(--' + theme + '-accent2-color)';
    document.getElementById('closeCommunicationInfoStar').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
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
};
var favorites = [];
var globalData;

function fetchData() {
    var sessionUserId = userId;
    var formData = new FormData();
    formData.append('sessionUserId',sessionUserId);

    return fetch('http://192.168.248.35/projects/ClassevivaComms/Fat3/user-stars', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Errore durante il recupero dei dati dei likes');
            }
            return response.json();
        })
        .then(data => {
            // Trasforma i dati ricevuti nel formato desiderato
            var formattedData = data.map(item => ({
                pubId: item.id,
                cntTitle: item.name,
                cntCategory: item.category
            }));
            globalData = formattedData;
            return formattedData;
        })
        .catch(error => {
            console.error(error.message);
        });
}

function renderTable(page) {
    var tableBody = document.getElementById("tableRows");
    tableBody.innerHTML = '';
    var filteredItems = filterItems(globalData);
    console.log(filteredItems);
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
        titleText.addEventListener("click", createShowCommunicationHandler(item.pubId)); // Questa è la modifica qui
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

        tableBody.appendChild(row);
    }
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

    return filteredItems;
}

function renderPagination() {
    var filteredItems = filterItems(globalData);
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

//Section for adding favoutires
function toggleFavorite(icon) {
    var index = parseInt(icon.dataset.index);
    var item = globalData[index];
    var circolareId = item.pubId;

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
    fetch('http://192.168.248.35/projects/ClassevivaComms/Fat3/save-favorite', {
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

//Search Section
document.getElementById("searchInput").addEventListener("input", function() {
    applyFilters();
});

//Showing Communication Info
function createShowCommunicationHandler(pubId) {
    return function() {
        var infoDiv = document.getElementById("communicationInfo");
        if (infoDiv) {
            var titleElement = document.getElementById("communicationTitle");
            var categoryElement = document.getElementById("communicationCategory");
            var communicationData = getCommunicationData(pubId);
            if (communicationData) {
                titleElement.textContent = communicationData.cntTitle;
                categoryElement.textContent = communicationData.cntCategory;
            } else {
                console.error("Dati della comunicazione non disponibili.");
                return;
            }
            infoDiv.classList.remove("hidden");
        } else {
            console.error("Div delle informazioni della comunicazione non trovato.");
        }
    };
}


function getCommunicationData(pubId) {
    return globalData.find(item => item.pubId === pubId);
}

// Close Communication Info
document.getElementById("closeCommunicationInfoStar").addEventListener("click", function() {
    var infoDiv = document.getElementById("communicationInfo");
    if (infoDiv) {
        infoDiv.classList.add("hidden");
    } else {
        console.error("Div delle informazioni della comunicazione non trovato.");
    }
});
async function fetchDataAndRenderTable() {
    try {
        const data = await fetchData();
        renderTable(currentPage);
        renderPagination();
        console.log(globalData);
    } catch (error) {
        console.error(error.message);
    }
}

fetchDataAndRenderTable();

//Section to update the profile pic
function updateProfilePic() {
    const sessionUserId = userId;
    const formData = new FormData();
    formData.append('sessionUserId', sessionUserId);
    fetch('http://192.168.248.35/projects/ClassevivaComms/Fat3/profile-pic', {
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
