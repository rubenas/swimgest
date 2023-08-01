export function loadCKEditorSettings(element) {

    if (element == null) element = document;
    const forms = element.querySelectorAll("form");

    for (let form of forms) {

        const textarea = form.querySelector(".editor");

        if (textarea != null) {

            ClassicEditor
                .create(textarea)
                .then(editor => editor.editing.view.document.on("change",
                    function () {
                        textarea.value = editor.getData();
                    })
                )
                .catch(error => {
                    console.error(error);
                });
        }
    }
}