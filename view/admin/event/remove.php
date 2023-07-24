<?php
    $event = $data['content']['object'];
?>
<section class="modal" id="modal-remove-event-<?php echo $event->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar evento
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">

            <form class="row ai-center jc-between" id="remove-event-<?php echo $event->getId(); ?>" method="post" action="adminEvent/remove/<?php echo $event->getId(); ?>" eventId="<?php echo $event->getId(); ?>" parentEventId="<?php echo $event->getParentId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar el evento <?php echo $event->getName() ?>. Esta acción eliminará también sus sub-eventos asociados.
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminEvent/remove/<?php echo $event->getId(); ?>/v"}'>Aceptar</button>
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