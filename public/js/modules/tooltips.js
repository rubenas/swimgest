/*Modificación de la posición de las tooltips cuando no caben en pantalla
Por defecto, se muestran sobre el elemento con clase tooltip. Si no caben en la parte superior,
se muestran debajo*/

export function loadTooltipSettings() {
    let toolTips = document.getElementsByClassName("tooltip");
    for (let i = 0; i < toolTips.length; i++) {
        toolTips[i].addEventListener("mouseover", function () { setTooltipPosition(toolTips[i]); }, false);
    }
}

function setTooltipPosition(toolTip) {

    if (toolTip != undefined && toolTip != null) {

        let tooltipText = toolTip.querySelector(".tooltip-text");
        let topSpace = tooltipText.parentNode.getBoundingClientRect().top;
        let rightSpace = window.innerWidth - tooltipText.parentNode.getBoundingClientRect().right;

        if (topSpace < tooltipText.offsetHeight) {

            tooltipText.classList.add("bottom");

        } else {

            tooltipText.classList.remove("bottom");
        }
        
        if (rightSpace < tooltipText.offsetWidth) {

            tooltipText.classList.add("left");
        } else {

            tooltipText.classList.remove("left");
        }
    }

}