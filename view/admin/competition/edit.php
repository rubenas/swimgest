<?php $competition = $data['content']['object'] ?>

<section class="modal" id="modal-edit-competition-<?php echo $competition->getId() ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Editar competición
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="edit-competition-<?php echo $competition->getId() ?>" action="adminCompetition/update/<?php echo $competition->getId() ?>" method="post" competitionId="<?php echo $competition->getId() ?>">
                <div class="mt-1 form-group col-12 col-sm-4">
                    <div>Nombre*</div>
                    <input type="text" name="name" required autocomplete="true" value="<?php echo $competition->getName() ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-4">
                    <div>Lugar*</div>
                    <input type="text" name="place" required autocomplete="true" value="<?php echo $competition->getPlace() ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <div>Máx. Pruebas*</div>
                    <input type="number" step="1" min="1" name="inscriptionsLimit" required value="<?php echo $competition->getInscriptionsLimit() ?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Ubicación</div>
                    <input type="url" name="location" value="<?php echo $competition->getLocation() ?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Descripción</div>
                    <textarea class="editor" name="description"><?php echo $competition->getDescription() ?></textarea>
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <div>F. Inicio*</div>
                    <input type="date" name="startDate" required value="<?php echo $competition->getStartDate() ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <div>F. Fin*</div>
                    <input type="date" name="endDate" required value="<?php echo $competition->getEndDate() ?>">
                </div>
                <div class="mt-1 form-group col-12 col-sm-5">
                    <div>Inscripciones hasta*</div>
                    <input type="datetime-local" name="inscriptionsDeadLine" required value="<?php echo $competition->getInscriptionsDeadLine() ?>">
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminCompetition/update/<?php echo $competition->getId() ?>/v"}'>Aceptar</button>
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