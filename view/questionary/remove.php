<?php
    $questionary = $data['content']['object'];
?>
<section class="modal" id="modal-remove-answers-<?php echo $questionary->getId(); ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Eliminar competición
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">

            <form class="row ai-center jc-between" id="remove-answers-<?php echo $questionary->getId(); ?>" method="post" action="questionary/remove/<?php echo $questionary->getId(); ?>">
                <div class="mt-1 col-12">
                    Vas a eliminar tus respuestas al cuestionario <?php echo $questionary->getName() ?>.
                <div class="mt-1 col-12">
                    Estás seguro/a?
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1">Aceptar</button>
                    <button type="reset" class="close btn-secondary">Cancelar</button>
                </div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Esta acción no tiene vuelta atrás
        </footer>
    </div>
</section>