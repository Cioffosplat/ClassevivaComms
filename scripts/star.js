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
    document.getElementById('closeCommunicationInfoStar').setAttribute("stroke",'var(--' + theme + '-text-color)');
    document.getElementById('removeStarButton').style.backgroundColor = 'var(--' + theme + '-secondary-color)';
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
var globalData;

function fetchData() {
    var sessionUserId = userId;
    var formData = new FormData();
    formData.append('sessionUserId',sessionUserId);

    return fetch('http://192.168.101.35/projects/ClassevivaComms/Fat3/user-stars', {
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
        
        var titleText = document.createElement("span");
        titleText.textContent = item.cntTitle;
        titleText.addEventListener("click", createShowCommunicationHandler(item.pubId));
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


//Search Section
document.getElementById("searchInput").addEventListener("input", function() {
    applyFilters();
});

//Showing Communication Info
function createShowCommunicationHandler(pubId) {
    console.log(commsData)
    return function() {
        var item = commsData.items.find(function(item) {
            return item.pubId === pubId;
        });

        if (item) {
            showCommunicationInfo(item.pubId,item.cntTitle, item.cntCategory, item.attachments);
        } else {
            console.error("Comunicazione non trovata con l'ID:", pubId);
        }
    }
}
function showSuccessBanner() {
    var successBanner = document.getElementById("successBanner");
    successBanner.classList.remove("hidden");

    setTimeout(function() {
        hideSuccessBanner();
    }, 3000);
}

function hideSuccessBanner() {
    var successBanner = document.getElementById("successBanner");
    successBanner.classList.add("hidden");
}

function showCommunicationInfo(pubId,title, category, attachments) {
    var infoDiv = document.getElementById("communicationInfo");
    var titleElement = document.getElementById("communicationTitle");
    var attachmentsElement = document.getElementById("communicationAttachments");

    document.getElementById("removeStarButton").addEventListener("click", function() {
        removeFavoriteToDatabase(pubId, userId);
        fetchDataAndRenderTable();
        document.getElementById("communicationInfo").classList.remove("hidden");
        showSuccessBanner();
    });

    titleElement.textContent = title;

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

//Section for adding favoutires
function removeFavoriteToDatabase(circolareId, sessionUserId) {
    console.log(circolareId);
    console.log(sessionUserId);
    var formData = new FormData();
    formData.append('circolareId',circolareId);
    formData.append('sessionUserId',sessionUserId);
    fetch('http://192.168.101.35/projects/ClassevivaComms/Fat3/remove-favorite', {
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

fetchDataAndRenderTable();

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
