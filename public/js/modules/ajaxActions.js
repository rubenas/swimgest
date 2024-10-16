import {
    ajaxPostRequest,
    ajaxGetRequest
} from "./ajax.js";
import {
    checkEmailFormat,
    checkRequiredElements,
    checkMark,
    returnTrue,
    checkPicture,
    isNotDefault,
    checkFinaCalculatorFields,
    checkPassword,
    checkCompetition,
    checkJourney
} from "./functions.js";
import {
    closeModalWindow,
    loadModalSettings
} from "./modal.js";
import {
    loadTabSettings
} from "./tab.js";

/**
 * Loads AJAX settings for various actions related to the application.
 *
 * @param {HTMLElement|null} element - The root element to search within, defaults to the document.
 */

export function loadAjaxSettings(element) {
    if (element == null) element = document;

    // Swimmer actions
    updateEmailSettings(element);
    updatePasswordSettings(element);
    addMarkSetting(element);
    removeMarkSettings(element);
    editMarkSettings(element);
    updatePictureSettings(element);
    removePictureSettings(element);
    finaPointsSettings(element);

    // Competition actions
    addCompetitionSettings(element);
    addJourneySettings(element);
    addRaceSettings(element);
    addSessionSettings(element);
    removeRaceSettings(element);
    removeSessionSettings(element);
    moveRaceSettings(element);
    editSessionSettings(element);
    editJourneySettings(element);
    editCompetitionSettings(element);
    updateCompetitionPictureSettings(element);
    removeCompetitionPictureSettings(element);

    // Event actions
    addEventSettings(element);
    editEventSettings(element);
    removeEventPictureSettings(element);
    updateEventPictureSettings(element);
    addSubEventSettings(element);
    removeSubEventSettings(element);

    // Question actions
    addQuestionToEventSettings(element);
    editQuestionSettings(element);
    removeQuestionSettings(element);
    addOptionSettings(element);
    removeOptionSettings(element);
    moveOptionSettings(element);

    // Questionary actions
    editQuestionarySettings(element);
    updateQuestionaryPictureSettings(element);
    removeQuestionaryPictureSettings(element);

    // Updates state of inscriptions on events, competitions and questionaries
    loadUpdateStateSettings(element);
}

