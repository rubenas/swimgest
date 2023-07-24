<?php $question = $data['content']['object'];?>
<section class="modal" id="modal-edit-question-<?php echo $question->getId()?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Editar pregunta
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="edit-question-<?php echo $question->getId()?>" action="adminQuestion/update/<?php echo $question->getId()?>" method="post" questionId="<?php echo $question->getId()?>">
                <div class="mt-1 form-group col-12">
                    <div>Pregunta*</div>
                    <input type="text" name="text" required autocomplete="true" value="<?php echo $question->getText()?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Tipo*</div>
                    <select name="type" required>
                        <option value="checkbox" <?php if ($question->getType() == 'checkbox') echo 'selected';?> >Respuesta múltiple</option>
                        <option value="radio" <?php if ($question->getType() == 'radio') echo 'selected';?> >Respuesta única</option>
                        <option value="text" <?php if ($question->getType() == 'text') echo 'selected';?> >Respuesta abierta</option>
                    </select>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminQuestion/ajaxUpdate/<?php echo $question->getId()?>/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>