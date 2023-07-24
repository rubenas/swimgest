<?php if(!isset($session)) $session = $data['content']['object']?>
<article class="row col-12 col-sm-6 mt-1" id="session-<?php echo $session->getId() ?>">
    <section class="row w-100 jc-between ai-center">
        <div>
            <header class="row w-100">
                <h4><?php echo $session->getName() ?></h4>
                <div>
                    <a class="btn-icon tooltip ml-1" href="adminSession/addRace/<?php echo $session->getId(); ?>" modal-target="modal-add-race-to-session-<?php echo $session->getId() ?>" ajax-request='{"url" : "adminSession/addRace/<?php echo $session->getId(); ?>/v"}'>
                        <span class="material-symbols-outlined text-lg">
                            add_box
                        </span>
                        <span class="tooltip-text">Añadir prueba</span>
                    </a>
                    <a class="tooltip btn-icon-success ml-1" modal-target="modal-edit-session-<?php echo $session->getId(); ?>" ajax-request='{"url": "adminSession/edit/<?php echo $session->getId(); ?>/v"}' href="adminSession/edit/<?php echo $session->getId(); ?>">
                        <span class="material-symbols-outlined text-lg">
                            edit_note
                        </span>
                        <span class="tooltip-text">Editar</span>
                    </a>
                    <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-session-<?php echo $session->getId(); ?>" ajax-request='{"url": "adminSession/removeConfirm/<?php echo $session->getId(); ?>/v"}' href="adminSession/removeConfirm/<?php echo $session->getId(); ?>">
                        <span class="material-symbols-outlined text-lg">
                            disabled_by_default
                        </span>
                        <span class="tooltip-text">Eliminar</span>
                    </a>
                </div>
            </header>
            <main class="my-1">
                <p><strong>Hora:</strong> <?php echo formatHMDate($session->getTime()) ?></p>
                <p><strong>Límite de pruebas:</strong> <?php echo $session->getInscriptionsLimit() ?></p>
            </main>
        </div>
    </section>
    <ol class="px-1">
        <?php foreach ($session->getRaces() as $race) {

            include './view/admin/race/details.php';
        }
        ?>
    </ol>
</article>