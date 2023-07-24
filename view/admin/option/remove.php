<?php
    $option = $data['content']['object'];
?>
<section class="modal" id="modal-remove-option-<?php echo $option->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar opción de respuesta
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="remove-option-<?php echo $option->getId(); ?>" optionId="<?php echo $option->getId() ?>" questionId="<?php echo $option->getQuestionId(); ?>" method="post" action="adminOption/remove/<?php echo $option->getId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar la opción <?php echo $option->getText() ?>. 
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminOption/ajaxRemove/<?php echo $option->getId(); ?>/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <input type="hidden" name="optionId" value="<?php echo $option->getId() ?>">
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Esta acción no tiene vuelta atrás
        </footer>
    </div>
</section>