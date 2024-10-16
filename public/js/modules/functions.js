/**
 * Adds an option to a select input.
 *
 * @param {HTMLSelectElement} select - The select input element to which the option will be added.
 * @param {string} optId - The ID to assign to the new option.
 * @param {string} optName - The name to assign to the new option.
 * @param {string} optValue - The value to assign to the new option.
 * @param {string} optText - The text to display for the new option.
 * @param {boolean} optSelected - Whether the new option should be selected.
 */

export function addOption(select, optId, optName, optValue, optText, optSelected) {
    let option = new Option(optText, optValue);
    option.id = optId;
    option.name = optName;
    option.selected = optSelected;
    select.add(option);
}

/**
 * Clears all options from a select element except the first one.
 *
 * @param {HTMLSelectElement} select - The select input element to clear options from.
 */

export function clearOptions(select) {
    for (let i = select.options.length - 1; i > 0; i--) {
        select.remove(i);
    }
}

/**
 * Checks if a form has all the required fields filled.
 *
 * @param {HTMLFormElement} form - The form element to check.
 * @returns {boolean} - True if all required fields are filled, false otherwise.
 */

export function checkRequiredElements(form) {
    for (let element of form.elements) {
        if (element.value == "" && element.required) {
            element.focus();
            return false;
        }
    }
    return true;
}

/**
 * Checks if the email input has the correct format.
 *
 * @param {HTMLInputElement[]} array - An array containing the email input element.
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function checkEmailFormat(array) {
    const validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    if (array[0].value.match(validRegex)) {
        return {
            success: true
        };
    } else {
        return {
            success: false,
            msg: "Introduce un email válido"
        };
    }
}

/**
 * Checks if the provided passwords match and meet security criteria.
 *
 * @param {HTMLInputElement[]} passwords - An array containing two password input elements.
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function checkPassword(passwords) {
    if (passwords[0].value != passwords[1].value) {
        return {
            success: false,
            msg: "Las contraseñas no coinciden"
        };
    }

    const password = passwords[0].value;

    if (password.length < 8) {
        return {
            success: false,
            msg: "La contraseña debe contener un mínimo de 8 caracteres"
        };
    }

    if (!(password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/))) {
        return {
            success: false,
            msg: "La contraseña debe contener al menos una letra mayúscula, una minúscula y un número"
        };
    }

    return {
        success: true
    };
}

/**
 * Checks if a mark is valid (not all zeroes).
 *
 * @param {HTMLInputElement[]} array - An array containing mark input elements (minutes, seconds, decimals).
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function checkMark(array) {
    const min = array[0].value;
    const sec = array[1].value;
    const dec = array[2].value;

    if (min == 0 && sec == 0 && dec == 0) {
        return {
            success: false,
            msg: "Todos los campos de la marca son cero"
        };
    }

    return {
        success: true
    };
}

/**
 * Checks if a picture is valid (max size 1MB).
 *
 * @param {HTMLInputElement[]} array - An array containing the file input element for the picture.
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function checkPicture(array) {
    const picture = array[0].files[0];
    const acceptedImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];

    if (picture.size > 1048576) {
        return {
            success: false,
            msg: "El tamaño máximo permitido es 1MB"
        };
    }

    if (!acceptedImageTypes.includes(picture.type)) {
        return {
            success: false,
            msg: "Solo se aceptan archivos jpeg y png"
        };
    }

    return {
        success: true
    };
}

/**
 * Returns whether the provided picture is the default one or not.
 *
 * @param {HTMLImageElement[]} args - An array containing the picture element to check.
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function isNotDefault(args) {
    const picture = args[0];
    const defaultPicture = "no-picture";

    if (picture.src.includes(defaultPicture)) {
        return {
            success: false,
            msg: "No puedes borrar la imagen por defecto"
        };
    }

    return {
        success: true
    };
}

/**
 * Checks the fields of the FINA calculator.
 *
 * @param {HTMLInputElement[]} array - An array containing the mark input elements and FINA points.
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function checkFinaCalculatorFields(array) {
    const min = array[0].value;
    const sec = array[1].value;
    const dec = array[2].value;
    const finaPoints = array[3].value;

    if (finaPoints == "") {
        if ((min == 0 || min == "") && (sec == 0 || sec == "") && (dec == 0 || dec == "")) {
            return {
                success: false,
                msg: "Todos los campos de la marca son nulos o cero"
            };
        }
    }

    if (isNaN(finaPoints)) {
        return {
            success: false,
            msg: "El valor de los puntos FINA debe ser un número"
        };
    }

    if (finaPoints < 0) {
        return {
            success: false,
            msg: "El valor de los puntos FINA debe ser positivo"
        };
    }

    return {
        success: true
    };
}

/**
 * Checks if a competition form is correct.
 *
 * @param {HTMLInputElement[]} array - An array containing elements for picture, start date, end date, and inscription deadline.
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function checkCompetition(array) {
    const picture = array[0];
    const startDate = array[1] ? new Date(array[1].value) : null;
    const endDate = array[2] ? new Date(array[2].value) : null;
    const inscriptionDeadLine = array[3] ? new Date(array[3].value) : null;

    if (picture != null && picture.value != "") {
        let result = checkPicture([picture]);
        if (!result.success) return result;
    }

    if ((endDate != null && startDate != null) && (endDate < startDate)) {
        return {
            success: false,
            msg: "La fecha de fin no puede ser anterior a la fecha de inicio"
        };
    }

    if ((inscriptionDeadLine != null && startDate != null) && (inscriptionDeadLine >= startDate)) {
        return {
            success: false,
            msg: "La fecha de cierre de inscripciones no puede ser posterior a la fecha de inicio"
        };
    }

    return {
        success: true
    };
}

/**
 * Checks if a journey form is correct.
 *
 * @param {HTMLInputElement[]} array - An array containing elements for start date, end date, and journey date.
 * @returns {Object} - An object indicating success or failure, along with a message.
 */

export function checkJourney(array) {
    const startDate = new Date(array[0].value);
    const endDate = new Date(array[1].value);
    const journeyDate = new Date(array[2].value);

    if (journeyDate < startDate || journeyDate > endDate) {
        return {
            success: false,
            msg: "La fecha de la jornada está fuera del rango de fechas de la competición"
        };
    }

    return {
        success: true
    };
}

/**
 * Always returns success.
 *
 * @param {Array} args - Arguments to the function (not used).
 * @returns {Object} - An object indicating success.
 */

export function returnTrue(args) {
    return {
        success: true
    };
}
