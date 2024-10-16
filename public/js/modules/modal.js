import { loadAjaxSettings } from "./ajaxActions.js";
import { ajaxGetRequest } from "./ajax.js";
import { loadSelectRacesSettings } from "./selectRaces.js";
import { loadCKEditorSettings } from "./loadCKEditor.js";

/**
 * Loads basic settings for modal windows by assigning click events to buttons or links 
 * that open modals (those with the attribute modal-target).
 *
 * @param {HTMLElement} [element=document] - The parent element to search for modal triggers. Defaults to the entire document if not provided.
 */

export function loadModalSettings(element) {
    if (element == null) element = document;

    let modalButtons = element.querySelectorAll("*[modal-target]");

    for (let i = 0; i < modalButtons.length; i++) {
        let id = modalButtons[i].getAttribute("modal-target");

        modalButtons[i].addEventListener("click", function (event) {
            createModalWindow(event, id, modalButtons[i]);
        }, false);
    }
}

/**
 * Creates a modal window if it does not exist. 
 * If it exists, it shows the modal.
 *
 * @param {Event} event - The click event that triggered the modal.
 * @param {string} id - The ID of the modal to create or show.
 * @param {HTMLElement} button - The button that triggered the modal action.
 */

function createModalWindow(event, id, button) {
    event.preventDefault();

    if (!document.getElementById(id)) { // If the modal does not exist, request it from the server
        ajaxGetRequest(button, "html", createModalFromServerRequest);
    } else { // If it exists, simply show it
        let modal = document.getElementById(id);
        showModal(modal);
    }
}

/**
 * Creates a modal window from an HTML object returned by the server.
 *
 * @param {HTMLElement} html - The HTML object returned from the server containing the modal.
 */

function createModalFromServerRequest(html) {
    let modal = html.querySelector(".modal");
    document.getElementById("modalWindows").appendChild(modal);
    showModal(modal);
    loadAjaxSettings(modal);
    loadSelectRacesSettings(modal);
    loadCKEditorSettings(modal);
}

/**
 * Closes a modal window with a fade-out effect.
 *
 * @param {HTMLElement} modal - The modal window to be closed.
 */

export function closeModalWindow(modal) {
    modal.style.opacity = 0;

    setTimeout(function () {
        modal.style.display = "none";
    }, 400);
}

/**
 * Shows an existing modal window and adds event listeners to close buttons.
 *
 * @param {HTMLElement} modal - The modal window to be shown.
 */

function showModal(modal) {
    modal.style.display = "flex";

    setTimeout(function () {
        modal.style.opacity = 1;
    }, 100);

    let closeButtons = modal.getElementsByClassName("close");

    for (let i = 0; i < closeButtons.length; i++) {
        closeButtons[i].addEventListener("click", function () {
            closeModalWindow(modal);
        }, false);
    }

    window.addEventListener("click", function (event) {
        if (event.target == modal) {
            closeModalWindow(modal);
        }
    }, false);
}
