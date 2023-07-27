window.addEventListener("load", loadInscriptionEventCheckerSettings);

function loadInscriptionEventCheckerSettings() {

    const events = document.querySelectorAll("[id^='event-']");

    for (let event of events) {

        const checkboxEvent = event.querySelector("[name^='event[']");

        if (checkboxEvent.checked) activateQuestions(event);

        else deactivateQuestions(event);

        checkboxEvent.addEventListener("change",function(){

            if (checkboxEvent.checked) activateQuestions(event);

            else deactivateQuestions(event);
        });
    }
}

function activateQuestions(event){

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

function deactivateQuestions(event){

    const sectionQuestions = event.querySelector("[id^='questions-']");

    const sectionSubEvents = event.querySelector("[id^='subEvents-']");

    const questions = sectionQuestions.querySelectorAll("[id^='question-']");

    for (let question of questions) {

        const options = question.querySelectorAll("input");

        for (let option of options) {

            option.checked = false;
            option.required = false;
            if (option.type == 'text') option.value="";
        }
    }

    sectionQuestions.style.display = "none";

    sectionSubEvents.style.display = "none";
}