<?php
if ($data['content']['object'] == null) $questionaries = [];
else $questionaries = $data['content']['object'];
?>

<section class="tab" id="questionaries">
    <main class="card p-1">
        <section class="row jc-between">
            <h1>Cuestionarios</h1>
            <button class="btn tooltip" modal-target="modal-add-questionary">
                <span class="material-symbols-outlined">add</span>
                <span class="tooltip-text">Añadir cuestionario</span>
            </button>
        </section>
        <section class="text-sm text-sm-md mt-1">
            <div class="row th">
                <div class="col-2">Nombre</div>
                <div class="col-2">Lím. Resp.</div>
                <div class="col-2">Resp.</div>
                <div class="col-2"></div>
            </div>
            <?php
            foreach ($questionaries as $questionary) { ?>
                <div class="row tr">
                    <div class="col-2"><?php echo $questionary->getName(); ?></div>
                    <div class="col-2"><?php echo formatDMYDate($questionary->getDeadLine()); ?></div>
                    <div class="col-2">
                        <?php include 'stateForm.php' ?>
                    </div>
                    <div class="col-2">
                        <a class="tooltip btn-icon mr-sm-1">
                            <span class="material-symbols-outlined text-lg">
                                list_alt
                            </span>
                            <span class="tooltip-text">Respuestas</span>
                        </a>
                        <a class="tooltip btn-icon-success" ajax-request='{"url": "adminQuestionary/details/<?php echo $questionary->getId(); ?>/v"}' href="adminQuestionary/details/<?php echo $questionary->getId(); ?>" id="questionary-details-<?php echo $questionary->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                edit_note
                            </span>
                            <span class="tooltip-text">Editar</span>
                        </a>
                        <a class="tooltip btn-icon-error ml-sm-1" modal-target="modal-remove-questionary-<?php echo $questionary->getId(); ?>" ajax-request='{"url": "adminQuestionary/removeConfirm/<?php echo $questionary->getId(); ?>/v"}' href="adminQuestionary/removeConfirm/<?php echo $questionary->getId(); ?>">
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
    </main>
</section>