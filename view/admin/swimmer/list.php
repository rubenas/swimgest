<?php $swimmers = $data['content']['object'] ?>
<section class="tab active" id="swimmers">
    <main class="card p-1">
        <section class="row jc-between">
            <h1>Nadadores</h1>
            <button modal-target="modal-add-swimmer" class="btn tooltip">
                <span class="material-symbols-outlined">add</span>
                <span class="tooltip-text">Añadir nadador/a</span>
            </button>
        </section>
        <section class="text-sm text-sm-md mt-1">
            <div class="row th">
                <div class="col-3">Nombre</div>
                <div class="col-4">Apellidos</div>
                <div class="col-2">Año</div>
                <div class="col-3"></div>
            </div>
            <?php
            foreach ($swimmers as $swimmer) { ?>
                <div class="row tr">
                    <div class="col-3"><?php echo $swimmer->getName(); ?></div>
                    <div class="col-4"><?php echo $swimmer->getSurname(); ?></div>
                    <div class="col-2"><?php echo $swimmer->getBirthYear(); ?></div>
                    <div class="col-3">
                        <a class="tooltip btn-icon-success pr-1" modal-target="modal-edit-swimmer-<?php echo $swimmer->getId(); ?>" ajax-request='{"url": "adminSwimmer/edit/<?php echo $swimmer->getId(); ?>/v"}' href="adminSwimmer/edit/<?php echo $swimmer->getId(); ?>">
                            <span class="material-symbols-outlined text-lg">
                                edit_note
                            </span>
                            <span class="tooltip-text">Editar</span>
                        </a>
                        <a class="tooltip btn-icon-error" modal-target="modal-remove-swimmer-<?php echo $swimmer->getId(); ?>" ajax-request='{"url": "adminSwimmer/removeConfirm/<?php echo $swimmer->getId(); ?>/v"}' href="adminSwimmer/removeConfirm/<?php echo $swimmer->getId(); ?>">
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
