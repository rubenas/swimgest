<?php
    $event = $data['content']['object'];
?>
<section class="modal" id="modal-remove-event-inscription-<?php echo $event->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar inscripción
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">

            <form class="row ai-center jc-between" id="remove-event-inscription-<?php echo $event->getId(); ?>" method="post" action="inscriptionEvent/remove/<?php echo $event->getId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar la tu inscripción a <?php echo $event->getName() ?>.
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1">Aceptar</button>
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