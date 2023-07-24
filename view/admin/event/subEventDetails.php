<?php
if (isset($subEvent)) $event = $subEvent;

if (!isset($event)) $event = $data['content']['object'];
?>
<article class="card row my-1" id="event-<?php echo $event->getId(); ?>">

    <?php include 'picture.php'; ?>

    <section class="col-12 col-sm-9 text-card">
        <header class="row w-100 pt-1 px-1">
            <h3><?php echo $event->getName(); ?></h3>
            <div>
                <a class="btn-icon tooltip ml-1" href="adminEvent/showAddSubEvent/<?php echo $event->getId(); ?>" modal-target="modal-add-sub-event-<?php echo $event->getId(); ?>" ajax-request='{"url" : "adminEvent/addSubEvent/<?php echo $event->getId(); ?>/v"}'>
                    <span class="material-symbols-outlined text-lg">
                        add_box
                    </span>
                    <span class="tooltip-text">Añadir sub-evento</span>
                </a>
                <a class="btn-icon-alert tooltip ml-1" href="adminEvent/addQuestion/<?php echo $event->getId(); ?>" modal-target="modal-add-question-to-event-<?php echo $event->getId(); ?>" ajax-request='{"url" : "adminEvent/addQuestion/<?php echo $event->getId(); ?>/v"}'>
                    <span class="material-symbols-outlined text-lg">
                        quiz
                    </span>
                    <span class="tooltip-text">Añadir pregunta</span>
                </a>
                <a class="tooltip btn-icon-success ml-1" modal-target="modal-edit-event-<?php echo  $event->getId(); ?>" ajax-request='{"url": "adminEvent/edit/<?php echo  $event->getId(); ?>/v"}' href="adminEvent/edit/<?php echo $event->getId(); ?>">
                    <span class="material-symbols-outlined text-lg">
                        edit_note
                    </span>
                    <span class="tooltip-text">Editar</span>
                </a>
                <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-event-<?php echo $event->getId(); ?>" ajax-request='{"url": "adminEvent/removeConfirm/<?php echo  $event->getId(); ?>/v"}' href="adminEvent/removeConfirm/<?php echo $event->getId(); ?>">
                    <span class="material-symbols-outlined text-lg">
                        disabled_by_default
                    </span>
                    <span class="tooltip-text">Eliminar</span>
                </a>

            </div>
        </header>
        <main class="p-1">
            <p><strong><?php echo $event->getPlace(); ?></strong></p>
            <p>
                <?php
                if (!is_null($event->getStartDate())) echo 'Del ' . formatDMYDate($event->getStartDate());
                if (!is_null($event->getEndDate())) echo ' al ' . formatDMYDate($event->getEndDate());
                ?>
            </p>
            <p>
                <?php
                if (!is_null($event->getDeadLine())) echo '<strong>Fecha límite de inscripción:</strong> ' . formatDMYDate($event->getDeadLine());
                ?>
            </p>
        </main>
    </section>
    <section id="questions-<?php echo $event->getId(); ?>" class="w-100 px-1">
        <?php foreach ($event->getQuestions() as $question) {
            include './view/admin/question/details.php';
        }
        ?>
    </section>
    <section id="subEvents" class="w-100 px-1">
        <?php
        foreach ($event->getsubEvents() as $subEvent) {
            include 'subEventDetails.php';
        }
        ?>
    </section>
</article>