<?php $mark = $data['content']['object'] ?>
<section class="modal" id="modal-remove-mark-<?php echo $mark->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar marca
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">

            <form class="row ai-center jc-between" method="post" id="remove-mark-<?php echo $mark->getId(); ?>" action="mark/remove/<?php echo $mark->getId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar tu marca de <?php echo $mark->getDistance() . ' '
                                                    . $translateToSpanish[$mark->getStyle()] . ' en piscina de ' . $mark->getPool(); ?>
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url": "mark/remove/<?php echo $mark->getId(); ?>/v"}'>
                        Aceptar
                    </button>
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