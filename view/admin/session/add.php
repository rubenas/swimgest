<?php $journey = $data['content']['object'] ?>
<section class="modal" id="modal-add-session-to-journey-<?php echo $journey->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir sesión
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-session-to-journey-<?php echo $journey->getId(); ?>" journeyId="<?php echo $journey->getId(); ?>" action="adminSession/add" method="post">
                <input type="hidden" name="journeyId" value="<?php echo $journey->getId(); ?>">
                <div class="mt-1 form-group col-12 col-sm-5">
                    <label for="session-name">Nombre*</label>
                    <input type="text" id="session-name" name="name" required autocomplete="true">
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="sessionInscriptionsLimit">Máx. Pruebas*</label>
                    <input type="number" step="1" min="1" id="sessionInscriptionsLimit" name="inscriptionsLimit" required>
                </div>
                <div class="mt-1 form-group col-12 col-sm-3">
                    <label for="time">Hora*</label>
                    <input type="time" id="time" name="time" required>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-session-btn" class="btn mr-1" ajax-request='{"url" : "adminSession/ajaxAdd/v"}'>Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
                <div class="success"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            *Campos obligatorios
        </footer>
    </div>
</section>