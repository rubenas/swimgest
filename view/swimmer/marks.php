<section id="marks" class="card p-1 mt-1">
    <header class="row jc-between">
        <h2>Mis marcas</h2>
        <button modal-target="modal-add-mark" class="btn tooltip">
            <span class="material-symbols-outlined">add</span>
            <span class="tooltip-text">Añadir marca</span>
        </button>
    </header>

    <main class="text-sm text-sm-md mt-1">
        <div class="row th">
            <div class="col-2">Prueba</div>
            <div class="col-3">Tiempo</div>
            <div class="col-2">Piscina</div>
            <div class="col-3">Puntos FINA</div>
            <div class="col-2"></div>
        </div>

        <?php foreach ($data['content']['object']->getMarks() as $mark) { ?>

            <div class="row tr">
                <div class="col-2"><?php echo $mark->getDistance() . " " . $translateToSpanish[$mark->getStyle()]; ?></div>
                <div class="col-3"><?php echo formatMark($mark->getTime()); ?></div>
                <div class="col-2"><?php echo $mark->getPool(); ?></div>
                <div class="col-3"><?php echo $mark->getFinaPoints(); ?></div>
                <div class="col-2">
                    <button class="tooltip btn-icon-success pr-1" modal-target="modal-edit-mark-<?php echo $mark->getId(); ?>" ajax-request='{"url": "mark/edit/<?php echo $mark->getId(); ?>/v"}'>
                        <span class="material-symbols-outlined text-lg">
                            edit_note
                        </span>
                        <span class="tooltip-text">Editar</span>
                    </button>
                    <button class="tooltip btn-icon-error" modal-target="modal-remove-mark-<?php echo $mark->getId(); ?>" ajax-request='{"url": "mark/removeConfirm/<?php echo $mark->getId(); ?>/v"}'>
                        <span class="material-symbols-outlined text-lg">
                            disabled_by_default
                        </span>
                        <span class="tooltip-text">Eliminar</span>
                    </button>
                </div>
            </div>

        <?php } ?>

    </main>
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
    <div class="success"><?php if (isset($data['content']['msg'])) echo $data['content']['msg']; ?></div>
</section>