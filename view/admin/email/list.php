<?php
if ($data['content']['object'] == null) $emails = [];
else $emails = $data['content']['object'];
?>

<section class="tab" id="emails">
    <main class="card p-1">
        <section class="row jc-between">
            <h1>Plantillas de correo</h1>
            <button class="btn tooltip" modal-target="modal-add-email">
                <span class="material-symbols-outlined">add</span>
                <span class="tooltip-text">Añadir email</span>
            </button>
        </section>
        <section class="text-sm text-sm-md mt-1">
            <div class="row th">
                <div class="col-8 col-sm-4">Título</div>
                <div class="d-none d-sm-block col-sm-6">Asunto</div>
                <div class="col-4 col-sm-2"></div>
            </div>
            <?php
            foreach ($emails as $email) { ?>
                <div class="row tr">
                    <div class="col-8 col-sm-4"><?php echo $email->getTitle(); ?></div>
                    <div class="d-none d-sm-block col-sm-6"><?php echo $email->getSubject(); ?></div>
                    <div class="col-4 col-sm-2">
                        <a class="tooltip btn-icon-success" modal-target="modal-edit-email-<?php echo $email->getId() ?>" ajax-request='{"url": "adminEmail/edit/<?php echo $email->getId(); ?>/v"}' href="adminEmail/edit/<?php echo $email->getId(); ?>" id="email-details-<?php echo $email->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                edit_note
                            </span>
                            <span class="tooltip-text">Editar</span>
                        </a>
                        <a class="tooltip btn-icon-error ml-1" modal-target="modal-remove-email-<?php echo $email->getId(); ?>" ajax-request='{"url": "adminEmail/removeConfirm/<?php echo $email->getId(); ?>/v"}' href="adminEmail/removeConfirm/<?php echo $email->getId(); ?>">
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