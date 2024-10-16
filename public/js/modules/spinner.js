/**
 * Displays the loading spinner or animation in the specified section.
 *
 * @param {HTMLElement} section - The section element in which to display the loader.
 */

export function showLoading(section) {
   const loading = section.querySelector(".loading");
   loading.classList.add("display");
}

/**
* Hides the loading spinner or animation in the specified section.
*
* @param {HTMLElement} section - The section element in which to hide the loader.
*/

export function hideLoading(section) {
   const loading = section.querySelector(".loading");
   loading.classList.remove("display");
}
