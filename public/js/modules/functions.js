
/*Funtion to add an option to a select input */
export function addOption(select, optId, optName, optValue, optText, optSelected) {
    let option = new Option(optText, optValue);
    option.id = optId;
    option.name = optName;
    option.selected = optSelected;
    select.add(option);
}

/*Clear all options from a select except the first one*/
export function clearOptions(select) {
    for (let i = select.options.length - 1; i > 0; i--) {
        select.remove(i);
    }
}


/*Checks if a form has all the requird fields */
export function checkRequiredElements(form) {
    for (let element of form.elements) {
        if (element.value == "" && element.required) {
            element.focus();
            return false;
        }
    }
    return true;
}

/* Function to check email input has the correct pattern from array*/
export function checkEmailFormat(array) {

    var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

    if (array[0].value.match(validRegex)) {

        return {
            "success": true
        }

    } else {

        return {
            "success": false,
            "msg": "Introduce un email válido"
        }

    }

}

/* Function to check if the passwords are equals, and if it has 8 characters at least and contains a lowercase, an uppercase and a number*/
export function checkPassword(passwords) {

    if (passwords[0].value != passwords[1].value) {

        return {
            "success": false,
            "msg": "Las contraseñas no coinciden"
        }

    }

    const password = passwords[0].value;

    if (password.length < 8) {

        return {
            "success": false,
            "msg": "La contraseña debe contener un mínimo de 8 caracteres"
        }

    }

    if (!(password.match(/[a-z]/)
        && password.match(/[A-Z]/)
        && password.match(/[0-9]/))) {

        return {
            "success": false,
            "msg": "La contraseña debe contener almenos una letra mayúscula, una minúscula y un número"
        }

    }

    return {
        "success": true
    }

}

/*Function to check if a mark is correct*/

export function checkMark(array) {

    const min = array[0].value;
    const sec = array[1].value;
    const dec = array[2].value;

    if (min == 0 && sec == 0 && dec == 0) {

        return {
            "success": false,
            "msg": "Todos los campos de la marca son cero"
        }

    }

    return {
        "success": true,
    }

}

/*Function to check if a picture is correct max size=1MB*/

export function checkPicture(array) {

    const picture = array[0].files[0];

    const acceptedImageTypes = ['image/jpg', 'image/jpeg', 'image/png'];

    if (picture.size > 1048576) {
        return {
            "success": false,
            "msg": "El tamaño máximo permitido es 1MB"
        }
    }

    if (!acceptedImageTypes.includes(picture.type)) {
        return {
            "success": false,
            "msg": "Solo se aceptan archivos jpeg y png"
        }
    }

    return {
        "success": true
    }
}

/*Returns is picture given is or not the default one */

export function isNotDefault(args) {
    
    const picture = args[0];

    const defaultPicture = "no-picture";

    if (picture.src.includes(defaultPicture)) {

        return {
            "success": false,
            "msg": "No puedes borrar la imagen por defecto"
        }
    }

    return {
        "success": true
    }

}

/* Check finaCalculator fields */

export function checkFinaCalculatorFields(array) {

    const min = array[0].value;
    const sec = array[1].value;
    const dec = array[2].value;
    const finaPoints = array[3].value;

    if (finaPoints == "") {

        if ((min == 0 || min == "") &&
            (sec == 0 || sec == "") &&
            (dec == 0 || dec == "")) {

            return {
                "success": false,
                "msg": "Todos los campos de la marca son nulos o cero"
            }

        }

    }

    if (isNaN(finaPoints)) {

        return {
            "success": false,
            "msg": "El valor de los puntos FINA debe ser un número"
        }

    }

    if (finaPoints < 0) {

        return {
            "success": false,
            "msg": "El valor de los puntos FINA debe ser positivo"
        }

    }

    return {
        "success": true
    }

}

/**Checks if a competition form is correct */
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
            "success": false,
            "msg": "La fecha de fin no puede ser anterior a la fecha de inicio"
        }
    }

    if ((inscriptionDeadLine != null && startDate != null) && (inscriptionDeadLine >= startDate)) {
        return {
            "success": false,
            "msg": "La fecha de cierre de inscripciones no puede ser posterior a la fecha de inicio"
        }
    }

    return {
        "success": true
    }

}

/**Checks if a journey form is correct */
export function checkJourney(array) {

    const startDate = new Date(array[0].value);
    const endDate = new Date(array[1].value);
    const journeyDate = new Date(array[2].value);

    if (journeyDate < startDate || journeyDate > endDate) {
        return {
            "success": false,
            "msg": "La fecha de la jornada está fuera del rango de fechas de la competición"
        }
    }

    return {
        "success": true
    }

}

export function returnTrue(args) {

    return {
        "success": true
    }

}




