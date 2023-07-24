<?php $competition = $data['content']['object']; ?>
<section class="tab" id="competitions">
    <header>
        <h1>Detalles de la competición</h1>
    </header>
    <main>
        <article class="card row mt-1">

            <?php include 'picture.php'; ?>

            <section class="col-12 col-sm-9 text-card">
                <header class="row w-100 pt-1 px-1">
                    <h4><?php echo $competition->getName(); ?></h4>
                    <div>
                        <a class="btn-icon tooltip ml-1" href="adminCompetition/addJourney/<?php echo $competition->getId(); ?>" modal-target="modal-add-journey" ajax-request='{"url" : "adminCompetition/addJourney/<?php echo $competition->getId(); ?>/v"}'>
                            <span class="material-symbols-outlined text-lg">
                                add_box
                            </span>
                            <span class="tooltip-text">Añadir jornada</span>
                        </a>
                        <a class="tooltip btn-icon-success ml-1" modal-target="modal-edit-competition-<?php echo  $competition->getId(); ?>" ajax-request='{"url": "adminCompetition/edit/<?php echo  $competition->getId(); ?>/v"}' href="adminCompetition/edit/<?php echo $competition->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                edit_note
                            </span>
                            <span class="tooltip-text">Editar</span>
                        </a>
                        <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-competition-<?php echo $competition->getId(); ?>" ajax-request='{"url": "adminCompetition/removeConfirm/<?php echo  $competition->getId(); ?>/v"}' href="adminCompetition/removeConfirm/<?php echo $competition->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                disabled_by_default
                            </span>
                            <span class="tooltip-text">Eliminar</span>
                        </a>
                    </div>
                </header>
                <main class="p-1">
                    <p><strong><?php echo $competition->getPlace(); ?></strong></p>
                    <p>Del <?php echo formatDMYDate($competition->getStartDate()); ?> al <?php echo formatDMYDate($competition->getEndDate()); ?></p>
                    <p><strong>Fecha límite de inscripción:</strong> <?php echo formatDMYHMDate($competition->getDeadLine()); ?></p>
                    <p><strong>Límite de pruebas:</strong> <?php echo $competition->getInscriptionsLimit() ?></p>
                </main>
            </section>
            <section id="journeys" class="w-100">
                <?php foreach ($competition->getJourneys() as $journey) {

                    include './view/admin/journey/details.php';
                }
                ?>
            </section>
            <div class="mb-1 mr-1 row jc-end w-100">
                <a href="adminCompetition/list" class="btn">Finalizar</a>
            </div>
        </article>
    </main>
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
</section>