/**
 * Initializes tooltip functionality by adding mouseover event listeners to tooltip elements.
 */

export function loadTooltipSettings() {
    let toolTips = document.getElementsByClassName("tooltip");
    for (let i = 0; i < toolTips.length; i++) {
        toolTips[i].addEventListener("mouseover", function () {
            setTooltipPosition(toolTips[i]);
        }, false);
    }
}

/**
 * Sets the position of the tooltip based on available screen space.
 * If there is not enough space above the tooltip, it will be positioned below the element.
 *
 * @param {HTMLElement} toolTip - The tooltip element whose position is to be adjusted.
 */

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
