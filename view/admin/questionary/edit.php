<?php $questionary = $data['content']['object'] ?>
<section class="modal" id="modal-edit-questionary-<?php echo $questionary->getId() ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Editar cuestionario
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="edit-questionary-<?php echo $questionary->getId() ?>" action="adminQuestionary/update/<?php echo $questionary->getId() ?>" method="post" questionaryId="<?php echo $questionary->getId() ?>">
                <div class="mt-1 form-group col-12 col-sm-6">
                    <label for="questionary-name">Nombre*</label>
                    <input type="text" id="questionary-name" name="name" required autocomplete="true" value="<?php echo $questionary->getName() ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="questionary-deadLine">Inscripciones hasta*</label>
                    <input type="datetime-local" id="questionary-deadLine" name="deadLine" required value="<?php echo $questionary->getDeadLine() ?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="questionary-description">Descripción</label>
                    <textarea id="questionary-description" name="description" class="editor"><?php echo $questionary->getDescription() ?></textarea>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-questionary-btn" class="btn mr-1" ajax-request='{"url" : "adminQuestionary/update/<?php echo $questionary->getId() ?>/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            * Campos obligatorios
        </footer>
    </div>
</section>