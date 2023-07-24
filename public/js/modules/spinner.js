/*Función que muestra el loader durante una petición AJAX*/
export function showLoading(section) {
    const loading = section.querySelector(".loading");
    loading.classList.add("display");
 }

 /*Función que oculta el loader durante una petición AJAX*/
 export function hideLoading(section) {
    const loading = section.querySelector(".loading");
    loading.classList.remove("display");
 }