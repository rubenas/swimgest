import { loadModalSettings } from "./modules/modal.js";
import { loadNavbarSettings } from "./modules/navbar.js";
import { loadTabSettings } from "./modules/tab.js";
import { loadTooltipSettings } from "./modules/tooltips.js";
import { loadAjaxSettings } from "./modules/ajaxActions.js";
import { loadSelectRacesSettings } from "./modules/selectRaces.js";
import { loadCKEditorSettings } from "./modules/loadCKEditor.js";

window.addEventListener("load", onLoadPage, false);

/**
 * Initializes page components when the window loads.
 *
 * This function is called once the window has fully loaded, 
 * ensuring that all necessary components are set up before user interaction.
 */

function onLoadPage() {
    loadModalSettings(); // Modals
    loadTooltipSettings(); // Tooltips
    loadNavbarSettings(); // Navbars
    loadTabSettings(); // Tabs
    loadAjaxSettings(); // Ajax requests settings
    loadSelectRacesSettings(); // Select auto fill for race distance
    loadCKEditorSettings(); // CKEditor config
}


