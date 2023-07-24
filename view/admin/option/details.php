<li class="pb-1">
    <div class="row w-100">
        <?php echo $option->getText() ?>
        <div>
            <a class="btn-icon tooltip ml-1" href="adminOption/moveUp/<?php echo $option->getId(); ?>" id="move-option-up-<?php echo $option->getId(); ?>" ajax-request='{"url" : "adminOption/ajaxMoveUp/<?php echo $option->getId(); ?>/v"}' questionId="<?php echo $option->getQuestionId(); ?>">
                <span class="material-symbols-outlined text-lg">
                    arrow_upward
                </span>
                <span class="tooltip-text">Subir</span>
            </a>
            <a class="btn-icon tooltip" href="adminOption/moveDown/<?php echo $option->getId(); ?>" id="move-option-down-<?php echo $option->getId(); ?>" ajax-request='{"url" : "adminOption/ajaxMoveDown/<?php echo $option->getId(); ?>/v"}' questionId="<?php echo $option->getQuestionId(); ?>">
                <span class="material-symbols-outlined text-lg">
                    arrow_downward
                </span>
                <span class="tooltip-text">Bajar</span>
            </a>
            <a class="tooltip btn-icon-error" modal-target="modal-remove-option-<?php echo $option->getId(); ?>" ajax-request='{"url": "adminOption/removeConfirm/<?php echo $option->getId(); ?>/v"}' href="adminOption/removeConfirm/<?php echo $option->getId(); ?>">
                <span class="material-symbols-outlined text-lg">
                    disabled_by_default
                </span>
                <span class="tooltip-text">Eliminar</span>
            </a>
        </div>
    </div>
</li>