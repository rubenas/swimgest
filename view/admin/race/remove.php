<?php
    $race = $data['content']['object'];
    $sessionName = $data['content']['sessionName'];
?>
<section class="modal" id="modal-remove-race-<?php echo $race->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar prueba
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">

            <form class="row ai-center jc-between" id="remove-race-<?php echo $race->getId(); ?>" sessionId="<?php echo $race->getSessionId() ?>" raceId="<?php echo $race->getId(); ?>" method="post" action="adminRace/remove/<?php echo $race->getId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar los <?php echo $race->getDistance() . ' ' . $translateToSpanish[$race->getStyle()]. ' ' . $translateToSpanish[$race->getGender()]; ?> de la sesión <?php echo $sessionName ?>
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminRace/ajaxRemove/<?php echo $race->getId(); ?>/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <input type="hidden" name="sessionId" value="<?php echo $race->getSessionId() ?>">
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Esta acción no tiene vuelta atrás
        </footer>
    </div>
</section>