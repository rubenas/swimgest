<?php if(!isset($questionary)) $questionary = $data['content']['object'] ?>
<section class="col-12 col-sm-3 profile-picture" id="questionary-picture-<?php echo $questionary->getId()?>">
    <img src="<?php echo $questionary->getPicture(); ?>" class="img-card img-rounded" id="questionaryPicture">
    <div class="edit-picture">
        <button class="tooltip btn-icon-success mr-1" modal-target="modal-add-questionary-picture-<?php echo $questionary->getId()?>" ajax-request='{"url":"adminQuestionary/showAddPicture/<?php echo $questionary->getId()?>"}'>
            <span class="material-symbols-outlined text-xl">
                edit_note
            </span>
            <span class="tooltip-text">Editar</span>
        </button>
        <button class="tooltip btn-icon-error ml-1" modal-target="modal-remove-questionary-picture-<?php echo $questionary->getId()?>" ajax-request='{"url":"adminQuestionary/showRemovePicture/<?php echo $questionary->getId()?>"}'>
            <span class="material-symbols-outlined text-xl">
                disabled_by_default
            </span>
            <span class="tooltip-text">Eliminar</span>
        </button>
    </div>
</section>