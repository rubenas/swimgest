import { hideLoading, showLoading } from "./spinner.js";


/* Functions to make a get and post requests to server after pressing a button.
    Result format can be text, json or html
    actionAfter is a function called after receiving response
*/

export function ajaxGetRequest(button, resultFormat, actionAfter, dataToAction=null) {

    showLoading(document);

    const data = JSON.parse(button.getAttribute("ajax-request"));
    
    fetch(data.url)
        .then(response => response.text())
        .then(text => formatResult(text, resultFormat))
        .then(result => {
            hideLoading(document);
            actionAfter(result,dataToAction);
        })
        .catch(error => {
            hideLoading(document);
            document.querySelector(".error").innerText = "Ha habido un error " + error;
        });
}

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


function formatResult(text, format) {

    if (format == "html") {

        let parser = new DOMParser();

        return parser.parseFromString(text, 'text/html');

    } else {

        return text;
    }

}