/**
 * Sets up email update settings.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function updateEmailSettings(element) {
    const form = element.querySelector("#update-email");

    if (form != null) {
        const button = form.querySelector("button[type='submit']");

        const dataToAction = {
            "idToReplace": "profile",
            "idToClose": "#modal-edit-email"
        };

        commonSettings(form, button, checkEmailFormat, ["#newEmail"], dataToAction);
    }
}

/**
 * Sets up password update settings.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function updatePasswordSettings(element) {
    const form = element.querySelector("#update-pass");

    if (form != null) {
        const button = form.querySelector("button[type='submit']");

        const dataToAction = {
            "idToReplace": "profile",
            "idToClose": "#modal-edit-pass"
        };

        commonSettings(form, button, checkPassword, ["#password1", "#password2"], dataToAction);
    }
}

/**
 * Sets up settings for adding a mark.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addMarkSetting(element) {
    const form = element.querySelector("#add-mark");

    if (form != null) {
        const button = form.querySelector("button[type='submit']");

        const dataToAction = {
            "idToReplace": "marks",
            "idToClose": "#modal-add-mark"
        };

        commonSettings(form, button, checkMark, [
            "*[name='min']",
            "*[name='sec']",
            "*[name='dec']"
        ], dataToAction);
    }
}

/**
 * Sets up settings for removing marks.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeMarkSettings(element) {
    const forms = element.querySelectorAll("*[id^='remove-mark-']");

    const dataToAction = {
        "idToReplace": "marks",
        "idToClose": "*[id^='modal-remove-mark-']"
    };

    for (let form of forms) {
        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for editing marks.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function editMarkSettings(element) {
    const forms = element.querySelectorAll("*[id^='edit-mark-']");

    const dataToAction = {
        "idToReplace": "marks",
        "idToClose": "*[id^='modal-edit-mark-']"
    };

    for (let form of forms) {
        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, checkMark, [
            "*[name='min']",
            "*[name='sec']",
            "*[name='dec']"
        ], dataToAction);
    }
}


/**
 * Sets up settings for updating a swimmer's profile picture.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function updatePictureSettings(element) {
    const form = element.querySelector("#swimmer-picture");

    if (form != null) {
        const button = form.querySelector("button[type='submit']");

        const dataToAction = {
            "idToReplace": "profile",
            "idToClose": "#modal-add-profile-picture"
        };

        commonSettings(form, button, checkPicture, ["*[name='profile-picture']"], dataToAction);
    }
}

/**
 * Sets up settings for removing a swimmer's profile picture.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removePictureSettings(element) {
    const form = element.querySelector("#remove-profile-picture");

    const dataToAction = {
        "idToReplace": "profile",
        "idToClose": "#modal-remove-profile-picture"
    };

    if (form != null) {
        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, isNotDefault, ["#profilePicture"], dataToAction);
    }
}

/**
 * Sets up settings for calculating FINA points.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function finaPointsSettings(element) {
    const form = element.querySelector("#mark-points");

    if (form != null) {
        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, checkFinaCalculatorFields, [
            "*[name='min']",
            "*[name='sec']",
            "*[name='dec']",
            "*[name='finaPoints']"
        ], null);
    }
}

/**
 * Sets up settings for adding a competition.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addCompetitionSettings(element) {
    const form = element.querySelector("#add-competition");

    const dataToAction = {
        "idToReplace": "competitions",
        "idToClose": "#modal-add-competition"
    };

    if (form != null) {
        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, checkCompetition, ["#picture", "#startDate", "#endDate", "#inscriptionDeadLine"], dataToAction);
    }
}

/**
 * Sets up settings for editing existing competitions.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function editCompetitionSettings(element) {
    const forms = element.querySelectorAll("[id^=edit-competition-]");

    for (let form of forms) {
        let competitionId = form.getAttribute("competitionId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "competitions",
            "idToClose": "#modal-edit-competition-" + competitionId,
            "idToRemove": "#modal-edit-competition-" + competitionId //We need it updates competition data
        };

        commonSettings(form, button, checkCompetition, [
            "*[name='picture']",
            "*[name='startDate']",
            "*[name='endDate']",
            "*[name='inscriptionDeadLine']"
        ], dataToAction);
    }
}

/**
 * Sets up settings for updating competition pictures.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function updateCompetitionPictureSettings(element) {
    const forms = element.querySelectorAll("*[id^=add-competition-picture-]");

    for (let form of forms) {
        const button = form.querySelector("button[type='submit']");
        const competitionId = form.getAttribute("competitionId");

        const dataToAction = {
            "idToReplace": "competition-picture",
            "idToClose": "#modal-add-competition-picture-" + competitionId
        };

        commonSettings(form, button, checkPicture, ["*[name='competition-picture']"], dataToAction);
    }
}

/**
 * Sets up settings for removing competition pictures.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeCompetitionPictureSettings(element) {
    const forms = element.querySelectorAll("*[id^=remove-competition-picture-]");

    for (let form of forms) {
        const button = form.querySelector("button[type='submit']");
        const competitionId = form.getAttribute("competitionId");

        const dataToAction = {
            "idToReplace": "competition-picture",
            "idToClose": "#modal-remove-competition-picture-" + competitionId
        };

        commonSettings(form, button, isNotDefault, ["#competitionPicture"], dataToAction);
    }
}

/**
 * Sets up settings for adding a journey.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addJourneySettings(element) {
    const form = element.querySelector("#add-journey");

    const dataToAction = {
        "idToReplace": "competitions",
        "idToClose": "#modal-add-journey"
    };

    if (form != null) {
        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, checkJourney, ["*[name='startDate']", "*[name='endDate']", "#date"], dataToAction);
    }
}

/**
 * Sets up settings for editing an existing journey.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function editJourneySettings(element) {
    const forms = element.querySelectorAll("[id^=edit-journey-]");

    for (let form of forms) {
        let journeyId = form.getAttribute("journeyId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "journey-" + journeyId,
            "idToClose": "#modal-edit-journey-" + journeyId,
            "idToRemove": "#modal-edit-journey-" + journeyId // We need it updates journey data
        };

        commonSettings(form, button, checkJourney, ["*[name='startDate']", "*[name='endDate']", "#date"], dataToAction);
    }
}

/**
 * Sets up settings for adding sessions to a journey.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addSessionSettings(element) {
    const forms = element.querySelectorAll("[id^=add-session-to-journey-]");

    for (let form of forms) {
        let journeyId = form.getAttribute("journeyId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "journey-" + journeyId,
            "idToClose": "#modal-add-session-to-journey-" + journeyId
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for removing sessions from a journey.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeSessionSettings(element) {
    const forms = element.querySelectorAll("[id^=remove-session-]");

    for (let form of forms) {
        let journeyId = form.getAttribute("journeyId");
        let sessionId = form.getAttribute("sessionId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "journey-" + journeyId,
            "idToClose": "#modal-remove-session-" + sessionId
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for editing existing sessions.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function editSessionSettings(element) {
    const forms = element.querySelectorAll("[id^=edit-session-]");

    for (let form of forms) {
        let sessionId = form.getAttribute("sessionId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "session-" + sessionId,
            "idToClose": "#modal-edit-session-" + sessionId,
            "idToRemove": "#modal-edit-session-" + sessionId // We need it updates session data
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for adding races to a session.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addRaceSettings(element) {
    const forms = element.querySelectorAll("[id^=add-race-to-session-]");

    for (let form of forms) {
        let sessionId = form.getAttribute("sessionId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "session-" + sessionId,
            "idToClose": "#modal-add-race-to-session-" + sessionId,
            "idToRemove": "#modal-add-race-to-session-" + sessionId // We need it updates numRaces
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for removing races from a session.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeRaceSettings(element) {
    const forms = element.querySelectorAll("[id^=remove-race-]");

    for (let form of forms) {
        let sessionId = form.getAttribute("sessionId");
        let raceId = form.getAttribute("raceId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "session-" + sessionId,
            "idToClose": "#modal-remove-race-" + raceId
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for moving races between sessions.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function moveRaceSettings(element) {
    const buttons = element.querySelectorAll("[id^=move-race-]");

    for (let button of buttons) {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            let sessionId = button.getAttribute("sessionId");

            let dataToAction = {
                "idToReplace": "session-" + sessionId
            };

            ajaxGetRequest(button, "html", replaceElement, dataToAction);
        });
    }
}

/**
 * Sets up settings for adding an event.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addEventSettings(element) {
    const form = element.querySelector("#add-event");

    const dataToAction = {
        "idToReplace": "events",
        "idToClose": "#modal-add-event"
    };

    if (form != null) {
        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, checkCompetition, ["#event-picture", "#event-startDate", "#event-endDate", "#event-inscriptionDeadLine"], dataToAction);
    }
}

/**
 * Sets up settings for adding a sub-event.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addSubEventSettings(element) {
    const forms = element.querySelectorAll("[id^=add-sub-event-]");

    for (let form of forms) {
        let parentEventId = form.getAttribute("parentEventId");

        let dataToAction = {
            "idToReplace": "event-" + parentEventId,
            "idToClose": "#modal-add-sub-event-" + parentEventId
        };

        let button = form.querySelector("button[type='submit']");

        commonSettings(form, button, checkCompetition, ["#event-picture", "#event-startDate", "#event-endDate", "#event-inscriptionDeadLine"], dataToAction);
    }
}

/**
 * Sets up settings for removing a sub-event.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeSubEventSettings(element) {
    const forms = element.querySelectorAll("[id^=remove-event-]");

    for (let form of forms) {
        let parentEventId = form.getAttribute("parentEventId");
        let eventId = form.getAttribute("eventId");

        if (parentEventId != null && parentEventId != '') {
            let dataToAction = {
                "idToReplace": "event-" + parentEventId,
                "idToClose": "#modal-remove-event-" + eventId
            };

            let button = form.querySelector("button[type='submit']");

            commonSettings(form, button, returnTrue, [], dataToAction);
        }
    }
}

/**
 * Sets up settings for editing an existing event.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function editEventSettings(element) {
    const forms = element.querySelectorAll("[id^=edit-event-]");

    for (let form of forms) {
        let eventId = form.getAttribute("eventId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "event-" + eventId,
            "idToClose": "#modal-edit-event-" + eventId,
            "idToRemove": "#modal-edit-event-" + eventId // We need it updates event data
        };

        commonSettings(form, button, checkCompetition, ["*[name='picture']", "*[name='startDate']", "*[name='endDate']", "*[name='inscriptionDeadLine']"], dataToAction);
    }
}

/**
 * Sets up settings for updating an event's picture.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function updateEventPictureSettings(element) {
    const forms = element.querySelectorAll("*[id^=add-event-picture-]");

    for (let form of forms) {
        const button = form.querySelector("button[type='submit']");

        const eventId = form.getAttribute("eventId");

        const dataToAction = {
            "idToReplace": "event-picture-" + eventId,
            "idToClose": "#modal-add-event-picture-" + eventId
        };

        commonSettings(form, button, checkPicture, ["*[name='event-picture']"], dataToAction);
    }
}

/**
 * Sets up settings for removing an event's picture.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeEventPictureSettings(element) {
    const forms = element.querySelectorAll("*[id^=remove-event-picture-]");

    for (let form of forms) {
        const button = form.querySelector("button[type='submit']");

        const eventId = form.getAttribute("eventId");

        const dataToAction = {
            "idToReplace": "event-picture-" + eventId,
            "idToClose": "#modal-remove-event-picture-" + eventId
        };

        commonSettings(form, button, isNotDefault, ["#eventPicture"], dataToAction);
    }
}

/**
 * Sets up settings for adding a question to an event.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addQuestionToEventSettings(element) {
    const forms = element.querySelectorAll("[id^=add-question-to-event-]");

    for (let form of forms) {
        let eventId = form.getAttribute("eventId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "event-" + eventId,
            "idToClose": "#modal-add-question-to-event-" + eventId,
            "idToRemove": "#modal-add-question-to-event-" + eventId // Clear fields
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for editing a question.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function editQuestionSettings(element) {
    const forms = element.querySelectorAll("[id^=edit-question-]");

    for (let form of forms) {
        let questionId = form.getAttribute("questionId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "question-" + questionId,
            "idToClose": "#modal-edit-question-" + questionId,
            "idToRemove": "#modal-edit-question-" + questionId // We need it updates event data
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for removing a question.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeQuestionSettings(element) {
    const forms = element.querySelectorAll("[id^=remove-question-]");

    for (let form of forms) {
        let eventId = form.getAttribute("eventId");
        let questionId = form.getAttribute("questionId");

        if (eventId != null && eventId != '') {
            let dataToAction = {
                "idToReplace": "event-" + eventId,
                "idToClose": "#modal-remove-question-" + questionId
            };

            let button = form.querySelector("button[type='submit']");

            commonSettings(form, button, returnTrue, [], dataToAction);
        }
    }
}

/**
 * Sets up settings for adding an option to a question.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function addOptionSettings(element) {
    const forms = element.querySelectorAll("[id^=add-option-to-question-]");

    for (let form of forms) {
        let questionId = form.getAttribute("questionId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "question-" + questionId,
            "idToClose": "#modal-add-option-to-question-" + questionId,
            "idToRemove": "#modal-add-option-to-question-" + questionId // We need it updates numOptions
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for removing an option from a question.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeOptionSettings(element) {
    const forms = element.querySelectorAll("[id^=remove-option-]");

    for (let form of forms) {
        let questionId = form.getAttribute("questionId");
        let optionId = form.getAttribute("optionId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "question-" + questionId,
            "idToClose": "#modal-remove-option-" + optionId
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for moving an option within a question.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function moveOptionSettings(element) {
    const buttons = element.querySelectorAll("[id^=move-option-]");

    for (let button of buttons) {
        button.addEventListener("click", function (event) {
            event.preventDefault();

            let questionId = button.getAttribute("questionId");

            let dataToAction = {
                "idToReplace": "question-" + questionId
            };

            ajaxGetRequest(button, "html", replaceElement, dataToAction);
        });
    }
}

/**
 * Sets up settings for editing a questionary.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function editQuestionarySettings(element) {
    const forms = element.querySelectorAll("[id^=edit-questionary-]");

    for (let form of forms) {
        let questionaryId = form.getAttribute("questionaryId");

        let button = form.querySelector("button[type='submit']");

        let dataToAction = {
            "idToReplace": "questionaries",
            "idToClose": "#modal-edit-questionary-" + questionaryId,
            "idToRemove": "#modal-edit-questionary-" + questionaryId // We need it updates questionary data
        };

        commonSettings(form, button, returnTrue, [], dataToAction);
    }
}

/**
 * Sets up settings for updating a questionary's picture.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function updateQuestionaryPictureSettings(element) {
    const forms = element.querySelectorAll("*[id^=add-questionary-picture-]");

    for (let form of forms) {
        const button = form.querySelector("button[type='submit']");
        const questionaryId = form.getAttribute("questionaryId");

        const dataToAction = {
            "idToReplace": "questionary-picture-" + questionaryId,
            "idToClose": "#modal-add-questionary-picture-" + questionaryId
        };

        commonSettings(form, button, checkPicture, ["*[name='questionary-picture']"], dataToAction);
    }
}

/**
 * Sets up settings for removing a questionary's picture.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function removeQuestionaryPictureSettings(element) {
    const forms = element.querySelectorAll("*[id^=remove-questionary-picture-]");

    for (let form of forms) {
        const button = form.querySelector("button[type='submit']");
        const questionaryId = form.getAttribute("questionaryId");

        const dataToAction = {
            "idToReplace": "questionary-picture-" + questionaryId,
            "idToClose": "#modal-remove-questionary-picture-" + questionaryId
        };

        commonSettings(form, button, isNotDefault, ["#questionaryPicture"], dataToAction);
    }
}

/**
 * Sets up settings for loading update state options.
 *
 * @param {HTMLElement} element - The root element to search within.
 */

