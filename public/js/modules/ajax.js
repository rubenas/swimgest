import { hideLoading, showLoading } from "./spinner.js";

/**
 * Makes a GET request to the server after pressing a button.
 *
 * @param {HTMLElement} button - The button element that triggers the request.
 * @param {string} resultFormat - The format of the response (e.g., "text", "json", or "html").
 * @param {function} actionAfter - A callback function called after receiving the response.
 * @param {Object|null} [dataToAction=null] - Additional data to pass to the actionAfter function.
 */

export function ajaxGetRequest(button, resultFormat, actionAfter, dataToAction = null) {
    showLoading(document);

    const data = JSON.parse(button.getAttribute("ajax-request"));

    fetch(data.url)
        .then(response => response.text())
        .then(text => formatResult(text, resultFormat))
        .then(result => {
            hideLoading(document);
            actionAfter(result, dataToAction);
        })
        .catch(error => {
            hideLoading(document);
            document.querySelector(".error").innerText = "Ha habido un error " + error;
        });
}

/**
 * Makes a POST request to the server after submitting a form.
 *
 * @param {HTMLFormElement} form - The form element to be submitted.
 * @param {HTMLElement} button - The button element that triggers the request.
 * @param {string} resultFormat - The format of the response (e.g., "text", "json", or "html").
 * @param {function} actionAfter - A callback function called after receiving the response.
 * @param {Object} dataToAction - Additional data to pass to the actionAfter function.
 */

export function ajaxPostRequest(form, button, resultFormat, actionAfter, dataToAction) {
    showLoading(document);

    const data = JSON.parse(button.getAttribute("ajax-request"));
    const content = new FormData(form);

    fetch(data.url, {
        method: 'POST',
        body: content
    })
        .then(response => response.text())
        .then(text => formatResult(text, resultFormat))
        .then(result => {
            hideLoading(document);
            form.reset();
            actionAfter(result, dataToAction);
        })
        .catch(error => {
            hideLoading(document);
            document.querySelector(".error").innerText = "Ha habido un error " + error;
        });
}

/**
 * Formats the result based on the specified format.
 *
 * @param {string} text - The response text from the server.
 * @param {string} format - The desired format of the response (e.g., "html").
 * @returns {Document|string} The formatted result, which can be an HTML document or plain text.
 */

export function formatResult(text, format) {
    if (format == "html") {
        let parser = new DOMParser();
        return parser.parseFromString(text, 'text/html');
    } else {
        return text;
    }
}
