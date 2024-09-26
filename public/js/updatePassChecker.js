
import { checkPassword } from "./modules/functions.js";

window.addEventListener("load", loadUpdatePassCheckerSettings);

function loadUpdatePassCheckerSettings() {

    const pass1 = document.querySelector("*[name='password']");
    const pass2 = document.querySelector("*[name='password2']");
    const form = document.querySelector("*[name='update-pass']");
    const button = form.querySelector("*[type='submit']");

    button.addEventListener("click", function (event) {

        event.preventDefault();
        const result = checkPassword([pass1, pass2]);

        if (!result.success) {

            form.querySelector(".error").textContent = result.msg;

        } else {

            form.submit();

        }

    });

}
