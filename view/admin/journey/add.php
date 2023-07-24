<?php $competition = $data['content']['object']?>
<section class="modal" id="modal-add-journey">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir jornada
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-journey" action="adminJourney/add" method="post">
                <input type="hidden" name="competitionId" value="<?php echo $competition->getId(); ?>">
                <input type="hidden" name="startDate" value="<?php echo $competition->getStartDate(); ?>">
                <input type="hidden" name="endDate" value="<?php echo $competition->getEndDate(); ?>">
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="journey-name">Nombre*</label>
                    <input type="text" id="journey-name" name="name" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="inscriptionsLimit">Máx. Pruebas*</label>
                    <input type="number" step="1" min="1" id="inscriptionsLimit" name="inscriptionsLimit" required>
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="date">Fecha*</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-competition-btn" class="btn mr-1" ajax-request='{"url" : "adminJourney/add/v"}'>Aceptar</button>
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