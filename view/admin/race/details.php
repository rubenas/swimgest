<?php if(!isset($race)) $race=$data['content']['object']?>
<li class="pb-1">
    <div class="row w-100">
        <?php echo $race->getDistance() . ' ' . $translateToSpanish[$race->getStyle()] . ' ' . $translateToSpanish[$race->getGender()] ?>
        <div>
            <a class="btn-icon tooltip ml-1" href="adminRace/moveUp/<?php echo $race->getId(); ?>" id="move-race-up-<?php echo $race->getId(); ?>" ajax-request='{"url" : "adminRace/ajaxMoveUp/<?php echo $race->getId(); ?>/v"}' sessionId="<?php echo $race->getSessionId(); ?>">
                <span class="material-symbols-outlined text-lg">
                    arrow_upward
                </span>
                <span class="tooltip-text">Subir</span>
            </a>
            <a class="btn-icon tooltip mx-1" href="adminRace/moveDown/<?php echo $race->getId(); ?>" id="move-race-down-<?php echo $race->getId(); ?>" ajax-request='{"url" : "adminRace/ajaxMoveDown/<?php echo $race->getId(); ?>/v"}' sessionId="<?php echo $race->getSessionId(); ?>">
                <span class="material-symbols-outlined text-lg">
                    arrow_downward
                </span>
                <span class="tooltip-text">Bajar</span>
            </a>
            <a class="tooltip btn-icon-error" modal-target="modal-remove-race-<?php echo $race->getId(); ?>" ajax-request='{"url": "adminRace/removeConfirm/<?php echo $race->getId(); ?>/v"}' href="adminRace/removeConfirm/<?php echo $race->getId(); ?>">
                <span class="material-symbols-outlined text-lg">
                    disabled_by_default
                </span>
                <span class="tooltip-text">Eliminar</span>
            </a>
        </div>
    </div>
</li>