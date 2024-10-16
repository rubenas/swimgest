
import { checkRequiredElements } from "./modules/functions.js";
import { modalMsg } from "./modules/modalMsg.js";

window.addEventListener("load", loadQuestionaryCheckerSettings);

/**
 * Initializes the questionnaire checker settings when the window loads.
 *
 * This function adds event listeners to checkbox inputs to toggle the 'required' 
 * attribute based on whether any checkboxes are checked. It also handles form 
 * submission, checking for required fields before allowing submission.
 */

function loadQuestionaryCheckerSettings() {
    const inputs = document.querySelectorAll("[name^='answer['][type='checkbox']");

    for (let input of inputs) {
        if (input.checked) toggleRequiredInputs(input, inputs);

        input.addEventListener("change", function () {
            toggleRequiredInputs(input, inputs);
        }, false);
    }

    const form = document.querySelector("[id^='questionary-']");

    if (form != null) {
        const button = form.querySelector("[type='submit']");

        button.addEventListener("click", function (event) {
            event.preventDefault();
            if (checkRequiredElements(form)) form.submit();
            else modalMsg("Existen campos obligatorios por cubrir");
        });
    }
}

/**
 * Removes or adds the 'required' attribute to checkbox inputs 
 * based on whether some option is checked.
 *
 * @param {HTMLInputElement} input - The checkbox input that triggered the change.
 * @param {NodeList} inputs - A NodeList of all checkbox inputs for the current question.
 */

function toggleRequiredInputs(input, inputs) {
    for (let checkbox of inputs) {
        if (checkbox.getAttribute("questionId") == input.getAttribute("questionId")) {
            if (input.checked) checkbox.removeAttribute("required");
            else if (!isSomeChecked(input, inputs)) checkbox.setAttribute("required", true);
        }
    }
}

/**
 * Checks if at least one checkbox option is checked.
 *
 * @param {HTMLInputElement} input - The checkbox input that triggered the check.
 * @param {NodeList} inputs - A NodeList of all checkbox inputs for the current question.
 * @returns {boolean} True if at least one checkbox is checked, false otherwise.
 */

function isSomeChecked(input, inputs) {
    for (let checkbox of inputs) {
        if (checkbox.getAttribute("questionId") == input.getAttribute("questionId") && checkbox.checked) {
            return true;
        }
    }
    return false;
}
