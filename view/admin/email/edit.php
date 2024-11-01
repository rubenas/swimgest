<?php $email = $data['content']['object'] ?>

<section class="modal" id="modal-edit-email-<?php echo $email->getId() ?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Editar plantilla de correo
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" id="edit-email-<?php echo $email->getId() ?>" action="adminEmail/update/<?php echo $email->getId() ?>" method="post" emailId="<?php echo $email->getId() ?>">
                <div class="mt-1 form-group col-12">
                    <div>Título*</div>
                    <input type="text" name="title" required autocomplete="true" value="<?php echo $email->getTitle() ?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Asunto*</div>
                    <input type="text" name="subject" required autocomplete="true" value="<?php echo $email->getSubject() ?>">
                </div>
                <div class="mt-1 form-group col-12">
                    <div>Cuerpo del mensaje</div>
                    <textarea class="editor" name="body"><?php echo $email->getBody() ?></textarea>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1" ajax-request='{"url" : "adminEmail/update/<?php echo $email->getId() ?>/v"}'>Aceptar</button>
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