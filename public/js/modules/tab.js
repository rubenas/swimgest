import { loadModalSettings } from './modal.js';
import { ajaxGetRequest } from './ajax.js';
import { loadSelectRacesSettings } from './selectRaces.js';
import { loadAjaxSettings } from './ajaxActions.js';

/**
 * Initializes tab functionality by adding click event listeners to tab links.
 *
 * @param {HTMLElement} [element=document] - The parent element containing the tab links.
 */

export function loadTabSettings(element) {
    if (element == null) element = document;

    let tabLinks = element.querySelectorAll("*[tab-target]");

    for (let i = 0; i < tabLinks.length; i++) {
        let id = tabLinks[i].getAttribute("tab-target");
        tabLinks[i].addEventListener("click", function (event) {
            createTab(event, id, tabLinks[i]);
        }, false);
    }
}

/**
 * Creates a tab by removing the existing one (if it exists) and fetching a new one from the server.
 *
 * @param {Event} event - The click event triggered by the tab link.
 * @param {string} id - The ID of the tab to be created.
 * @param {HTMLElement} tabLink - The tab link that was clicked.
 */

function createTab(event, id, tabLink) {
    event.preventDefault();

    if (document.getElementById(id)) document.getElementById(id).remove();

    ajaxGetRequest(tabLink, "html", createTabFromServerRequest);
}

/**
 * Handles the response from the server to create and display a new tab.
 *
 * @param {HTMLElement} html - The HTML response from the server containing the tab structure.
 */

function createTabFromServerRequest(html) {
    const tab = html.querySelector(".tab");

    document.getElementById("tab-container").appendChild(tab);

    showTab(tab.id);

    // Reload event listeners for newly created buttons
    loadModalSettings(tab);
    loadSelectRacesSettings(tab);
    loadTabSettings(tab);
    loadAjaxSettings(tab);
}

/**
 * Displays the specified tab and updates the corresponding tab link to appear active.
 *
 * @param {string} id - The ID of the tab to be shown.
 */

function showTab(id) {
    let tabs = document.getElementsByClassName("tab");
    for (let i = 0; i < tabs.length; i++) {
        if (tabs[i].id == id) {
            tabs[i].classList.add("active");
            tabs[i].classList.remove("inactive");
        } else {
            tabs[i].classList.add("inactive");
            tabs[i].classList.remove("active");
        }
    }

    activateTabLink(id);

    window.history.replaceState('', 'SwimGest', './'+id);
}

/**
 * Activates the corresponding tab link based on the selected tab.
 *
 * @param {string} tabTarget - The ID of the target tab.
 */

export function activateTabLink(tabTarget) {
    let tabLinks = document.querySelectorAll("*[tab-target]");

    for (let i = 0; i < tabLinks.length; i++) {
        if (tabLinks[i].getAttribute("tab-target") == tabTarget) {
            tabLinks[i].parentNode.classList.add("active");
            tabLinks[i].parentNode.classList.remove("inactive");
        } else {
            tabLinks[i].parentNode.classList.add("inactive");
            tabLinks[i].parentNode.classList.remove("active");
        }
    }
}
