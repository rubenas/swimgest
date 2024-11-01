<?php 
$questionary = $data['content']['questionary'];
$emails = $data['content']['emails'];

?>
<section class="modal" id="modal-send-questionary-email-confirm-<?php echo $questionary->getId()?>">
    <div class="modal-content">
        <header class="modal-header">
            <h3>Envío de email masivo a nadadores/as
                <span class="material-symbols-outlined close">
                    close
                </span>
            </h3>
        </header>
        <main class="modal-main">
            <form class="row ai-center jc-between" method="post" id="send-questionary-email-confirm-<?php echo $questionary->getId()?>" action="adminQuestionary/showCheckEmail/<?php echo $questionary->getId()?>" questionaryId="<?php echo $questionary->getId()?>">
                <div class="mt-1 col-12">
                    Quieres enviar un email a todos los nadadores/as?
                </div>
                <div class="mt-1 col-12 col-sm-6">
                    Selecciona la plantilla que quieres utilizar:
                </div>
                <div class="mt-1 col-12 col-sm-6">
                    <select name="template-id">
                        <option value=0 selected>Ninguna</option>
                        <?php
                            foreach ($emails as $email){
                                echo '<option value='.$email->getId().'>'.$email->getTitle().'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="mt-1 col-12 row">
                    <button type="submit" class="btn mr-1">
                        Sí, enviar email
                    </button>
                    <button type="reset" class="close btn-secondary">No, solo abrir plazo de respuesta</button>
                </div>
                <div class="error"></div>
            </form>
        </main>
        <hr class="mx-1">
        <footer class="modal-footer">
            Podrás editar el mensaje en la siguiente pantalla.
        </footer>
    </div>
</section>