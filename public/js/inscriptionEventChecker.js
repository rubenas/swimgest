window.addEventListener("load", loadInscriptionEventCheckerSettings);

/**
 * Initializes the inscription event checker settings when the window loads.
 *
 * This function sets up event listeners for each event checkbox. It activates or 
 * deactivates the corresponding question sections based on the checked status of the checkbox.
 */

function loadInscriptionEventCheckerSettings() {
    const events = document.querySelectorAll("[id^='event-']");

    for (let event of events) {
        const checkboxEvent = event.querySelector("[name^='event[']");

        if (checkboxEvent.checked) activateQuestions(event);
        else deactivateQuestions(event);

        checkboxEvent.addEventListener("change", function() {
            if (checkboxEvent.checked) activateQuestions(event);
            else deactivateQuestions(event);
        });
    }
}

/**
 * Activates the questions section and sets the required attribute for input options.
 *
 * @param {HTMLElement} event - The event element that contains questions and sub-events.
 */

function activateQuestions(event) {
    const sectionQuestions = event.querySelector("[id^='questions-']");
    const sectionSubEvents = event.querySelector("[id^='subEvents-']");
    const questions = sectionQuestions.querySelectorAll("[id^='question-']");

    for (let question of questions) {
        const options = question.querySelectorAll("input");
        for (let option of options) {
            option.required = true;
        }
    }

    sectionQuestions.style.display = "block";
    sectionSubEvents.style.display = "block";
}

/**
 * Deactivates the questions section, unchecking and removing the required attribute from options.
 *
 * @param {HTMLElement} event - The event element that contains questions and sub-events.
 */

function deactivateQuestions(event) {
    const sectionQuestions = event.querySelector("[id^='questions-']");
    const sectionSubEvents = event.querySelector("[id^='subEvents-']");
    const questions = sectionQuestions.querySelectorAll("[id^='question-']");

    for (let question of questions) {
        const options = question.querySelectorAll("input");
        for (let option of options) {
            option.checked = false;
            option.required = false;
            if (option.type === 'text') option.value = "";
        }
    }

    sectionQuestions.style.display = "none";
    sectionSubEvents.style.display = "none";
}
