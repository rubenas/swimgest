<?php $questionary = $data['content']['object']?>
<section class="modal" id="modal-add-questionary-picture-<?php echo $questionary->getId()?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Añadir imagen del cuestionario
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row" id="add-questionary-picture-<?php echo $questionary->getPicture()?>" enctype="multipart/form-data" method="post" action="adminQuestionary/updatePicture/<?php echo $questionary->getId()?>" questionaryId="<?php echo $questionary->getId()?>">
                <div class="mt-1 mr-1 w-100">
                    <h4 class="mb-1">Seleccionar archivo</h4>
                    <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                    <input class="w-100" type="file" name="questionary-picture" accept=".png, .jpeg, .jpg" required>
                </div>
                <div class="w-100 mt-1">
                    <button class="close btn-secondary float-right">
                        Cancelar
                    </button>
                    <button type="submit" class="btn float-right mr-1" ajax-request='{"url": "adminQuestionary/ajaxUpdatePicture/<?php echo $questionary->getId()?>/v"}'>
                        Aceptar
                    </button>
                </div>
                <div class="error"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Seleciona un archivo jpg o png válido. Máximo 1MB.
        </footer>
    </div>
</section>