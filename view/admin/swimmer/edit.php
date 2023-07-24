<?php $swimmer = $data['content']['object']?>
<section class="modal" id="modal-edit-swimmer-<?php echo $swimmer->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Editar nadador/a
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">

            <form class="row ai-center jc-between" method="post" action="adminSwimmer/update/<?php echo $swimmer->getId(); ?>">
                <div class="mt-1 form-group col-12 col-sm-4">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" value="<?php echo $swimmer->getName(); ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-7">
                    <label for="surname">Apellidos</label>
                    <input type="text" id="surname" name="surname" value="<?php echo $swimmer->getSurname(); ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="gender">Género</label>
                    <select id="gender" name="gender">
                        <option value="" disabled>Género</option>
                        <option value="male" <?php
                                                if ($swimmer->getGender() == 'male') {
                                                    echo 'selected';
                                                }
                                                ?>>Masculino</option>
                        <option value="female" <?php
                                                if ($swimmer->getGender() == 'female') {
                                                    echo 'selected';
                                                }
                                                ?>>Femenino</option>
                    </select>
                </div>
                <div class="mt-1 form-group col-12 col-sm-4">
                    <label for="birtYear">Año de nacimiento</label>
                    <input type="number" min="1900" max="2099" step="1" id="birtYear" name="birthYear" value="<?php echo $swimmer->getBirthYear(); ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="name">Licencia</label>
                    <input type="text" id="licence" name="licence" value="<?php echo $swimmer->getLicence(); ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-7">
                    <label for="name">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $swimmer->getEmail(); ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-4">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" autocomplete="true">
                </div>
                <?php
                if ($data['id'] != $swimmer->getId() && $swimmer->getEmail() != 'admin@admin.com') { ?>
                    <div class="mt-1 col-12">
                        <label for="isAdmin" class="mr-1">Hacer administrador/a?</label>
                        <input type="checkbox" value=1 id="isAdmin" name="isAdmin" <?php
                                                                                    if ($swimmer->getIsAdmin() == true) {
                                                                                        echo 'checked';
                                                                                    }
                                                                                    ?>>
                    </div>
                <?php
                } ?>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="edit-swimmer-btn" class="btn mr-1">Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>