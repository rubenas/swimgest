<?php $event = $data['content']['object']; ?>
<section class="modal" id="modal-add-sub-event-<?php echo $event->getId() ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir sub-evento a <?php echo $event->getName() ?>
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="add-sub-event-<?php echo $event->getId() ?>" action="adminEvent/add" method="post" parentEventId="<?php echo $event->getId() ?>">
                <input type="hidden" name="parentId" value="<?php echo $event->getId() ?>">
                <form class="row ai-center jc-between" id="add-event" action="adminEvent/add" method="post" enctype="multipart/form-data">
                    <div class="mt-1 form-group col-12 col-sm-6">
                        <label for="event-name">Nombre*</label>
                        <input type="text" id="event-name" name="name" required autocomplete="true">
                    </div>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-place">Lugar</label>
                        <input type="text" id="event-place" name="place" autocomplete="true">
                    </div>
                    <div class="mt-1 form-group col-12">
                        <div>Imagen</div>
                        <input type="file" id="event-picture" name="picture" accept=".jpg,.png,.jpeg">
                    </div>
                    <div class="mt-1 form-group col-12">
                        <label for="event-location">Ubicación</label>
                        <input type="url" id="event-location" name="location">
                    </div>
                    <div class="mt-1 form-group col-12">
                        <label for="event-description">Descripción</label>
                        <textarea id="event-description" name="description" class="editor"></textarea>
                    </div>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-startDate">F. Inicio</label>
                        <input type="date" id="event-startDate" name="startDate">
                    </div>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-endDate">F. Fin</label>
                        <input type="date" id="event-endDate" name="endDate">
                    </div>
                    <div class="mt-1 col-12 row">
                        <button type="submit" id="add-event-btn" class="btn mr-1" ajax-request='{"url" : "adminEvent/ajaxAdd/v"}'>Aceptar</button>
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