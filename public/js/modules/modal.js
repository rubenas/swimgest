import { loadAjaxSettings } from "./ajaxActions.js";
import { ajaxGetRequest } from "./ajax.js";
import { loadSelectRacesSettings } from "./selectRaces.js";

//Función que carga los ajustes básicos para el uso de ventanas modales

export function loadModalSettings(element) {

    /*Recuperamos todos los botones o enlaces que abran ventanas modales (aquellos que cuentan con el atrbuto modal-target)
    y les asignamos su ventana de referencia */

    if(element == null) element = document;

    let modalButtons = element.querySelectorAll("*[modal-target]");

    for (let i = 0; i < modalButtons.length; i++) {

        let id = modalButtons[i].getAttribute("modal-target");

        modalButtons[i].addEventListener("click", function (event) {
            createModalWindow(event, id, modalButtons[i]);
        }, false);

    }

}

//Función que crea la ventana modal, si no existe
function createModalWindow(event, id, button) {

    event.preventDefault();
   
    if (!document.getElementById(id)) {//Si no existe la ventana modal, la solicitamos al servidor

        ajaxGetRequest(button, "html", createModalFromServerRequest);

    } else { //Si existe, simplemente la mostramos

        let modal = document.getElementById(id);

        showModal(modal);

    }
}

//Función que crea una ventana modal a partir de un objeto html devuelto por el servidor 

function createModalFromServerRequest(html) {

    let modal = html.querySelector(".modal");

    document.getElementById("modalWindows").appendChild(modal);

    showModal(modal);

    loadAjaxSettings(modal);

    loadSelectRacesSettings(modal);

}


//Función que cierra la ventana modal con efecto fadeout

export function closeModalWindow(modal) {

    modal.style.opacity = 0;

    setTimeout(function () {
        modal.style.display = "none";
    }, 400);
    
}

//Función que muestra una ventana modal existente y añade los listeners a los botones cerrar.

function showModal(modal) {

    modal.style.display = "flex";

    setTimeout(function () {
        modal.style.opacity = 1;
    }, 100);

    let closeButtons = modal.getElementsByClassName("close");

    for (let i = 0; i < closeButtons.length; i++) {

        closeButtons[i].addEventListener("click", function () {
            closeModalWindow(modal);
        }, false)
    }
    window.addEventListener("click", function (event) {

        if (event.target == modal) {
            closeModalWindow(modal);
        }

    }, false);

}