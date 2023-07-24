<?php $questionary = $data['content']['object'];?>
<section class="modal" id="modal-add-question-to-questionary-<?php echo $questionary->getId()?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir pregunta a <?php echo $questionary->getName()?>
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-question-to-questionary-<?php echo $questionary->getId()?>" action="adminQuestion/add" method="post" questionaryId="<?php echo $questionary->getId()?>">
                <input type="hidden" name="questionaryId" value="<?php echo $questionary->getId()?>">
                <div class="mt-1 form-group col-12">
                    <div>Pregunta*</div>
                    <input type="text" name="text" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Tipo*</div>
                    <select name="type" required>
                        <option value="" selected disabled>Selecciona tipo</option>
                        <option value="checkbox">Respuesta múltiple</option>
                        <option value="radio">Respuesta única</option>
                        <option value="text">Respuesta abierta</option>
                    </select>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminQuestion/ajaxAdd/v"}'>Aceptar</button>
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