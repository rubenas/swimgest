
import {checkRequiredElements} from "./modules/functions.js";
import { modalMsg } from "./modules/modalMsg.js";

window.addEventListener("load",loadQuestionaryCheckerSettings);

function loadQuestionaryCheckerSettings() {
    
    const inputs = document.querySelectorAll("[name^='answer['][type='checkbox']");

    for (let input of inputs){

        if(input.checked) toggleRequiredInputs(input,inputs);

        input.addEventListener("change",function(){toggleRequiredInputs(input,inputs)},false);
    }

    const form = document.querySelector["[id^='questionary-']"];

    if (form != null) {

        const button = form.querySelector(["type='submit'"]);

        button.addEventListener("click",function(event){

            event.preventDefault();
            if(checkRequiredElements(form)) form.submit();
            else modalMsg("Existen campos obligatorios por cubrir");
        });
    }
}

//Removes or ad required attribute if some option is checked 

function toggleRequiredInputs(input,inputs){

    for (let checkbox of inputs) {
        
        if(checkbox.getAttribute("questionId") == input.getAttribute("questionId")) {

            if(input.checked) checkbox.removeAttribute("required");

            else if (!isSomeChecked(input,inputs)) checkbox.setAttribute("required",true);
        }
    }
}

//Checks if at least an option is checked

function isSomeChecked(input,inputs){

    for (let checkbox of inputs) {

        if(checkbox.getAttribute("questionId") == input.getAttribute("questionId") && checkbox.checked) return true;
    }

    return false;
}