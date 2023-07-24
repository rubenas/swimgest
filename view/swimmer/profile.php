<?php $swimmer = $data['content']['object'] ?>
<section id="profile" class="card mt-1 p-1 row jc-between ai-center">
    <div class="col-12 col-sm-4 profile-picture">
        <img src="<?php echo $swimmer->getPicture() . '?' . rand(0, 100000) ?>" class="img-rounded" id="profilePicture">
        <div class="edit-picture">
            <button class="tooltip btn-icon-success mr-1" modal-target="modal-add-profile-picture">
                <span class="material-symbols-outlined text-xl">
                    edit_note
                </span>
                <span class="tooltip-text">Editar</span>
            </button>
            <button class="tooltip btn-icon-error ml-1" modal-target="modal-remove-profile-picture">
                <span class="material-symbols-outlined text-xl">
                    disabled_by_default
                </span>
                <span class="tooltip-text">Eliminar</span>
            </button>
        </div>
    </div>
    <div class="col-12 col-sm-7">
        <p><strong>Nombre:</strong> <?php echo $swimmer->getName() . ' ' . $swimmer->getSurname() ?></p>
        <p class="mt-1"><strong>Género:</strong> <?php echo $translateToSpanish[$swimmer->getGender()] ?></p>
        <p class="mt-1"><strong>Licencia:</strong> <?php echo $swimmer->getLicence() ?></p>
        <p class="mt-1"><strong>Año de nacimiento:</strong> <?php echo $swimmer->getBirthYear() ?></p>
        <p class="mt-1"><strong>Email:</strong> <?php echo $swimmer->getEmail() ?>
            <button class="tooltip btn-icon-success col-12 col-sm-4 text-lg" modal-target="modal-edit-email">
                <span class="material-symbols-outlined">
                    edit_note
                </span>
                <span class="tooltip-text">Editar</span>
            </button>
        </p>
        <p class="mt-1">
            <button class="btn" modal-target="modal-edit-pass">Modificar contraseña</button>
        </p>
    </div>
    <div class="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
    <div class="success"><?php if (isset($data['content']['msg'])) echo $data['content']['msg']; ?></div>
</section>