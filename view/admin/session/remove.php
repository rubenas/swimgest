<?php
    $session = $data['content']['object'];
    $journeyName = $data['content']['journeyName'];
?>
<section class="modal" id="modal-remove-session-<?php echo $session->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar sesión
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="remove-session-<?php echo $session->getId(); ?>" sessionId="<?php echo $session->getId() ?>" journeyId="<?php echo $session->getJourneyId(); ?>" method="post" action="adminSession/remove/<?php echo $session->getId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar la sesión <?php echo $session->getName() ?> de la jornada <?php echo $journeyName ?>. Esta acción eliminará también todas sus pruebas.
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminSession/ajaxRemove/<?php echo $session->getId(); ?>/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <input type="hidden" name="sessionId" value="<?php echo $session->getId() ?>">
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Esta acción no tiene vuelta atrás
        </footer>
    </div>
</section>