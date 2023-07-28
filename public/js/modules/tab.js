/*Muestra y oculta las tabs en función de la ocpión seleccionada en un nav-tab*/

import { loadModalSettings } from './modal.js';
import { ajaxGetRequest } from './ajax.js';
import { loadSelectRacesSettings } from './selectRaces.js';
import { loadAjaxSettings } from './ajaxActions.js';

export function loadTabSettings(element) {

    if(element == null) element = document;
    
    let tabLinks = element.querySelectorAll("*[tab-target]");

    for (let i = 0; i < tabLinks.length; i++) {

        let id = tabLinks[i].getAttribute("tab-target");

        tabLinks[i].addEventListener("click", function (event) { 
            createTab(event, id, tabLinks[i]); }, false);

    }
}

//Función que crea la tab, eliminándola previamente si existe.
function createTab(event, id, tabLink) {

    event.preventDefault();

    if(document.getElementById(id)) document.getElementById(id).remove();

    ajaxGetRequest(tabLink, "html", createTabFromServerRequest);

}

function createTabFromServerRequest(html) {

    const tab = html.querySelector(".tab");

    document.getElementById("tab-container").appendChild(tab);
                
    showTab(tab.id);

    //Es necesario volver a cargar los event listeners de los nuevos botones creados
    loadModalSettings(tab);
    loadSelectRacesSettings(tab);
    loadTabSettings(tab);
    loadAjaxSettings(tab);
}

/*Muestra la tab y actualiza el tab-link para que aparezca activo*/
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
   
}

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