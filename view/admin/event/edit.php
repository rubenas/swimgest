<?php $event = $data['content']['object']?>
<section class="modal" id="modal-edit-event-<?php echo $event->getId()?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Editar evento
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="edit-event-<?php echo $event->getId()?>" action="adminEvent/update/<?php echo $event->getId()?>" method="post" eventId="<?php echo $event->getId()?>">
                <div class="mt-1 form-group col-12 col-sm-6">
                    <label for="event-name">Nombre*</label>
                    <input type="text" id="event-name" name="name" required autocomplete="true" value="<?php echo $event->getName()?>">
                </div>
                <?php if($event->isTopParent()) {?>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-place">Lugar*</label>
                        <input type="text" id="event-place" name="place" required autocomplete="true" value="<?php echo $event->getPlace()?>">
                    </div>
                <?php } else {?>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-place">Lugar</label>
                        <input type="text" id="event-place" name="place" autocomplete="true" value="<?php echo $event->getPlace()?>">
                    </div>
                <?php } ?>
                <div class="mt-1 form-group col-12">
                    <label for="event-location">Ubicación</label>
                    <input type="url" id="event-location" name="location" value="<?php echo $event->getLocation()?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <label for="event-description">Descripción</label>
                    <textarea id="event-description" name="description" class="editor"><?php echo $event->getDescription()?></textarea>
                </div>
                <?php if($event->isTopParent()) {?>
                    <div class="mt-1 form-group col-12 col-sm-3">
                        <label for="event-startDate">F. Inicio*</label>
                        <input type="date" id="event-startDate" name="startDate" required value="<?php echo $event->getStartDate()?>">
                    </div>
                    <div class="mt-1 form-group col-12 col-sm-3">
                        <label for="event-endDate">F. Fin*</label>
                        <input type="date" id="event-endDate" name="endDate" required value="<?php echo $event->getEndDate()?>">
                    </div>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-deadLine">Inscripciones hasta*</label>
                        <input type="datetime-local" id="event-deadLine" name="deadLine" required value="<?php echo $event->getDeadLine()?>">
                    </div>
                <?php } else {?>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-startDate">F. Inicio*</label>
                        <input type="date" id="event-startDate" name="startDate" value="<?php echo $event->getStartDate()?>">
                    </div>
                    <div class="mt-1 form-group col-12 col-sm-5">
                        <label for="event-endDate">F. Fin</label>
                        <input type="date" id="event-endDate" name="endDate" value="<?php echo $event->getEndDate()?>">
                    </div>
                <?php } ?>
                <div class="mt-1 col-12 row">
                    <button type="submit" id="add-event-btn" class="btn mr-1" ajax-request='{"url" : "adminEvent/update/<?php echo $event->getId()?>/v"}'>Aceptar</button>
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