import { loadModalSettings } from "./modules/modal.js";
import { loadNavbarSettings } from "./modules/navbar.js";
import { loadTabSettings } from "./modules/tab.js";
import { loadTooltipSettings } from "./modules/tooltips.js";
import { loadAjaxSettings } from "./modules/ajaxActions.js";
import { loadSelectRacesSettings } from "./modules/selectRaces.js";
import { loadCKEditorSettings } from "./modules/loadCKEditor.js";

window.addEventListener("load", onLoadPage, false);

//Load add needed components

function onLoadPage() {
    loadModalSettings(); //Modals
    loadTooltipSettings(); //Tooltips
    loadNavbarSettings(); //Navbars
    loadTabSettings(); //Tabs
    loadAjaxSettings(); //Ajax requests settings
    loadSelectRacesSettings(); //Select auto fill for race distance
    loadCKEditorSettings(); //CKEditor config
}

