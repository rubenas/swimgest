<?php $competition = $data['content']['object']?>
<section class="modal" id="modal-remove-competition-picture-<?php echo $competition->getId()?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar imagen de la competición
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" method="post" id="remove-competition-picture-<?php echo $competition->getId()?>" action="adminCompetition/removePicture/<?php echo $competition->getId()?>" competitionId="<?php echo $competition->getId()?>">
                <div class="mt-1 col-12">
                    Vas a eliminar la imagen de la competición
                </div>
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminCompetition/ajaxRemovePicture/<?php echo $competition->getId()?>/v"}'>
                        Aceptar
                    </button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
                <div class="error"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Esta acción no tiene vuelta atrás
        </footer>
    </div>
</section>