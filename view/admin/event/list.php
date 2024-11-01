<?php
$events = $data['content']['object'];
?>

<section class="tab" id="events">
    <main class="card p-1">
        <section class="row jc-between">
            <h1>Eventos</h1>
            <button class="btn tooltip" modal-target="modal-add-event">
                <span class="material-symbols-outlined">add</span>
                <span class="tooltip-text">Añadir evento</span>
            </button>
        </section>
        <section class="text-sm text-sm-md mt-1">
            <div class="row th">
                <div class="col-4 col-sm-2">Nombre</div>
                <div class="d-none d-sm-block col-sm-2">Lugar</div>
                <div class="d-none d-sm-block col-sm-2">F. Inicio</div>
                <div class="d-none d-sm-block col-sm-2">Lím. Inscrip.</div>
                <div class="col-4 col-sm-2">Inscrip.</div>
                <div class="col-4 col-sm-2"></div>
            </div>
            <?php
            foreach ($events as $event) { ?>
                <div class="row tr">
                    <div class="col-4 col-sm-2"><?php echo $event->getName(); ?></div>
                    <div class="d-none d-sm-block col-sm-2"><?php echo $event->getPlace(); ?></div>
                    <div class="d-none d-sm-block col-sm-2"><?php echo formatDMYDate($event->getStartDate()); ?></div>
                    <div class="d-none d-sm-block col-sm-2"><?php echo formatDMYDate($event->getDeadLine()); ?></div>
                    <div class="col-4 col-sm-2">
                       <?php include 'stateForm.php'?>
                    </div>
                    <div class="col-4 col-sm-2">
                        <a class="tooltip btn-icon mr-1" tab-target="events" href="adminInscription/event/<?php echo $event->getId()?>" ajax-request='{"url": "adminInscription/event/<?php echo $event->getId(); ?>/v"}' id="inscripted-to-event-<?php echo $event->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                list_alt
                            </span>
                            <span class="tooltip-text">Inscrit@s</span>
                        </a>
                        <a class="tooltip btn-icon-success" tab-target="events" ajax-request='{"url": "adminEvent/details/<?php echo $event->getId(); ?>/v"}' href="adminEvent/details/<?php echo $event->getId(); ?>" id="event-details-<?php echo $event->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                edit_note
                            </span>
                            <span class="tooltip-text">Editar</span>
                        </a>
                        <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-event-<?php echo $event->getId(); ?>" ajax-request='{"url": "adminEvent/removeConfirm/<?php echo $event->getId(); ?>/v"}' href="adminEvent/removeConfirm/<?php echo $event->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                disabled_by_default
                            </span>
                            <span class="tooltip-text">Eliminar</span>
                        </a>
                    </div>
                </div>
            <?php  } ?>
        </section>
        <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
        <div class="success"><?php if (isset($data['content']['msg'])) echo $data['content']['msg']; ?></div>
    </main>
</section>