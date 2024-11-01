<?php
if ($data['content']['object'] == null) $competitions = [];
else $competitions = $data['content']['object'];
?>

<section class="tab" id="competitions">
    <main class="card p-1">
        <section class="row jc-between">
            <h1>Competiciones</h1>
            <button class="btn tooltip" modal-target="modal-add-competition">
                <span class="material-symbols-outlined">add</span>
                <span class="tooltip-text">Añadir competición</span>
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
            foreach ($competitions as $competition) { ?>
                <div class="row tr">
                    <div class="col-4 col-sm-2"><?php echo $competition->getName(); ?></div>
                    <div class="d-none d-sm-block col-sm-2"><?php echo $competition->getPlace(); ?></div>
                    <div class="d-none d-sm-block col-sm-2"><?php echo formatDMYDate($competition->getStartDate()); ?></div>
                    <div class="d-none d-sm-block col-sm-2"><?php echo formatDMYDate($competition->getDeadLine()); ?></div>
                    <div class="col-4 col-sm-2">
                        <?php include 'stateForm.php' ?>
                    </div>
                    <div class="col-4 col-sm-2">
                        <a class="tooltip btn-icon mr-1" tab-target="competitions" href="adminInscription/competition/<?php echo $competition->getId()?>" ajax-request='{"url": "adminInscription/competition/<?php echo $competition->getId(); ?>/v"}' id="inscripted-to-competition-<?php echo $competition->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                list_alt
                            </span>
                            <span class="tooltip-text">Inscrit@s</span>
                        </a>
                        <a class="tooltip btn-icon-success" tab-target="competitions" ajax-request='{"url": "adminCompetition/details/<?php echo $competition->getId(); ?>/v"}' href="adminCompetition/details/<?php echo $competition->getId(); ?>" id="competition-details-<?php echo $competition->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                edit_note
                            </span>
                            <span class="tooltip-text">Editar</span>
                        </a>
                        <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-competition-<?php echo $competition->getId(); ?>" ajax-request='{"url": "adminCompetition/removeConfirm/<?php echo $competition->getId(); ?>/v"}' href="adminCompetition/removeConfirm/<?php echo $competition->getId(); ?>">
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
<script type="module">import {activateTabLink} from './public/js/modules/tab.js';activateTabLink('competitions');</script>