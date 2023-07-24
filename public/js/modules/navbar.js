/*Gestión del boón hamburguer del menú de navegación*/

export function loadNavbarSettings() {
    let navbarTogglers = document.querySelectorAll("*[nav-target]");
    for (let i = 0; i < navbarTogglers.length; i++) {
        let id = navbarTogglers[i].getAttribute("nav-target");
        let display = false;
        navbarTogglers[i].addEventListener("click",function(){display = toggleNav(id,display)},false);
    }
}

function toggleNav(id,display) {
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