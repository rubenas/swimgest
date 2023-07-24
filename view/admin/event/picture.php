<?php if(!isset($event)) $event = $data['content']['object'] ?>
<section class="col-12 col-sm-3 profile-picture" id="event-picture-<?php echo $event->getId()?>">
    <img src="<?php echo $event->getPicture(); ?>" class="img-card img-rounded" id="eventPicture">
    <div class="edit-picture">
        <button class="tooltip btn-icon-success mr-1" modal-target="modal-add-event-picture-<?php echo $event->getId()?>" ajax-request='{"url":"adminEvent/showAddPicture/<?php echo $event->getId()?>"}'>
            <span class="material-symbols-outlined text-xl">
                edit_note
            </span>
            <span class="tooltip-text">Editar</span>
        </button>
        <button class="tooltip btn-icon-error ml-1" modal-target="modal-remove-event-picture-<?php echo $event->getId()?>" ajax-request='{"url":"adminEvent/showRemovePicture/<?php echo $event->getId()?>"}'>
            <span class="material-symbols-outlined text-xl">
                disabled_by_default
            </span>
            <span class="tooltip-text">Eliminar</span>
        </button>
    </div>
</section>