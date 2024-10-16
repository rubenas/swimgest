import { formatResult } from "./ajax.js";
import { showLoading, hideLoading } from "./spinner.js";

/**
 * Displays a modal message by sending a POST request to fetch the modal content.
 *
 * @param {string} msg - The message to be sent in the body of the request.
 */

export function modalMsg(msg) {
    showLoading(document);

    fetch('base/showMessage/v', {
        method: 'POST',
        body: msg
    })
        .then(response => response.text())
        .then(text => formatResult(text, 'html'))
        .then(result => {
            hideLoading(document);
            appendModal(result);
        })
        .catch(error => {
            hideLoading(document);
            document.querySelector(".error").innerText = "Ha habido un error " + error;
        });
}

/**
 * Appends a modal element to the DOM and displays it.
 *
 * @param {HTMLElement} html - The HTML content returned from the server, containing the modal.
 */

function appendModal(html) {
    const modal = html.querySelector(".modal");

    document.getElementById("modalWindows").appendChild(modal);

    modal.style.display = "flex";

    setTimeout(function () {
        modal.style.opacity = 1;
    }, 100);

    let closeButtons = modal.getElementsByClassName("close");

    for (let i = 0; i < closeButtons.length; i++) {
        closeButtons[i].addEventListener("click", function () {
            modal.remove();
        }, false);
    }

    window.addEventListener("click", function (event) {
        if (event.target == modal) {
            modal.remove();
        }
    }, false);
}
