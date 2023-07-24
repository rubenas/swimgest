<?php if(!isset($journey)) $journey = $data['content']['object'] ?>
<article class="card row p-1 m-1" id="journey-<?php echo $journey->getId(); ?>">
    <section class="row w-100 jc-between ai-center">
        <header class="row w-100">
            <h3><?php echo $journey->getName() ?></h3>
            <div>
                <a class="btn-icon tooltip ml-1" href="adminJourney/addSession/<?php echo $journey->getId(); ?>" modal-target="modal-add-session-to-journey-<?php echo $journey->getId(); ?>" ajax-request='{"url" : "adminJourney/addSession/<?php echo $journey->getId(); ?>/v"}'>
                    <span class="material-symbols-outlined text-lg">
                        add_box
                    </span>
                    <span class="tooltip-text">Añadir sesión</span>
                </a>
                <a class="tooltip btn-icon-success ml-1" modal-target="modal-edit-journey-<?php echo $journey->getId(); ?>" ajax-request='{"url": "adminJourney/edit/<?php echo $journey->getId(); ?>/v"}' href="adminJourney/edit/<?php echo $journey->getId(); ?>">
                    <span class="material-symbols-outlined text-lg">
                        edit_note
                    </span>
                    <span class="tooltip-text">Editar</span>
                </a>
                <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-journey-<?php echo $journey->getId(); ?>" ajax-request='{"url": "adminJourney/removeConfirm/<?php echo $journey->getId(); ?>/v"}' href="adminJourney/removeConfirm/<?php echo $journey->getId(); ?>">
                    <span class="material-symbols-outlined text-lg">
                        disabled_by_default
                    </span>
                    <span class="tooltip-text">Eliminar</span>
                </a>
            </div>
        </header>
        <main class="my-1">
            <p><strong>Fecha:</strong> <?php echo formatDMYDate($journey->getDate()) ?></p>
            <p><strong>Límite de pruebas:</strong> <?php echo $journey->getInscriptionsLimit() ?></p>
        </main>
    </section>
    <section id="sessions-<?php echo $journey->getId() ?>" class="w-100 row jc-between ai-start">
        <?php foreach ($journey->getSessions() as $session) {

            include './view/admin/session/details.php';
        }
        ?>
    </section>
</article>