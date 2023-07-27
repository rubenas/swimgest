
import {checkRequiredElements} from "./modules/functions.js";

window.addEventListener("load",loadQuestionaryCheckerSettings);

function loadQuestionaryCheckerSettings() {
    
    const inputs = document.querySelectorAll("[name^='answer['][type='checkbox']");

    for (let input of inputs){
        input.addEventListener("change",function(){toggleRequiredinputs(input,inputs)},false);
    }

    const form = document.querySelector["[id^='questionary-']"];

    if (form != null) {

        const button = form.querySelector(["type='submit'"]);

        button.addEventListener("click",function(event){

            event.preventDefault();
            if(checkRequiredElements(form)) form.submit();
            else document.querySelector(".error").textContent = "Existen campos obligatorios por cubrir";
        });
    }
}

//Removes or ad required attribute if some option is checked 

function toggleRequiredinputs(input,inputs){

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