function loadUpdateStateSettings(element) {
    const forms = element.querySelectorAll("[id*='-state-']");

    for (let form of forms) {
        const selectState = form.querySelector("[name='state']");
        const button = form.querySelector("button[type='submit']");

        selectState.addEventListener("change", function () {
            const dataToAction = {
                "idToReplace": form.id
            };

            ajaxPostRequest(form, button, "html", replaceElement, dataToAction);
        });
    }
}

/**
 * Configures common settings for forms, including event listeners and validation.
 *
 * @param {HTMLFormElement} form - The form element to attach settings to.
 * @param {HTMLElement} button - The submit button associated with the form.
 * @param {function} checkFunction - A function to validate form fields.
 * @param {Array<string>} fields - An array of CSS selectors for fields to validate.
 * @param {Object} dataToAction - Data object containing IDs for replacement and closure actions.
 */

function commonSettings(form, button, checkFunction, fields, dataToAction) {
    if (button != null) {
        button.addEventListener("click", function (event) {
            if (checkRequiredElements(form)) {
                event.preventDefault();

                let fieldsToCheck = [];

                for (let selector of fields) {
                    let element;

                    if (selector.includes("profilePicture") ||
                        selector.includes("competitionPicture") ||
                        selector.includes("eventPicture") ||
                        selector.includes("questionaryPicture")) {
                        element = document.querySelector(selector);
                    } else {
                        element = form.querySelector(selector);
                    }

                    fieldsToCheck.push(element);
                }

                if (form.id == "mark-points") {
                    dataToAction = loadDataToAction(form);
                }

                let validation = checkFunction(fieldsToCheck);

                if (validation.success) {
                    ajaxPostRequest(form, button, "html", replaceElement, dataToAction);
                } else {
                    form.querySelector(".error").textContent = validation.msg;
                }
            } else {
                form.querySelector(".error").textContent = "Hay campos obligatorios por cubrir.";
            }
        });
    }
}

