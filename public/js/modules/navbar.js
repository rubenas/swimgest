/**
 * Initializes the settings for the navbar togglers.
 * Adds click event listeners to elements that control the visibility of the navigation menus.
 */

export function loadNavbarSettings() {
    let navbarTogglers = document.querySelectorAll("*[nav-target]");
    for (let i = 0; i < navbarTogglers.length; i++) {
        let id = navbarTogglers[i].getAttribute("nav-target");
        let display = false;
        navbarTogglers[i].addEventListener("click", function () {
            display = toggleNav(id, display);
        }, false);
    }
}

/**
 * Toggles the display state of the navigation menu.
 *
 * @param {string} id - The ID of the navigation element to toggle.
 * @param {boolean} display - The current display state of the navigation element.
 * @returns {boolean} - The updated display state of the navigation element.
 */

function toggleNav(id, display) {
    let nav = document.getElementById(id);
    if (!display) {
        nav.style.display = "flex";
        display = true;
    } else {
        nav.style.display = "none";
        display = false;
    }
    return display;
}
