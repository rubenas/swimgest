<?php
    $question = $data['content']['object'];
?>
<section class="modal" id="modal-remove-question-<?php echo $question->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar pregunta
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="remove-question-<?php echo $question->getId(); ?>" questionId="<?php echo $question->getId() ?>" questionaryId="<?php echo $question->getQuestionaryId() ?>" eventId="<?php echo $question->getEventId(); ?>" method="post" action="adminQuestion/remove/<?php echo $question->getId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar la pregunta <?php echo $question->getText() ?>. Esta acción eliminará también todas sus opciones de respuesta.
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminQuestion/ajaxRemove/<?php echo $question->getId(); ?>/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Esta acción no tiene vuelta atrás
        </footer>
    </div>
</section>