/**
 * Updates the action data based on the provided form.
 *
 * @param {HTMLFormElement} form - The form element to extract data from.
 * @returns {Object} - An object containing the ID to replace.
 */

function loadDataToAction(form) {
    switch (form.id) {
        case "mark-points":
            if (form.querySelector("#finaPoints").value != "") {
                return { "idToReplace": "mark" };
            } else {
                return { "idToReplace": "finaPoints" };
            }
    }
}

/**
 * Replaces an existing element with a new one received from an Ajax request.
 *
 * @param {HTMLElement} element - The element to be replaced.
 * @param {Object} data - An object containing information for replacing elements.
 * @param {string} data.idToReplace - The ID of the element to replace.
 * @param {string} data.idToClose - The selector(s) of modal(s) to close.
 * @param {string} data.idToRemove - The selector(s) of modal(s) to remove.
 */

function replaceElement(element, data) {
    const newElement = element.getElementById(data.idToReplace);
    const oldElement = document.getElementById(data.idToReplace);

    oldElement.replaceWith(newElement);

    loadModalSettings(newElement);
    moveRaceSettings(newElement);
    moveOptionSettings(newElement);
    loadTabSettings(newElement);

    let modals = document.querySelectorAll(data.idToClose);
    for (let modal of modals) {
        closeModalWindow(modal);
        for (let div of modal.querySelectorAll(".error")) {
            div.textContent = "";
        }
    }

    modals = document.querySelectorAll(data.idToRemove);
    for (let modal of modals) {
        modal.remove();
    }

    let msgs = document.querySelectorAll(".success");
    for (let msg of msgs) {
        setTimeout(function () {
            msg.textContent = "";
        }, 4000);
    }

    let errors = document.querySelectorAll(".error");
    for (let error of errors) {
        error.textContent = "";
    }
}
