<?php $swimmer = $data['content']['object']?>
<section class="modal" id="modal-remove-swimmer-<?php echo $swimmer->getId(); ?>">
        <div class="modal-content">
            <header class="modal-header">
                <h3>Eliminar nadador/a
                    <span class="material-symbols-outlined close">
                        close
                    </span>
                </h3>
            </header>
            <main class="modal-main">

                <form class="row ai-center jc-between" method="post" action="adminSwimmer/remove/<?php echo $swimmer->getId(); ?>">
                    <div class="mt-1 col-12">
                        Vas a eliminar a <?php echo $swimmer->getName() . ' ' . $swimmer->getSurName(); ?> de la lista de nadadores/as
                    </div>
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