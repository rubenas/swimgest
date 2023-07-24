/*Este script actualiza las opciones del select que lista las distancias en pruebas de piscina
En función del estilo seleccionado en selectStyle, se cargan las distancias disponibles*/

import { addOption, clearOptions } from "./functions.js";

export function loadSelectRacesSettings(element) {

    if (element == null) element = document;

    let formsRace = document.getElementsByClassName("form-race");

    for (let form of formsRace) {

        const selectStyle = form.querySelector(".style");
        const selectDistance = form.querySelector(".distance");
        const selectPool = form.querySelector(".pool");

        if (selectStyle != null) {

            selectStyle.addEventListener("change", function () {
                selectDistanceUpdate(selectStyle, selectDistance, selectPool);
            }, false);

        }

        if (selectPool != null) {

            selectPool.addEventListener("change", function () {
                selectDistanceUpdate(selectStyle, selectDistance, selectPool);
            }, false);
        }

    }

}

function selectDistanceUpdate(selectStyle, selectDistance, selectPool) {
    switch (selectStyle.options[selectStyle.selectedIndex].value) {
        case "freestyle":
            clearOptions(selectDistance);
            addOption(selectDistance, "50m", "50m", "50m", "50m", true);
            addOption(selectDistance, "100m", "100m", "100m", "100m", false);
            addOption(selectDistance, "200m", "200m", "200m", "200m", false);
            addOption(selectDistance, "400m", "400m", "400m", "400m", false);
            addOption(selectDistance, "800m", "800m", "800m", "800m", false);
            addOption(selectDistance, "1500m", "1500m", "1500m", "1500m", false);
            addOption(selectDistance, "4x50m", "4x50m", "4x50m", "4x50m", false);
            addOption(selectDistance, "4x100m", "4x100m", "4x100m", "4x100m", false);
            break;

        case "backstroke":
        case "breaststroke":
        case "butterfly":
            clearOptions(selectDistance);
            addOption(selectDistance, "50m", "50m", "50m", "50m", false);
            addOption(selectDistance, "100m", "100m", "100m", "100m", false);
            addOption(selectDistance, "200m", "200m", "200m", "200m", false);
            break;

        case "medley":
            clearOptions(selectDistance);
            if (selectPool[selectPool.selectedIndex].value == "25m") {
                addOption(selectDistance, "100m", "100m", "100m", "100m", false);
            }
            addOption(selectDistance, "200m", "200m", "200m", "200m", false);
            addOption(selectDistance, "400m", "400m", "400m", "400m", false);
            addOption(selectDistance, "4x50m", "4x50m", "4x50m", "4x50m", false);
            addOption(selectDistance, "4x100m", "4x100m", "4x100m", "4x100m", false);
            break;

    }
}