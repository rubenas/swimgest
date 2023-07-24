<?php if(!isset($competition)) $competition = $data['content']['object'] ?>
<section class="col-12 col-sm-3 profile-picture" id="competition-picture">
    <img src="<?php echo $competition->getPicture(); ?>" class="img-card img-rounded" id="competitionPicture">
    <div class="edit-picture">
        <button class="tooltip btn-icon-success mr-1" modal-target="modal-add-competition-picture-<?php echo $competition->getId()?>" ajax-request='{"url":"adminCompetition/showAddPicture/<?php echo $competition->getId()?>"}'>
            <span class="material-symbols-outlined text-xl">
                edit_note
            </span>
            <span class="tooltip-text">Editar</span>
        </button>
        <button class="tooltip btn-icon-error ml-1" modal-target="modal-remove-competition-picture-<?php echo $competition->getId()?>" ajax-request='{"url":"adminCompetition/showRemovePicture/<?php echo $competition->getId()?>"}'>
            <span class="material-symbols-outlined text-xl">
                disabled_by_default
            </span>
            <span class="tooltip-text">Eliminar</span>
        </button>
    </div>
</section>