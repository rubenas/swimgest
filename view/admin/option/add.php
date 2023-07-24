<?php $question = $data['content']['object']; ?>
<section class="modal" id="modal-add-option-to-question-<?php echo $question->getId(); ?>" >
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir opción de respuesta
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between form-race" id="add-option-to-question-<?php echo $question->getId(); ?>" action="adminOption/add" method="post" questionId="<?php echo $question->getId(); ?>">
                <input type="hidden" name="questionId" value="<?php echo $question->getId(); ?>">
                <input type="hidden" name="number" value="<?php echo $question->getNumOptions(); ?>">
                
                <div class="col-12 form-group">
                    <div>Opción*</div>
                    <input type="text" name="text" required>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminOption/ajaxAdd/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            *Campos obligatorios
        </footer>
    </div>
</section>